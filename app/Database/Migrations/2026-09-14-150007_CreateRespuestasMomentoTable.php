<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRespuestasMomentoTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'participant_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'momento'        => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true],
            'respuesta'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['participant_id', 'momento']);
        $this->forge->addForeignKey('participant_id', 'participants', 'id', '', 'CASCADE');
        $this->forge->createTable('respuestas_momento');
    }

    public function down(): void
    {
        $this->forge->dropTable('respuestas_momento');
    }
}
