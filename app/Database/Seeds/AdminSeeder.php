<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Siembra la dinamica "liderazgo-comunicacion" y el usuario administrador principal.
 * Las credenciales del usuario semilla viven en un archivo NO versionado
 * (app/Config/SeedAdmin.local.php / .production.php).
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $existing = $this->db->table('dinamicas')->where('slug', 'liderazgo-comunicacion')->get()->getRow();
        if (!$existing) {
            $this->db->table('dinamicas')->insert([
                'slug'        => 'liderazgo-comunicacion',
                'nombre'      => 'Liderazgo y Comunicación',
                'descripcion' => 'Dinámica de coordinación bajo presión con roles secretos y entrega simulada a un cliente.',
                'activa'      => 1,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
            echo "- dinamica 'liderazgo-comunicacion' creada\n";
        } else {
            echo "- dinamica 'liderazgo-comunicacion' ya existe\n";
        }

        $seedFile = APPPATH . 'Config/SeedAdmin.' . ENVIRONMENT . '.php';
        if (!is_file($seedFile)) {
            echo "- falta {$seedFile}, no se crea usuario admin\n";
            return;
        }
        $seed = require $seedFile;

        $user = $this->db->table('usuarios')->where('email', $seed['email'])->get()->getRow();
        if ($user) {
            echo "- usuario principal ya existe\n";
            return;
        }

        $this->db->table('usuarios')->insert([
            'email'         => $seed['email'],
            'password_hash' => password_hash($seed['password'], PASSWORD_DEFAULT),
            'nombre'        => $seed['nombre'] ?? null,
            'rol'           => 'admin',
            'created_at'    => date('Y-m-d H:i:s'),
        ]);
        echo "- usuario principal creado: {$seed['email']}\n";
    }
}
