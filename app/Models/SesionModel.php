<?php

namespace App\Models;

use CodeIgniter\Model;

class SesionModel extends Model
{
    protected $table         = 'sesiones';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'dinamica_id', 'usuario_id', 'cliente', 'token', 'estado',
        'team_size', 'duracion_min', 'iniciada_at', 'cerrada_at', 'analisis_ia',
    ];
    protected $useTimestamps = true;
    protected $returnType    = 'array';

    public function findByToken(string $token): ?array
    {
        return $this->select('sesiones.*, dinamicas.slug AS dinamica_slug, dinamicas.nombre AS dinamica_nombre')
            ->join('dinamicas', 'dinamicas.id = sesiones.dinamica_id')
            ->where('sesiones.token', $token)
            ->first();
    }

    public function porDinamica(int $dinamicaId): array
    {
        $builder = $this->db->table('sesiones s');
        $builder->select('s.*, u.email AS creado_por,
            (SELECT COUNT(*) FROM participants p WHERE p.sesion_id = s.id) AS total_participantes')
            ->join('usuarios u', 'u.id = s.usuario_id')
            ->where('s.dinamica_id', $dinamicaId)
            ->orderBy('s.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Sesiones recientes de CUALQUIER dinámica que ya tengan al menos un
     * participante registrado — para el selector de "reciclar participantes
     * de otra sesión". Excluye la sesión actual.
     *
     * @return array<int, array<string, mixed>>
     */
    public function recientesConParticipantes(int $excluirSesionId, int $limite = 30): array
    {
        $builder = $this->db->table('sesiones s');
        $builder->select('s.id, s.token, s.cliente, s.created_at, d.nombre AS dinamica_nombre,
            (SELECT COUNT(*) FROM participants p WHERE p.sesion_id = s.id) AS total_participantes')
            ->join('dinamicas d', 'd.id = s.dinamica_id')
            ->where('s.id !=', $excluirSesionId)
            ->having('total_participantes >', 0)
            ->orderBy('s.created_at', 'DESC')
            ->limit($limite);

        return $builder->get()->getResultArray();
    }

    public function crearToken(): string
    {
        return bin2hex(random_bytes(6));
    }
}
