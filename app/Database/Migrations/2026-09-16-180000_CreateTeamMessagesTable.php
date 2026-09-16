<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Chat por equipo, aislado por sesion+team -- ningun equipo ve los mensajes
 * de otro. Es un canal de conversacion en vivo, no se usa en el analisis ni
 * en los resultados.
 */
class CreateTeamMessagesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'sesion_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'team'           => ['type' => 'VARCHAR', 'constraint' => 50],
            'participant_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nombre'         => ['type' => 'VARCHAR', 'constraint' => 120],
            'mensaje'        => ['type' => 'VARCHAR', 'constraint' => 500],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['sesion_id', 'team']);
        $this->forge->addForeignKey('sesion_id', 'sesiones', 'id', '', 'CASCADE');
        $this->forge->addForeignKey('participant_id', 'participants', 'id', '', 'CASCADE');
        $this->forge->createTable('team_messages');
    }

    public function down(): void
    {
        $this->forge->dropTable('team_messages');
    }
}
