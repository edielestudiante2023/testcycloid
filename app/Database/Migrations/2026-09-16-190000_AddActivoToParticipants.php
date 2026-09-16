<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * "Dar de baja" a un participante durante un ejercicio en vivo (se fue por
 * una emergencia, un imprevisto) sin borrar sus respuestas ya registradas.
 * activo=0 excluye a la persona del conteo de "totalEquipo" en todo lo
 * demas, para que su equipo no quede trabado esperando a alguien que ya no
 * va a responder.
 */
class AddActivoToParticipants extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('participants', [
            'activo' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'after' => 'autorizo_datos'],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('participants', 'activo');
    }
}
