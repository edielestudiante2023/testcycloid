<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['email', 'password_hash', 'nombre', 'rol'];
    protected $useTimestamps    = true;
    protected $returnType       = 'array';

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }
}
