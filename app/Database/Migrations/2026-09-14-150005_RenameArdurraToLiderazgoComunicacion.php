<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * "Ardurra" era el cliente para el que se construyó esta dinámica, no el
 * nombre de la dinámica en sí. Corrige el slug/nombre sembrados en su momento.
 */
class RenameArdurraToLiderazgoComunicacion extends Migration
{
    public function up(): void
    {
        $this->db->table('dinamicas')
            ->where('slug', 'ardurra')
            ->update([
                'slug'       => 'liderazgo-comunicacion',
                'nombre'     => 'Liderazgo y Comunicación',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public function down(): void
    {
        $this->db->table('dinamicas')
            ->where('slug', 'liderazgo-comunicacion')
            ->update([
                'slug'       => 'ardurra',
                'nombre'     => 'Reto Ardurra',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
}
