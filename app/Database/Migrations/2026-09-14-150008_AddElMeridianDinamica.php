<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddElMeridianDinamica extends Migration
{
    public function up(): void
    {
        $existing = $this->db->table('dinamicas')->where('slug', 'el-meridian')->get()->getRow();
        if ($existing) {
            return;
        }

        $this->db->table('dinamicas')->insert([
            'slug'        => 'el-meridian',
            'nombre'      => 'El Meridián',
            'descripcion' => 'Un carguero navega hacia una tormenta que crece. Cada rol ve solo una parte de la situación y debe decidir, en varios momentos, qué tan claro se atreve a decirlo.',
            'activa'      => 1,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    public function down(): void
    {
        $this->db->table('dinamicas')->where('slug', 'el-meridian')->delete();
    }
}
