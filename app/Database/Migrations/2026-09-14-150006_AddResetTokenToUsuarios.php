<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResetTokenToUsuarios extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('usuarios', [
            'reset_token'            => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true, 'after' => 'rol'],
            'reset_token_expires_at' => ['type' => 'DATETIME', 'null' => true, 'after' => 'reset_token'],
        ]);
        $this->forge->addKey('reset_token');
        $this->forge->processIndexes('usuarios');
    }

    public function down(): void
    {
        $this->forge->dropColumn('usuarios', ['reset_token', 'reset_token_expires_at']);
    }
}
