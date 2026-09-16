<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * "Liderazgo y Comunicación" quedó reemplazada en la práctica por "Código
 * Azul" (misma metodología de análisis conductual, historia nueva). No se
 * borra — la sesión de prueba que ya tiene queda intacta y accesible por su
 * enlace directo — solo deja de aparecer en el listado de dinámicas
 * disponibles (ver DinamicaModel::activas()).
 */
class DesactivarLiderazgoComunicacion extends Migration
{
    public function up(): void
    {
        $this->db->table('dinamicas')->where('slug', 'liderazgo-comunicacion')->update(['activa' => 0]);
    }

    public function down(): void
    {
        $this->db->table('dinamicas')->where('slug', 'liderazgo-comunicacion')->update(['activa' => 1]);
    }
}
