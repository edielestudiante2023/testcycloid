<?php

namespace App\Models;

use CodeIgniter\Model;

class ParticipantModel extends Model
{
    protected $table         = 'participants';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'sesion_id', 'nombre', 'documento', 'cargo', 'email_corporativo', 'email_personal',
        'whatsapp', 'tiene_personal_a_cargo', 'autorizo_datos', 'mostro_liderazgo',
        'team', 'role', 'token', 'intro_visto_at',
    ];
    protected $useTimestamps = true;
    protected $returnType    = 'array';

    public const ROLES_5 = ['A', 'B', 'C', 'D', 'E'];
    public const ROLES_4 = ['A', 'B', 'C', 'E'];

    public function rolePool(int $size): array
    {
        return $size === 4 ? self::ROLES_4 : self::ROLES_5;
    }

    public function porSesion(int $sesionId): array
    {
        return $this->where('sesion_id', $sesionId)
            ->orderBy('team IS NULL', 'ASC', false)
            ->orderBy('team', 'ASC')
            ->orderBy('role', 'ASC')
            ->findAll();
    }

    public function contarRegistrados(int $sesionId): int
    {
        return $this->where('sesion_id', $sesionId)->countAllResults();
    }

    public function findByToken(string $token): ?array
    {
        return $this->select('participants.*, sesiones.token AS sesion_token, dinamicas.slug AS dinamica_slug')
            ->join('sesiones', 'sesiones.id = participants.sesion_id')
            ->join('dinamicas', 'dinamicas.id = sesiones.dinamica_id')
            ->where('participants.token', $token)
            ->first();
    }

    /**
     * Forma equipos del tamaño dado con todos los participantes pendientes
     * (team IS NULL), asigna roles sin repetir dentro de cada equipo y
     * genera su token de acceso al rol.
     *
     * @return array<int, array<string, mixed>> participantes recién asignados
     */
    public function iniciarEjercicio(int $sesionId, int $teamSize): array
    {
        $pendientes = $this->where('sesion_id', $sesionId)->where('team', null)->findAll();
        if (empty($pendientes)) {
            return [];
        }

        shuffle($pendientes);
        $grupos = array_chunk($pendientes, $teamSize);
        $asignados = [];

        foreach ($grupos as $i => $grupo) {
            $teamLabel = 'Equipo ' . ($i + 1);
            $pool = $this->rolePool($teamSize);
            shuffle($pool);
            $roles = array_slice($pool, 0, count($grupo));

            foreach ($grupo as $j => $participant) {
                $role = $roles[$j];
                $token = bin2hex(random_bytes(8));
                $this->update($participant['id'], [
                    'team'  => $teamLabel,
                    'role'  => $role,
                    'token' => $token,
                ]);
                $participant['team'] = $teamLabel;
                $participant['role'] = $role;
                $participant['token'] = $token;
                $asignados[] = $participant;
            }
        }

        return $asignados;
    }
}
