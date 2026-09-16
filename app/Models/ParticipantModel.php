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
        'team', 'role', 'token', 'intro_visto_at', 'activo',
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

    /**
     * Copia los participantes de una sesión anterior (de cualquier dinámica)
     * hacia una sesión nueva, para no hacer que el mismo grupo se registre
     * de cero en cada ejercicio. Copia solo los datos de la persona —
     * equipo, rol, token e intro_visto_at siempre arrancan limpios en la
     * sesión destino. Si alguien con el mismo documento ya está registrado
     * en la sesión destino, se salta (evita duplicar si se corre dos veces).
     *
     * @return int cuántos participantes se copiaron
     */
    public function copiarDesdeOtraSesion(int $sesionOrigenId, int $sesionDestinoId): int
    {
        $origen = $this->where('sesion_id', $sesionOrigenId)->findAll();
        if (empty($origen)) {
            return 0;
        }

        $yaEnDestino = array_column($this->where('sesion_id', $sesionDestinoId)->findAll(), null, 'documento');

        $copiados = 0;
        foreach ($origen as $p) {
            if (isset($yaEnDestino[$p['documento']])) {
                continue;
            }

            $this->insert([
                'sesion_id'              => $sesionDestinoId,
                'nombre'                 => $p['nombre'],
                'documento'              => $p['documento'],
                'cargo'                  => $p['cargo'],
                'email_corporativo'      => $p['email_corporativo'],
                'email_personal'         => $p['email_personal'],
                'whatsapp'               => $p['whatsapp'],
                'tiene_personal_a_cargo' => $p['tiene_personal_a_cargo'],
                'autorizo_datos'         => $p['autorizo_datos'],
            ]);
            $copiados++;
        }

        return $copiados;
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
    public function darDeBaja(int $participantId): void
    {
        $this->update($participantId, ['activo' => 0]);
    }

    public function reactivar(int $participantId): void
    {
        $this->update($participantId, ['activo' => 1]);
    }

    public function iniciarEjercicio(int $sesionId, int $teamSize): array
    {
        $pendientes = $this->where('sesion_id', $sesionId)->where('team', null)->where('activo', 1)->findAll();
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
