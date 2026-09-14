<?php

namespace App\Models;

use CodeIgniter\Model;

class RespuestaMomentoModel extends Model
{
    protected $table         = 'respuestas_momento';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['participant_id', 'momento', 'respuesta'];
    protected $useTimestamps = true;
    protected $updatedField  = '';
    protected $returnType    = 'array';

    /**
     * Guarda la respuesta de un participante a un momento. Si ya existía
     * (reintento, doble clic), no la sobrescribe — la primera respuesta manda.
     */
    public function guardar(int $participantId, int $momento, string $respuesta): void
    {
        $existente = $this->where('participant_id', $participantId)->where('momento', $momento)->first();
        if ($existente) {
            return;
        }

        $this->insert([
            'participant_id' => $participantId,
            'momento'        => $momento,
            'respuesta'      => $respuesta,
        ]);
    }

    public function respuestaDe(int $participantId, int $momento): ?array
    {
        return $this->where('participant_id', $participantId)->where('momento', $momento)->first();
    }

    /**
     * Cuenta cuántos miembros de un equipo ya respondieron un momento dado.
     */
    public function respondidosEnMomento(int $sesionId, string $team, int $momento): int
    {
        return $this->db->table('respuestas_momento rm')
            ->join('participants p', 'p.id = rm.participant_id')
            ->where('p.sesion_id', $sesionId)
            ->where('p.team', $team)
            ->where('rm.momento', $momento)
            ->countAllResults();
    }

    /**
     * Encuentra el primer momento (1..$totalMomentos) que el equipo todavía no
     * completó entre todos sus miembros. Si ya completaron todos, devuelve
     * $totalMomentos + 1 (el ejercicio terminó para ese equipo).
     */
    public function momentoActualDelEquipo(int $sesionId, string $team, int $totalEquipo, int $totalMomentos): int
    {
        for ($momento = 1; $momento <= $totalMomentos; $momento++) {
            if ($this->respondidosEnMomento($sesionId, $team, $momento) < $totalEquipo) {
                return $momento;
            }
        }

        return $totalMomentos + 1;
    }

    /**
     * Progreso de cada equipo de una sesión de El Meridián: en qué momento
     * está, cuántos de sus miembros ya respondieron ese momento, y el total
     * de momentos que tiene su recorrido. Para el panel del facilitador.
     *
     * @return array<int, array{team: string, totalEquipo: int, momentoActual: int, respondidos: int, totalMomentos: int, terminado: bool}>
     */
    public function progresoPorEquipo(int $sesionId): array
    {
        helper('el_meridian');

        $participantModel = new ParticipantModel();
        $miembros = $participantModel->where('sesion_id', $sesionId)->where('team IS NOT NULL')->findAll();

        $porEquipo = [];
        foreach ($miembros as $miembro) {
            $porEquipo[$miembro['team']][] = $miembro;
        }

        $momentosDefinidos = el_meridian_momentos();
        $progreso = [];
        foreach ($porEquipo as $team => $integrantes) {
            $totalMomentos = 0;
            foreach ($integrantes as $integrante) {
                $totalMomentos = max($totalMomentos, count($momentosDefinidos[$integrante['role']]['momentos'] ?? []));
            }
            $totalEquipo = count($integrantes);
            $momentoActual = $this->momentoActualDelEquipo($sesionId, $team, $totalEquipo, $totalMomentos);
            $terminado = $momentoActual > $totalMomentos;

            $progreso[] = [
                'team'          => $team,
                'totalEquipo'   => $totalEquipo,
                'momentoActual' => $terminado ? $totalMomentos : $momentoActual,
                'respondidos'   => $terminado ? $totalEquipo : $this->respondidosEnMomento($sesionId, $team, $momentoActual),
                'totalMomentos' => $totalMomentos,
                'terminado'     => $terminado,
            ];
        }

        usort($progreso, static fn ($a, $b) => strnatcmp($a['team'], $b['team']));
        return $progreso;
    }

    /**
     * Rellena con una respuesta "forzada" a quien le falte responder un
     * momento, para que el equipo pueda avanzar sin quedar atascado por un
     * rezagado. Uso exclusivo del facilitador.
     */
    public function forzarAvance(int $sesionId, string $team, int $momento): int
    {
        $participantModel = new ParticipantModel();
        $miembros = $participantModel->where('sesion_id', $sesionId)->where('team', $team)->findAll();

        $forzados = 0;
        foreach ($miembros as $miembro) {
            if (!$this->respuestaDe((int) $miembro['id'], $momento)) {
                $this->guardar((int) $miembro['id'], $momento, 'sin_respuesta');
                $forzados++;
            }
        }

        return $forzados;
    }
}
