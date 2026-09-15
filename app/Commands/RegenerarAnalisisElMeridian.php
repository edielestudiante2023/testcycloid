<?php

namespace App\Commands;

use App\Libraries\KimiAnalisis;
use App\Libraries\SendGridMailer;
use App\Models\RespuestaMomentoModel;
use App\Models\SesionModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Uso puntual: recalcula el analisis de Kimi de una sesion de El Meridian ya
 * cerrada (por ejemplo, si el formato del texto cambio) y reenvia el correo.
 * Uso: php spark sesion:regenerar-analisis {token} {email_destino}
 *
 * Borrar este archivo despues de usarlo.
 */
class RegenerarAnalisisElMeridian extends BaseCommand
{
    protected $group       = 'QA';
    protected $name        = 'sesion:regenerar-analisis';
    protected $description = 'Recalcula el analisis de Kimi de una sesion de El Meridian y reenvia el correo.';

    public function run(array $params)
    {
        $token = $params[0] ?? null;
        $destino = $params[1] ?? null;
        if (!$token || !$destino) {
            CLI::error('Uso: php spark sesion:regenerar-analisis {token} {email_destino}');
            return;
        }

        $sesion = (new SesionModel())->findByToken($token);
        if (!$sesion || $sesion['dinamica_slug'] !== 'el-meridian') {
            CLI::error('Sesion no encontrada o no es de El Meridian.');
            return;
        }

        CLI::write('Recalculando con Kimi (puede tardar ~20s por equipo)...');
        $porEquipo = (new RespuestaMomentoModel())->respuestasPorPersona((int) $sesion['id']);
        $kimi = new KimiAnalisis();
        $analisis = [];
        foreach ($porEquipo as $equipo) {
            $analisis[$equipo['team']] = $kimi->analizarEquipo($equipo);
        }

        $sesionModel = new SesionModel();
        $sesionModel->update((int) $sesion['id'], ['analisis_ia' => json_encode($analisis, JSON_UNESCAPED_UNICODE)]);
        CLI::write('Guardado.');

        $equipos = (new RespuestaMomentoModel())->resultadosPorEquipo((int) $sesion['id']);
        foreach ($equipos as &$eq) {
            $eq['analisisIa'] = $analisis[$eq['team']] ?? null;
        }
        unset($eq);

        $html = view('emails/el_meridian_resumen', [
            'sesion' => $sesion,
            'equipos' => $equipos,
            'resultadosUrl' => site_url('sesiones/resultados/' . $sesion['token']),
        ]);

        $resultado = (new SendGridMailer())->send(
            [$destino],
            'El Meridián — resumen de "' . $sesion['cliente'] . '" (actualizado)',
            $html
        );

        CLI::write($resultado['ok'] ? 'Correo reenviado a ' . $destino : 'Fallo el envio: ' . $resultado['body']);
    }
}
