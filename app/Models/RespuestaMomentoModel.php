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
     * Qué opciones del último momento de cada rol reflejan que la persona
     * sintió que su opinión sí llegó con claridad a la decisión final.
     * Es solo para el resumen del cierre reflexivo — no afecta el ejercicio en sí.
     */
    private const POLARIDAD_MOMENTO_FINAL = [
        'A' => ['bastante' => true, 'poco' => false, 'casi_nada' => false],
        'B' => ['si_claro' => true, 'parcial' => false, 'no' => false],
        'C' => ['claro' => true, 'suave' => false, 'no_dichas' => false],
        'D' => ['si' => true, 'parcial' => false, 'no' => false],
        'E' => ['hablo_claridad' => true, 'opiniones_pequenas' => false, 'silencio_decidio' => false],
    ];

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
     * Resultados completos para el cierre reflexivo: por equipo, el desglose de
     * respuestas de cada momento (cuántos eligieron cada opción) y un
     * resumen de si las opiniones individuales llegaron claras al final.
     *
     * @return array<int, array<string, mixed>>
     */
    public function resultadosPorEquipo(int $sesionId): array
    {
        helper('el_meridian');
        $momentosDefinidos = el_meridian_momentos();

        $participantModel = new ParticipantModel();
        $miembros = $participantModel->where('sesion_id', $sesionId)->where('team IS NOT NULL')->findAll();

        $porEquipo = [];
        foreach ($miembros as $miembro) {
            $porEquipo[$miembro['team']][] = $miembro;
        }

        $resultado = [];
        foreach ($porEquipo as $team => $integrantes) {
            $totalMomentos = 0;
            foreach ($integrantes as $integrante) {
                $totalMomentos = max($totalMomentos, count($momentosDefinidos[$integrante['role']]['momentos'] ?? []));
            }

            $momentosData = [];
            for ($momento = 1; $momento <= $totalMomentos; $momento++) {
                $porRespuesta = [];
                foreach ($integrantes as $integrante) {
                    $respuesta = $this->respuestaDe((int) $integrante['id'], $momento);
                    if (!$respuesta || $respuesta['respuesta'] === 'sin_respuesta') {
                        continue;
                    }
                    $definicion = $momentosDefinidos[$integrante['role']]['momentos'][$momento] ?? null;
                    $etiqueta = $definicion['opciones'][$respuesta['respuesta']] ?? $respuesta['respuesta'];
                    $clave = $integrante['role'] . ':' . $respuesta['respuesta'];
                    if (!isset($porRespuesta[$clave])) {
                        $porRespuesta[$clave] = ['etiqueta' => $etiqueta, 'rol' => $integrante['role'], 'count' => 0];
                    }
                    $porRespuesta[$clave]['count']++;
                }
                $momentosData[$momento] = array_values($porRespuesta);
            }

            $positivos = 0;
            $totalConDato = 0;
            foreach ($integrantes as $integrante) {
                $respuesta = $this->respuestaDe((int) $integrante['id'], $totalMomentos);
                if (!$respuesta || $respuesta['respuesta'] === 'sin_respuesta') {
                    continue;
                }
                $mapa = self::POLARIDAD_MOMENTO_FINAL[$integrante['role']] ?? null;
                if ($mapa === null || !array_key_exists($respuesta['respuesta'], $mapa)) {
                    continue;
                }
                $totalConDato++;
                if ($mapa[$respuesta['respuesta']]) {
                    $positivos++;
                }
            }

            $resultado[] = [
                'team'          => $team,
                'totalEquipo'   => count($integrantes),
                'totalMomentos' => $totalMomentos,
                'momentos'      => $momentosData,
                'positivos'     => $positivos,
                'totalConDato'  => $totalConDato,
                'resumenTexto'  => $this->resumenTexto($positivos, $totalConDato),
            ];
        }

        usort($resultado, static fn ($a, $b) => strnatcmp($a['team'], $b['team']));
        return $resultado;
    }

    /**
     * Igual que resultadosPorEquipo() pero desglosado por persona (con
     * nombre), para alimentar el análisis de Kimi — ese sí necesita ver el
     * patrón individual a través de los momentos, no solo los conteos.
     *
     * @return array<int, array{team: string, personas: array<int, array{nombre: string, rol: string, momentos: array<int, string>}>}>
     */
    public function respuestasPorPersona(int $sesionId): array
    {
        helper('el_meridian');
        $momentosDefinidos = el_meridian_momentos();

        $participantModel = new ParticipantModel();
        $miembros = $participantModel->where('sesion_id', $sesionId)->where('team IS NOT NULL')->findAll();

        $porEquipo = [];
        foreach ($miembros as $miembro) {
            $porEquipo[$miembro['team']][] = $miembro;
        }

        $resultado = [];
        foreach ($porEquipo as $team => $integrantes) {
            $personas = [];
            foreach ($integrantes as $integrante) {
                $totalMomentos = count($momentosDefinidos[$integrante['role']]['momentos'] ?? []);
                $momentos = [];
                for ($momento = 1; $momento <= $totalMomentos; $momento++) {
                    $respuesta = $this->respuestaDe((int) $integrante['id'], $momento);
                    if (!$respuesta || $respuesta['respuesta'] === 'sin_respuesta') {
                        continue;
                    }
                    $definicion = $momentosDefinidos[$integrante['role']]['momentos'][$momento] ?? null;
                    $momentos[$momento] = $definicion['opciones'][$respuesta['respuesta']] ?? $respuesta['respuesta'];
                }

                if (!empty($momentos)) {
                    $personas[] = [
                        'nombre'   => $integrante['nombre'],
                        'rol'      => $momentosDefinidos[$integrante['role']]['nombre'] ?? $integrante['role'],
                        'momentos' => $momentos,
                    ];
                }
            }

            if (!empty($personas)) {
                $resultado[] = ['team' => $team, 'personas' => $personas];
            }
        }

        usort($resultado, static fn ($a, $b) => strnatcmp($a['team'], $b['team']));
        return $resultado;
    }

    /**
     * Radiografía del equipo: 6 dimensiones calculadas matemáticamente a
     * partir de las opciones que cada quien eligió — nada estimado a ojo.
     * Las primeras 4 salen directo del promedio de puntajes del momento
     * correspondiente (ver ElMeridianDimensiones); "Persistencia ante
     * presión" compara el promedio del momento 3 contra el 4 (el punto de
     * quiebre); "Percepción de haber sido escuchado" reutiliza el mismo
     * cálculo que ya usa resultadosPorEquipo().
     *
     * @return array<int, array{team: string, dimensiones: array<string, string>, escuchados: string}>
     */
    public function radiografiaPorEquipo(int $sesionId): array
    {
        $porPersona = $this->respuestasPorPersonaConValores($sesionId);

        $resultado = [];
        foreach ($porPersona as $team => $integrantes) {
            $resultado[] = ['team' => $team] + $this->calcularDimensiones($integrantes);
        }

        usort($resultado, static fn ($a, $b) => strnatcmp($a['team'], $b['team']));
        return $resultado;
    }

    /**
     * Igual que radiografiaPorEquipo() pero consolidando a TODOS los equipos
     * de la sesión en un solo cálculo — la vista "resumen global" de la
     * empresa. Mismas reglas, sin distinguir equipo.
     *
     * @return array{dimensiones: array<string, string>, escuchados: string}
     */
    public function radiografiaGlobal(int $sesionId): array
    {
        $porPersona = $this->respuestasPorPersonaConValores($sesionId);

        $todos = [];
        foreach ($porPersona as $integrantes) {
            $todos = array_merge($todos, $integrantes);
        }

        if (empty($todos)) {
            return [];
        }

        return $this->calcularDimensiones($todos);
    }

    /**
     * @param array<int, array{nombre: string, role: string, valores: array<int, string>}> $integrantes
     * @return array{dimensiones: array<string, string>, escuchados: string}
     */
    private function calcularDimensiones(array $integrantes): array
    {
        $promedios = [1 => [], 2 => [], 3 => [], 4 => []];
        foreach ($integrantes as $integrante) {
            foreach ($integrante['valores'] as $momento => $valor) {
                if ($momento > 4) {
                    continue;
                }
                $puntaje = ElMeridianDimensiones::puntaje($momento, $integrante['role'], $valor);
                if ($puntaje !== null) {
                    $promedios[$momento][] = $puntaje;
                }
            }
        }

        $promedio = static fn (array $p) => empty($p) ? null : array_sum($p) / count($p);
        $p1 = $promedio($promedios[1]);
        $p2 = $promedio($promedios[2]);
        $p3 = $promedio($promedios[3]);
        $p4 = $promedio($promedios[4]);

        $dimensiones = [
            ElMeridianDimensiones::NOMBRES_DIMENSION[1] => $p1 === null ? '—' : ElMeridianDimensiones::nivel($p1),
            ElMeridianDimensiones::NOMBRES_DIMENSION[2] => $p2 === null ? '—' : ElMeridianDimensiones::nivel($p2),
            ElMeridianDimensiones::NOMBRES_DIMENSION[3] => $p3 === null ? '—' : ElMeridianDimensiones::nivel($p3),
            ElMeridianDimensiones::NOMBRES_DIMENSION[4] => $p4 === null ? '—' : ElMeridianDimensiones::nivel($p4),
            'Persistencia ante presión' => ($p3 === null || $p4 === null)
                ? '—'
                : ElMeridianDimensiones::nivel($p4 - $p3 + 1),
        ];

        $positivos = 0;
        $totalConDato = 0;
        foreach ($integrantes as $integrante) {
            $valorFinal = $integrante['valores'][5] ?? null;
            $mapa = self::POLARIDAD_MOMENTO_FINAL[$integrante['role']] ?? null;
            if ($valorFinal === null || $mapa === null || !array_key_exists($valorFinal, $mapa)) {
                continue;
            }
            $totalConDato++;
            if ($mapa[$valorFinal]) {
                $positivos++;
            }
        }

        return [
            'dimensiones' => $dimensiones,
            'escuchados'  => $totalConDato === 0 ? '—' : "{$positivos}/{$totalConDato}",
        ];
    }

    /**
     * Igual que respuestasPorPersona() pero conserva el valor crudo de cada
     * opción (no la etiqueta) — lo necesita radiografiaPorEquipo() para
     * buscar el puntaje en ElMeridianDimensiones.
     *
     * @return array<string, array<int, array{nombre: string, role: string, valores: array<int, string>}>>
     */
    private function respuestasPorPersonaConValores(int $sesionId): array
    {
        $participantModel = new ParticipantModel();
        $miembros = $participantModel->where('sesion_id', $sesionId)->where('team IS NOT NULL')->findAll();

        $porEquipo = [];
        foreach ($miembros as $miembro) {
            $valores = [];
            for ($momento = 1; $momento <= 5; $momento++) {
                $respuesta = $this->respuestaDe((int) $miembro['id'], $momento);
                if ($respuesta && $respuesta['respuesta'] !== 'sin_respuesta') {
                    $valores[$momento] = $respuesta['respuesta'];
                }
            }
            if (!empty($valores)) {
                $porEquipo[$miembro['team']][] = [
                    'nombre' => $miembro['nombre'],
                    'role'   => $miembro['role'],
                    'valores' => $valores,
                ];
            }
        }

        return $porEquipo;
    }

    private function resumenTexto(int $positivos, int $total): string
    {
        if ($total === 0) {
            return 'Todavía no hay suficientes respuestas para sacar una conclusión.';
        }

        $ratio = $positivos / $total;
        if ($ratio >= 0.75) {
            return 'La mayoría sintió que su opinión sí se reflejó en la decisión final del equipo.';
        }

        if ($ratio >= 0.4) {
            return 'A medias: algunas opiniones llegaron a la decisión final, otras se quedaron en el camino.';
        }

        return 'La mayoría sintió que su opinión NO llegó a influir en la decisión final — señal de que hablar no es lo mismo que ser escuchado.';
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
