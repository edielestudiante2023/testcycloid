<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateParticipantsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'                     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'sesion_id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nombre'                 => ['type' => 'VARCHAR', 'constraint' => 120],
            'documento'              => ['type' => 'VARCHAR', 'constraint' => 40],
            'cargo'                  => ['type' => 'VARCHAR', 'constraint' => 120],
            'email_corporativo'      => ['type' => 'VARCHAR', 'constraint' => 190],
            'email_personal'         => ['type' => 'VARCHAR', 'constraint' => 190, 'null' => true],
            'whatsapp'               => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'tiene_personal_a_cargo' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'autorizo_datos'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'mostro_liderazgo'       => ['type' => 'TINYINT', 'constraint' => 1, 'null' => true],
            'team'                   => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
            'role'                   => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
            'token'                  => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'created_at'             => ['type' => 'DATETIME', 'null' => true],
            'updated_at'             => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('token');
        $this->forge->addKey('sesion_id');
        $this->forge->addForeignKey('sesion_id', 'sesiones', 'id');
        $this->forge->createTable('participants');
    }

    public function down(): void
    {
        $this->forge->dropTable('participants');
    }
}
