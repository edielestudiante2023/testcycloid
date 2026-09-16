<?php

namespace App\Commands;

use App\Libraries\KimiAnalisisVolverACasa;
use App\Models\DinamicaModel;
use App\Models\ParticipantModel;
use App\Models\SesionModel;
use App\Models\VolverACasaAnalisisModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Uso puntual: crea una sesion de "Volver a Casa" con 19 participantes
 * sinteticos (nombres falsos, dominio @ejemplo.test), arma los equipos con
 * ParticipantModel::iniciarEjercicio(), simula una respuesta valida de cada
 * quien en sus 5 momentos, y cierra el ejercicio calculando radiografia +
 * intentando el analisis de Kimi (si no hay config local, cae al resumen
 * por reglas, tal como debe pasar en un ejercicio real).
 *
 * Uso: php spark volveracasa:simular
 *
 * Borrar este archivo despues de usarlo.
 */
class SimularVolverACasa extends BaseCommand
{
    protected $group       = 'QA';
    protected $name        = 'volveracasa:simular';
    protected $description = 'Crea una sesion sintetica de Volver a Casa con 19 participantes y simula el ejercicio completo.';

    private const NOMBRES = [
        'Laura Gómez', 'Andrés Rincón', 'Camila Torres', 'Julián Vargas', 'Daniela Rojas',
        'Santiago Peña', 'Valentina Cruz', 'Felipe Moreno', 'Mariana Silva', 'Nicolás Herrera',
        'Isabella Castro', 'Sebastián Ortiz', 'Sofía Ramírez', 'Tomás Guzmán', 'Paula Salazar',
        'Miguel Ángel Duarte', 'Carolina Beltrán', 'David Zapata', 'Natalia Cárdenas',
    ];

    public function run(array $params)
    {
        $dinamica = (new DinamicaModel())->where('slug', 'volver-a-casa')->first();
        if (!$dinamica) {
            CLI::error('No existe la dinámica "volver-a-casa". Corre antes: php spark migrate');
            return;
        }

        $sesionModel = new SesionModel();
        $token = $sesionModel->crearToken();
        $sesionModel->insert([
            'dinamica_id'  => $dinamica['id'],
            'usuario_id'   => 1,
            'cliente'      => 'QA Volver a Casa (sintético)',
            'token'        => $token,
            'team_size'    => 4,
            'duracion_min' => 15,
        ]);
        $sesionId = (int) $sesionModel->getInsertID();

        CLI::write("Sesión creada — token: {$token}", 'yellow');

        $participantModel = new ParticipantModel();
        foreach (self::NOMBRES as $i => $nombre) {
            $slug = strtolower(str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['.', 'a', 'e', 'i', 'o', 'u', 'n'], $nombre));
            $participantModel->insert([
                'sesion_id'              => $sesionId,
                'nombre'                 => $nombre,
                'documento'              => (string) (900000000 + $i),
                'cargo'                  => 'Especialista de prueba',
                'email_corporativo'      => $slug . '@ejemplo.test',
                'email_personal'         => $slug . '.personal@ejemplo.test',
                'whatsapp'               => '300000' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'tiene_personal_a_cargo' => $i % 3 === 0 ? 1 : 0,
                'autorizo_datos'         => 1,
            ]);
        }

        CLI::write('19 participantes sintéticos insertados.', 'yellow');

        $asignados = $participantModel->iniciarEjercicio($sesionId, 4);
        $sesionModel->update($sesionId, ['iniciada_at' => date('Y-m-d H:i:s')]);

        $porEquipo = [];
        foreach ($asignados as $p) {
            $porEquipo[$p['team']][] = $p['nombre'] . ' (' . $p['role'] . ')';
        }
        ksort($porEquipo);
        foreach ($porEquipo as $team => $miembros) {
            CLI::write($team . ' (' . count($miembros) . '): ' . implode(', ', $miembros));
        }

        helper('volver_a_casa');
        $momentosDefinidos = volver_a_casa_momentos();
        $respuestaModel = new VolverACasaAnalisisModel();
        foreach ($asignados as $p) {
            $momentos = $momentosDefinidos[$p['role']]['momentos'] ?? [];
            foreach ($momentos as $numMomento => $data) {
                $valores = array_keys($data['opciones']);
                $elegido = $valores[array_rand($valores)];
                $respuestaModel->guardar((int) $p['id'], $numMomento, $elegido);
            }
        }

        CLI::write('Respuestas simuladas para los 5 momentos de cada participante.', 'yellow');

        $sesionModel->update($sesionId, ['estado' => 'cerrada', 'cerrada_at' => date('Y-m-d H:i:s')]);

        $radiografiaEnLista = $respuestaModel->radiografiaPorEquipo($sesionId);
        CLI::write('');
        CLI::write('=== Radiografía por equipo ===', 'green');
        foreach ($radiografiaEnLista as $r) {
            CLI::write($r['team'] . ':');
            foreach ($r['dimensiones'] as $dim => $nivel) {
                CLI::write('  - ' . $dim . ': ' . $nivel);
            }
            CLI::write('  - Percepción de haber sido escuchado: ' . $r['escuchados']);
        }

        $global = $respuestaModel->radiografiaGlobal($sesionId);
        if (!empty($global['dimensiones'])) {
            CLI::write('');
            CLI::write('=== Radiografía global (todos los equipos) ===', 'green');
            foreach ($global['dimensiones'] as $dim => $nivel) {
                CLI::write('  - ' . $dim . ': ' . $nivel);
            }
            CLI::write('  - Percepción de haber sido escuchado: ' . $global['escuchados']);
        }

        $resultados = $respuestaModel->resultadosPorEquipo($sesionId);
        CLI::write('');
        CLI::write('=== Resumen por reglas (respaldo sin IA) ===', 'green');
        foreach ($resultados as $r) {
            CLI::write($r['team'] . ': ' . $r['positivos'] . '/' . $r['totalConDato'] . ' — ' . $r['resumenTexto']);
        }

        $kimi = new KimiAnalisisVolverACasa();
        $porPersona = $respuestaModel->respuestasPorPersona($sesionId);
        $radiografiaPorEquipo = [];
        foreach ($radiografiaEnLista as $r) {
            $radiografiaPorEquipo[$r['team']] = $r;
        }
        $analisisIa = [];
        foreach ($porPersona as $equipo) {
            $analisisIa[$equipo['team']] = $kimi->analizarEquipo($equipo, $radiografiaPorEquipo[$equipo['team']] ?? []);
        }
        $analisisIa['__global__'] = $kimi->analizarGlobal($radiografiaEnLista, $global);
        $sesionModel->update($sesionId, ['analisis_ia' => json_encode($analisisIa, JSON_UNESCAPED_UNICODE)]);

        CLI::write('');
        CLI::write('=== Análisis de Kimi ===', 'green');
        foreach ($analisisIa as $team => $texto) {
            CLI::write($team . ': ' . ($texto === null ? '(null — sin config local de Kimi, se usa el resumen por reglas de arriba, tal como debe pasar en un ejercicio real)' : 'texto recibido'));
        }

        CLI::write('');
        CLI::write('Listo. Revisa en el navegador:', 'green');
        CLI::write('  QR / control:  ' . site_url('sesiones/qr/' . $token));
        CLI::write('  Resultados:    ' . site_url('sesiones/resultados/' . $token));
    }
}
