<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['email', 'password_hash', 'nombre', 'rol', 'reset_token', 'reset_token_expires_at'];
    protected $useTimestamps    = true;
    protected $returnType       = 'array';

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    public function findByValidResetToken(string $token): ?array
    {
        return $this->where('reset_token', $token)
            ->where('reset_token_expires_at >=', date('Y-m-d H:i:s'))
            ->first();
    }

    public function setResetToken(int $id, string $token, string $expiresAt): void
    {
        $this->update($id, [
            'reset_token'            => $token,
            'reset_token_expires_at' => $expiresAt,
        ]);
    }

    public function clearResetToken(int $id): void
    {
        $this->update($id, [
            'reset_token'            => null,
            'reset_token_expires_at' => null,
        ]);
    }
}
