<?php

namespace App\Models;

use CodeIgniter\Model;

class DinamicaModel extends Model
{
    protected $table         = 'dinamicas';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['slug', 'nombre', 'descripcion', 'activa'];
    protected $useTimestamps = true;
    protected $returnType    = 'array';

    public function findBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }

    public function activas(): array
    {
        return $this->where('activa', 1)->orderBy('nombre')->findAll();
    }
}
