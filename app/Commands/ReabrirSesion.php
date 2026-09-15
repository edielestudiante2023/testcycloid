<?php

namespace App\Commands;

use App\Models\SesionModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Uso puntual: reabre una sesion cerrada (vuelve estado a "activa" y borra
 * cerrada_at) para poder probar de nuevo el flujo de cierre.
 * Uso: php spark sesion:reabrir {token}
 *
 * Borrar este archivo despues de usarlo.
 */
class ReabrirSesion extends BaseCommand
{
    protected $group       = 'QA';
    protected $name        = 'sesion:reabrir';
    protected $description = 'Reabre una sesion cerrada para volver a probar el cierre.';

    public function run(array $params)
    {
        $token = $params[0] ?? null;
        if (!$token) {
            CLI::error('Uso: php spark sesion:reabrir {token}');
            return;
        }

        $sesionModel = new SesionModel();
        $sesion = $sesionModel->where('token', $token)->first();
        if (!$sesion) {
            CLI::error("No existe ninguna sesion con token {$token}");
            return;
        }

        CLI::write("Sesion #{$sesion['id']} — cliente: {$sesion['cliente']} — estado actual: {$sesion['estado']}");
        $confirm = CLI::prompt('¿Confirmas reabrirla? Escribe "si" para continuar', ['si', 'no']);
        if ($confirm !== 'si') {
            CLI::write('Cancelado.');
            return;
        }

        $sesionModel->update($sesion['id'], [
            'estado'     => 'activa',
            'cerrada_at' => null,
        ]);

        CLI::write('Listo. La sesion vuelve a estar activa — el botón "Cerrar ejercicio" debería aparecer de nuevo.', 'green');
    }
}
