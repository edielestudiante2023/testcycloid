<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsuariosTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'email'          => ['type' => 'VARCHAR', 'constraint' => 190],
            'password_hash'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'nombre'         => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'rol'            => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => 'admin'],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('usuarios');
    }

    public function down(): void
    {
        $this->forge->dropTable('usuarios');
    }
}
