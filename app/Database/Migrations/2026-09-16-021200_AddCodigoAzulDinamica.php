<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCodigoAzulDinamica extends Migration
{
    public function up(): void
    {
        $existing = $this->db->table('dinamicas')->where('slug', 'codigo-azul')->get()->getRow();
        if ($existing) {
            return;
        }

        $this->db->table('dinamicas')->insert([
            'slug'        => 'codigo-azul',
            'nombre'      => 'Código Azul',
            'descripcion' => 'Un equipo de emergencias en un hospital ficticio debe integrar señales distintas antes de que la presión del tiempo cierre la decisión sin ellas.',
            'activa'      => 1,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    public function down(): void
    {
        $this->db->table('dinamicas')->where('slug', 'codigo-azul')->delete();
    }
}
