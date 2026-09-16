<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Gate para asegurar que el participante pase por la pantalla de "intro"
 * antes de poder ver su rol — sin esto, el correo manda dos links (intro y
 * rol) y nada impide saltarse el intro yendo directo al rol. Ver
 * GUIA_MODULOS_CONDUCTUALES.md, sección 2, "Forzar la lectura del intro".
 */
class AddIntroVistoAtToParticipants extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('participants', [
            'intro_visto_at' => ['type' => 'DATETIME', 'null' => true, 'after' => 'role'],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('participants', 'intro_visto_at');
    }
}
