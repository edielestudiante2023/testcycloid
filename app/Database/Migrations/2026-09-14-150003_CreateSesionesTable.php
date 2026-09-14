<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSesionesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'dinamica_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'usuario_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'cliente'      => ['type' => 'VARCHAR', 'constraint' => 150],
            'token'        => ['type' => 'VARCHAR', 'constraint' => 32],
            'estado'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'activa'],
            'team_size'    => ['type' => 'TINYINT', 'constraint' => 3, 'default' => 5],
            'duracion_min' => ['type' => 'INT', 'constraint' => 11, 'default' => 12],
            'iniciada_at'  => ['type' => 'DATETIME', 'null' => true],
            'cerrada_at'   => ['type' => 'DATETIME', 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('token');
        $this->forge->addKey('dinamica_id');
        $this->forge->addForeignKey('dinamica_id', 'dinamicas', 'id');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id');
        $this->forge->createTable('sesiones');
    }

    public function down(): void
    {
        $this->forge->dropTable('sesiones');
    }
}
