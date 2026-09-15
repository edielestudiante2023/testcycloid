<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAnalisisIaToSesiones extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('sesiones', [
            'analisis_ia' => ['type' => 'TEXT', 'null' => true, 'after' => 'cerrada_at'],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('sesiones', 'analisis_ia');
    }
}
