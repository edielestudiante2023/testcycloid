<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVolverACasaDinamica extends Migration
{
    public function up(): void
    {
        $existing = $this->db->table('dinamicas')->where('slug', 'volver-a-casa')->get()->getRow();
        if ($existing) {
            return;
        }

        $this->db->table('dinamicas')->insert([
            'slug'        => 'volver-a-casa',
            'nombre'      => 'Volver a Casa',
            'descripcion' => 'Una nave sufre una explosión a mitad de misión. En tierra, un equipo de especialistas con información distribuida debe coordinarse, bajo presión de tiempo y jerarquía, para traer a la tripulación de vuelta.',
            'activa'      => 1,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    public function down(): void
    {
        $this->db->table('dinamicas')->where('slug', 'volver-a-casa')->delete();
    }
}
