<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDinamicasTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 60],
            'nombre'      => ['type' => 'VARCHAR', 'constraint' => 120],
            'descripcion' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'activa'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('dinamicas');
    }

    public function down(): void
    {
        $this->forge->dropTable('dinamicas');
    }
}
