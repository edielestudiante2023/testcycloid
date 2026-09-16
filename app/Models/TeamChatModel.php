<?php

namespace App\Models;

use CodeIgniter\Model;

class TeamChatModel extends Model
{
    protected $table         = 'team_messages';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['sesion_id', 'team', 'participant_id', 'nombre', 'mensaje'];
    protected $useTimestamps = true;
    protected $updatedField  = '';
    protected $returnType    = 'array';

    public function enviar(int $sesionId, string $team, int $participantId, string $nombre, string $mensaje): void
    {
        $mensaje = trim($mensaje);
        if ($mensaje === '') {
            return;
        }

        $this->insert([
            'sesion_id'      => $sesionId,
            'team'           => $team,
            'participant_id' => $participantId,
            'nombre'         => $nombre,
            'mensaje'        => mb_substr($mensaje, 0, 500),
        ]);
    }

    /**
     * @return array<int, array{id: int, nombre: string, mensaje: string, hora: string}>
     */
    public function mensajesDe(int $sesionId, string $team, int $desdeId = 0): array
    {
        $filas = $this->where('sesion_id', $sesionId)
            ->where('team', $team)
            ->where('id >', $desdeId)
            ->orderBy('id', 'ASC')
            ->limit(200)
            ->findAll();

        return array_map(static fn ($m) => [
            'id'      => (int) $m['id'],
            'nombre'  => $m['nombre'],
            'mensaje' => $m['mensaje'],
            'hora'    => substr((string) $m['created_at'], 11, 5),
        ], $filas);
    }
}
