<?php

namespace App\Commands;

use App\Models\ParticipantModel;
use App\Models\SesionModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Comando puntual: revierte una sesion al estado "antes de enviar taller"
 * (sin borrar los registros de participantes, solo el team/role/token que
 * les asigna iniciarEjercicio()). Uso:
 *   php spark sesion:reset-envio {token}
 *
 * Borrar este archivo despues de usarlo una vez.
 */
class ResetSesionEnvio extends BaseCommand
{
    protected $group       = 'QA';
    protected $name        = 'sesion:reset-envio';
    protected $description = 'Revierte una sesion al estado "antes de enviar taller" (uso puntual).';

    public function run(array $params)
    {
        $token = $params[0] ?? null;
        if (!$token) {
            CLI::error('Uso: php spark sesion:reset-envio {token}');
            return;
        }

        $sesionModel = new SesionModel();
        $sesion = $sesionModel->where('token', $token)->first();
        if (!$sesion) {
            CLI::error("No existe ninguna sesion con token {$token}");
            return;
        }

        $participantModel = new ParticipantModel();
        $participantes = $participantModel->where('sesion_id', $sesion['id'])->findAll();

        CLI::write("Sesion #{$sesion['id']} — cliente: {$sesion['cliente']} — participantes: " . count($participantes));
        CLI::write("Estado actual: iniciada_at={$sesion['iniciada_at']} cerrada_at={$sesion['cerrada_at']} estado={$sesion['estado']}");

        $confirm = CLI::prompt('¿Confirmas revertir esta sesion al estado antes de enviar? Escribe "si" para continuar', ['si', 'no']);
        if ($confirm !== 'si') {
            CLI::write('Cancelado.');
            return;
        }

        $sesionModel->update($sesion['id'], [
            'iniciada_at' => null,
            'cerrada_at'  => null,
            'estado'      => 'activa',
        ]);

        foreach ($participantes as $p) {
            $participantModel->update($p['id'], [
                'team'  => null,
                'role'  => null,
                'token' => null,
            ]);
        }

        CLI::write('Listo. La sesion volvio al estado "antes de enviar" — los datos de registro de los participantes quedaron intactos.', 'green');
    }
}
