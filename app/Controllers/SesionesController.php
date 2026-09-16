<?php

namespace App\Controllers;

use App\Libraries\KimiAnalisis;
use App\Libraries\KimiAnalisisCodigoAzul;
use App\Libraries\KimiAnalisisVolverACasa;
use App\Libraries\SendGridMailer;
use App\Models\CodigoAzulAnalisisModel;
use App\Models\DinamicaModel;
use App\Models\ParticipantModel;
use App\Models\RespuestaMomentoModel;
use App\Models\SesionModel;
use App\Models\VolverACasaAnalisisModel;

class SesionesController extends BaseController
{
    public function listar(string $slug)
    {
        $dinamica = (new DinamicaModel())->findBySlug($slug);
        if (!$dinamica) {
            return redirect()->to('/');
        }

        $clientesConsolidables = [];
        $modeloConsolidable = $this->analisisModelConsolidable($slug);
        if ($modeloConsolidable) {
            $clientesConsolidables = $modeloConsolidable->clientesConsolidables((int) $dinamica['id']);
        }

        return view('sesiones/listar', [
            'dinamica' => $dinamica,
            'sesiones' => (new SesionModel())->porDinamica((int) $dinamica['id']),
            'clientesConsolidables' => $clientesConsolidables,
        ]);
    }

    public function nueva(string $slug)
    {
        $dinamica = (new DinamicaModel())->findBySlug($slug);
        if (!$dinamica) {
            return redirect()->to('/');
        }

        return view('sesiones/nueva', ['dinamica' => $dinamica, 'error' => null]);
    }

    public function crear()
    {
        $slug = (string) $this->request->getPost('dinamica');
        $cliente = trim((string) $this->request->getPost('cliente'));
        $teamSize = (int) $this->request->getPost('team_size');
        $duracion = (int) $this->request->getPost('duracion_min');

        $dinamica = (new DinamicaModel())->findBySlug($slug);
        if (!$dinamica) {
            return redirect()->to('/');
        }

        if ($cliente === '') {
            return view('sesiones/nueva', ['dinamica' => $dinamica, 'error' => 'El cliente es obligatorio.']);
        }

        $teamSize = $teamSize === 4 ? 4 : 5;
        $duracion = $duracion >= 1 && $duracion <= 120 ? $duracion : 12;

        $sesionModel = new SesionModel();
        $token = $sesionModel->crearToken();
        $sesionModel->insert([
            'dinamica_id'  => $dinamica['id'],
            'usuario_id'   => session('usuario_id'),
            'cliente'      => $cliente,
            'token'        => $token,
            'team_size'    => $teamSize,
            'duracion_min' => $duracion,
        ]);

        return redirect()->to('/sesiones/qr/' . $token);
    }

    public function qr(string $token)
    {
        $sesion = (new SesionModel())->findByToken($token);
        if (!$sesion) {
            return redirect()->to('/');
        }

        $registroUrl = site_url($sesion['dinamica_slug'] . '/registro/' . $sesion['token']);

        $progresoEquipos = [];
        if ($sesion['dinamica_slug'] === 'el-meridian' && !empty($sesion['iniciada_at'])) {
            $progresoEquipos = (new RespuestaMomentoModel())->progresoPorEquipo((int) $sesion['id']);
        } elseif ($sesion['dinamica_slug'] === 'volver-a-casa' && !empty($sesion['iniciada_at'])) {
            $progresoEquipos = (new VolverACasaAnalisisModel())->progresoPorEquipo((int) $sesion['id']);
        } elseif ($sesion['dinamica_slug'] === 'codigo-azul' && !empty($sesion['iniciada_at'])) {
            $progresoEquipos = (new CodigoAzulAnalisisModel())->progresoPorEquipo((int) $sesion['id']);
        }

        return view('sesiones/qr', [
            'sesion'            => $sesion,
            'registroUrl'       => $registroUrl,
            'count'             => (new ParticipantModel())->contarRegistrados((int) $sesion['id']),
            'progresoEquipos'   => $progresoEquipos,
            'participants'      => (new ParticipantModel())->porSesion((int) $sesion['id']),
            'nombresRol'        => $this->nombresRolPorDinamica($sesion['dinamica_slug']),
            'sesionesRecientes' => empty($sesion['iniciada_at'])
                ? (new SesionModel())->recientesConParticipantes((int) $sesion['id'])
                : [],
            'reciclados'        => (int) ($this->request->getGet('reciclados') ?? 0),
        ]);
    }

    /**
     * Uso exclusivo del facilitador: copia los participantes ya registrados
     * en OTRA sesión (de cualquier dinámica) hacia esta sesión, para no
     * hacer que el mismo grupo se registre de cero en cada ejercicio.
     * Equipo, rol y token siempre arrancan limpios en esta sesión.
     */
    public function reciclarParticipantes(string $token)
    {
        $sesion = (new SesionModel())->findByToken($token);
        if (!$sesion || !empty($sesion['iniciada_at'])) {
            return redirect()->to('/sesiones/qr/' . $token);
        }

        $sesionOrigenToken = (string) $this->request->getPost('sesion_origen');
        $sesionOrigen = $sesionOrigenToken !== '' ? (new SesionModel())->findByToken($sesionOrigenToken) : null;

        $copiados = 0;
        if ($sesionOrigen) {
            $copiados = (new ParticipantModel())->copiarDesdeOtraSesion((int) $sesionOrigen['id'], (int) $sesion['id']);
        }

        return redirect()->to('/sesiones/qr/' . $token . '?reciclados=' . $copiados);
    }

    /**
     * @return array<string, string> letra de rol => nombre legible, según la dinámica
     */
    private function nombresRolPorDinamica(string $dinamicaSlug): array
    {
        if ($dinamicaSlug === 'el-meridian') {
            $nombres = [];
            foreach (el_meridian_momentos() as $rol => $info) {
                $nombres[$rol] = $info['nombre'];
            }
            return $nombres;
        }

        if ($dinamicaSlug === 'liderazgo-comunicacion') {
            $nombres = [];
            foreach (liderazgo_comunicacion_role_cards() as $rol => $info) {
                $nombres[$rol] = preg_replace('/^TARJETA\s+\w+\s*—\s*/u', '', $info['title']);
            }
            return $nombres;
        }

        if ($dinamicaSlug === 'volver-a-casa') {
            $nombres = [];
            foreach (volver_a_casa_momentos() as $rol => $info) {
                $nombres[$rol] = $info['nombre'];
            }
            return $nombres;
        }

        if ($dinamicaSlug === 'codigo-azul') {
            $nombres = [];
            foreach (codigo_azul_momentos() as $rol => $info) {
                $nombres[$rol] = $info['nombre'];
            }
            return $nombres;
        }

        return [];
    }

    public function contador(string $token)
    {
        $sesion = (new SesionModel())->findByToken($token);
        if (!$sesion) {
            return $this->response->setJSON(['count' => 0]);
        }

        return $this->response->setJSON([
            'count' => (new ParticipantModel())->contarRegistrados((int) $sesion['id']),
        ]);
    }

    public function enviar(string $token)
    {
        $sesion = (new SesionModel())->findByToken($token);
        if (!$sesion) {
            return redirect()->to('/');
        }

        $participantModel = new ParticipantModel();
        $asignados = $participantModel->iniciarEjercicio((int) $sesion['id'], (int) $sesion['team_size']);

        if (!empty($asignados)) {
            (new SesionModel())->update((int) $sesion['id'], ['iniciada_at' => date('Y-m-d H:i:s')]);

            $mailer = new SendGridMailer();
            $nombresRol = $this->nombresRolPorDinamica($sesion['dinamica_slug']);

            foreach ($asignados as $p) {
                $rolUrl = site_url($sesion['dinamica_slug'] . '/rol/' . $p['token']);
                $introUrl = match ($sesion['dinamica_slug']) {
                    'el-meridian' => site_url('el-meridian/intro/' . $p['token']),
                    'volver-a-casa' => site_url('volver-a-casa/intro/' . $p['token']),
                    'codigo-azul' => site_url('codigo-azul/intro/' . $p['token']),
                    default => null,
                };
                $html = view('emails/rol', [
                    'nombre'         => $p['nombre'],
                    'rolUrl'         => $rolUrl,
                    'introUrl'       => $introUrl,
                    'dinamicaNombre' => $sesion['dinamica_nombre'],
                ]);
                $mailer->send(
                    [$p['email_corporativo'], $p['email_personal']],
                    $sesion['dinamica_nombre'] . ' — tu rol para el ejercicio de hoy',
                    $html
                );
            }

            // Correo de equipo: uno por equipo, con TODOS los correos corporativos
            // del equipo en el "para" (para que puedan responder a todos y
            // organizarse por correo de inmediato). No lleva ningún enlace de rol
            // — eso sigue siendo confidencial y solo va en el correo individual.
            $porEquipo = [];
            foreach ($asignados as $p) {
                $porEquipo[$p['team']][] = $p;
            }

            foreach ($porEquipo as $team => $miembros) {
                $roster = array_map(static fn ($m) => [
                    'nombre' => $m['nombre'],
                    'rol'    => $nombresRol[$m['role']] ?? $m['role'],
                ], $miembros);

                $htmlEquipo = view('emails/equipo', [
                    'dinamicaNombre' => $sesion['dinamica_nombre'],
                    'cliente'        => $sesion['cliente'],
                    'team'           => $team,
                    'miembros'       => $roster,
                ]);

                $correosEquipo = array_map(static fn ($m) => $m['email_corporativo'], $miembros);
                $mailer->send(
                    $correosEquipo,
                    $sesion['dinamica_nombre'] . ' — conoce a tu equipo (' . $team . ')',
                    $htmlEquipo
                );
            }
        }

        return redirect()->to('/sesiones/qr/' . $token . '?enviado=1');
    }

    /**
     * Uso exclusivo del facilitador: si un equipo se quedó atascado porque
     * alguien no respondió un momento (llegó tarde, se le dañó el celular),
     * esto lo destraba sin esperar a esa persona.
     */
    public function forzarAvance(string $token)
    {
        $sesion = (new SesionModel())->findByToken($token);
        if (!$sesion || !in_array($sesion['dinamica_slug'], ['el-meridian', 'volver-a-casa', 'codigo-azul'], true)) {
            return redirect()->to('/sesiones/qr/' . $token);
        }

        $team = (string) $this->request->getPost('team');
        $momento = (int) $this->request->getPost('momento');
        if ($team !== '' && $momento > 0) {
            (new RespuestaMomentoModel())->forzarAvance((int) $sesion['id'], $team, $momento);
        }

        return redirect()->to('/sesiones/qr/' . $token);
    }

    public function cerrar(string $token)
    {
        $sesion = (new SesionModel())->findByToken($token);
        if ($sesion) {
            $sesionModel = new SesionModel();
            $sesionModel->update((int) $sesion['id'], [
                'estado' => 'cerrada',
                'cerrada_at' => date('Y-m-d H:i:s'),
            ]);

            if ($sesion['dinamica_slug'] === 'el-meridian') {
                $analisisPorEquipo = $this->generarAnalisisElMeridian((int) $sesion['id']);
                $sesionModel->update((int) $sesion['id'], ['analisis_ia' => json_encode($analisisPorEquipo, JSON_UNESCAPED_UNICODE)]);
                $this->enviarResumenElMeridian($sesion, $analisisPorEquipo);
            } elseif ($sesion['dinamica_slug'] === 'volver-a-casa') {
                $analisisPorEquipo = $this->generarAnalisisVolverACasa((int) $sesion['id']);
                $sesionModel->update((int) $sesion['id'], ['analisis_ia' => json_encode($analisisPorEquipo, JSON_UNESCAPED_UNICODE)]);
                $this->enviarResumenVolverACasa($sesion, $analisisPorEquipo);
            } elseif ($sesion['dinamica_slug'] === 'codigo-azul') {
                $analisisPorEquipo = $this->generarAnalisisCodigoAzul((int) $sesion['id']);
                $sesionModel->update((int) $sesion['id'], ['analisis_ia' => json_encode($analisisPorEquipo, JSON_UNESCAPED_UNICODE)]);
                $this->enviarResumenCodigoAzul($sesion, $analisisPorEquipo);
            }
        }

        return redirect()->to('/sesiones/resultados/' . $token);
    }

    /**
     * @return array<string, string|null> team => texto del analisis (o null si Kimi no respondio);
     *         más la clave especial "__global__" con el análisis consolidado de todos los equipos
     *         (solo si hay 2 o más equipos).
     */
    private function generarAnalisisElMeridian(int $sesionId): array
    {
        $respuestaModel = new RespuestaMomentoModel();
        $porEquipo = $respuestaModel->respuestasPorPersona($sesionId);
        $radiografiaEnLista = $respuestaModel->radiografiaPorEquipo($sesionId);
        $radiografiaPorEquipo = [];
        foreach ($radiografiaEnLista as $r) {
            $radiografiaPorEquipo[$r['team']] = $r;
        }

        $kimi = new KimiAnalisis();
        $analisis = [];
        foreach ($porEquipo as $equipo) {
            $analisis[$equipo['team']] = $kimi->analizarEquipo($equipo, $radiografiaPorEquipo[$equipo['team']] ?? []);
        }

        $analisis['__global__'] = $kimi->analizarGlobal($radiografiaEnLista, $respuestaModel->radiografiaGlobal($sesionId));

        return $analisis;
    }

    private function enviarResumenElMeridian(array $sesion, array $analisisPorEquipo): void
    {
        $destino = session('usuario_email');
        if (!$destino) {
            return;
        }

        $respuestaModel = new RespuestaMomentoModel();
        $equipos = $respuestaModel->resultadosPorEquipo((int) $sesion['id']);
        if (empty($equipos)) {
            return;
        }

        $radiografiaPorEquipo = [];
        foreach ($respuestaModel->radiografiaPorEquipo((int) $sesion['id']) as $r) {
            $radiografiaPorEquipo[$r['team']] = $r;
        }

        foreach ($equipos as &$eq) {
            $eq['analisisIa'] = $analisisPorEquipo[$eq['team']] ?? null;
            $eq['radiografia'] = $radiografiaPorEquipo[$eq['team']] ?? null;
        }
        unset($eq);

        $html = view('emails/el_meridian_resumen', [
            'sesion' => $sesion,
            'equipos' => $equipos,
            'analisisGlobal' => $analisisPorEquipo['__global__'] ?? null,
            'radiografiaGlobal' => $respuestaModel->radiografiaGlobal((int) $sesion['id']),
            'resultadosUrl' => site_url('sesiones/resultados/' . $sesion['token']),
        ]);

        (new SendGridMailer())->send(
            [$destino],
            'El Meridián — resumen de "' . $sesion['cliente'] . '"',
            $html
        );
    }

    /**
     * @return array<string, string|null> team => texto del analisis (o null si Kimi no respondio);
     *         más la clave especial "__global__" con el análisis consolidado de todos los equipos
     *         (solo si hay 2 o más equipos).
     */
    private function generarAnalisisVolverACasa(int $sesionId): array
    {
        $respuestaModel = new VolverACasaAnalisisModel();
        $porEquipo = $respuestaModel->respuestasPorPersona($sesionId);
        $radiografiaEnLista = $respuestaModel->radiografiaPorEquipo($sesionId);
        $radiografiaPorEquipo = [];
        foreach ($radiografiaEnLista as $r) {
            $radiografiaPorEquipo[$r['team']] = $r;
        }

        $kimi = new KimiAnalisisVolverACasa();
        $analisis = [];
        foreach ($porEquipo as $equipo) {
            $analisis[$equipo['team']] = $kimi->analizarEquipo($equipo, $radiografiaPorEquipo[$equipo['team']] ?? []);
        }

        $analisis['__global__'] = $kimi->analizarGlobal($radiografiaEnLista, $respuestaModel->radiografiaGlobal($sesionId));

        return $analisis;
    }

    private function enviarResumenVolverACasa(array $sesion, array $analisisPorEquipo): void
    {
        $destino = session('usuario_email');
        if (!$destino) {
            return;
        }

        $respuestaModel = new VolverACasaAnalisisModel();
        $equipos = $respuestaModel->resultadosPorEquipo((int) $sesion['id']);
        if (empty($equipos)) {
            return;
        }

        $radiografiaPorEquipo = [];
        foreach ($respuestaModel->radiografiaPorEquipo((int) $sesion['id']) as $r) {
            $radiografiaPorEquipo[$r['team']] = $r;
        }

        foreach ($equipos as &$eq) {
            $eq['analisisIa'] = $analisisPorEquipo[$eq['team']] ?? null;
            $eq['radiografia'] = $radiografiaPorEquipo[$eq['team']] ?? null;
        }
        unset($eq);

        $html = view('emails/volver_a_casa_resumen', [
            'sesion' => $sesion,
            'equipos' => $equipos,
            'analisisGlobal' => $analisisPorEquipo['__global__'] ?? null,
            'radiografiaGlobal' => $respuestaModel->radiografiaGlobal((int) $sesion['id']),
            'resultadosUrl' => site_url('sesiones/resultados/' . $sesion['token']),
        ]);

        (new SendGridMailer())->send(
            [$destino],
            'Volver a Casa — resumen de "' . $sesion['cliente'] . '"',
            $html
        );
    }

    /**
     * @return array<string, string|null> team => texto del analisis (o null si Kimi no respondio);
     *         más la clave especial "__global__" con el análisis consolidado de todos los equipos
     *         (solo si hay 2 o más equipos).
     */
    private function generarAnalisisCodigoAzul(int $sesionId): array
    {
        $respuestaModel = new CodigoAzulAnalisisModel();
        $porEquipo = $respuestaModel->respuestasPorPersona($sesionId);
        $radiografiaEnLista = $respuestaModel->radiografiaPorEquipo($sesionId);
        $radiografiaPorEquipo = [];
        foreach ($radiografiaEnLista as $r) {
            $radiografiaPorEquipo[$r['team']] = $r;
        }

        $kimi = new KimiAnalisisCodigoAzul();
        $analisis = [];
        foreach ($porEquipo as $equipo) {
            $analisis[$equipo['team']] = $kimi->analizarEquipo($equipo, $radiografiaPorEquipo[$equipo['team']] ?? []);
        }

        $analisis['__global__'] = $kimi->analizarGlobal($radiografiaEnLista, $respuestaModel->radiografiaGlobal($sesionId));

        return $analisis;
    }

    private function enviarResumenCodigoAzul(array $sesion, array $analisisPorEquipo): void
    {
        $destino = session('usuario_email');
        if (!$destino) {
            return;
        }

        $respuestaModel = new CodigoAzulAnalisisModel();
        $equipos = $respuestaModel->resultadosPorEquipo((int) $sesion['id']);
        if (empty($equipos)) {
            return;
        }

        $radiografiaPorEquipo = [];
        foreach ($respuestaModel->radiografiaPorEquipo((int) $sesion['id']) as $r) {
            $radiografiaPorEquipo[$r['team']] = $r;
        }

        foreach ($equipos as &$eq) {
            $eq['analisisIa'] = $analisisPorEquipo[$eq['team']] ?? null;
            $eq['radiografia'] = $radiografiaPorEquipo[$eq['team']] ?? null;
        }
        unset($eq);

        $html = view('emails/codigo_azul_resumen', [
            'sesion' => $sesion,
            'equipos' => $equipos,
            'analisisGlobal' => $analisisPorEquipo['__global__'] ?? null,
            'radiografiaGlobal' => $respuestaModel->radiografiaGlobal((int) $sesion['id']),
            'resultadosUrl' => site_url('sesiones/resultados/' . $sesion['token']),
        ]);

        (new SendGridMailer())->send(
            [$destino],
            'Código Azul — resumen de "' . $sesion['cliente'] . '"',
            $html
        );
    }

    public function resultados(string $token)
    {
        $sesion = (new SesionModel())->findByToken($token);
        if (!$sesion) {
            return redirect()->to('/');
        }

        if ($sesion['dinamica_slug'] === 'el-meridian') {
            $respuestaModel = new RespuestaMomentoModel();
            $equipos = $respuestaModel->resultadosPorEquipo((int) $sesion['id']);
            $analisisPorEquipo = json_decode((string) ($sesion['analisis_ia'] ?? ''), true) ?? [];

            $radiografiaPorEquipo = [];
            foreach ($respuestaModel->radiografiaPorEquipo((int) $sesion['id']) as $r) {
                $radiografiaPorEquipo[$r['team']] = $r;
            }

            foreach ($equipos as &$eq) {
                $eq['analisisIa'] = $analisisPorEquipo[$eq['team']] ?? null;
                $eq['radiografia'] = $radiografiaPorEquipo[$eq['team']] ?? null;
            }
            unset($eq);

            return view('el-meridian/resultados', [
                'sesion' => $sesion,
                'equipos' => $equipos,
                'analisisGlobal' => $analisisPorEquipo['__global__'] ?? null,
                'radiografiaGlobal' => $respuestaModel->radiografiaGlobal((int) $sesion['id']),
            ]);
        }

        if ($sesion['dinamica_slug'] === 'volver-a-casa') {
            $respuestaModel = new VolverACasaAnalisisModel();
            $equipos = $respuestaModel->resultadosPorEquipo((int) $sesion['id']);
            $analisisPorEquipo = json_decode((string) ($sesion['analisis_ia'] ?? ''), true) ?? [];

            $radiografiaPorEquipo = [];
            foreach ($respuestaModel->radiografiaPorEquipo((int) $sesion['id']) as $r) {
                $radiografiaPorEquipo[$r['team']] = $r;
            }

            foreach ($equipos as &$eq) {
                $eq['analisisIa'] = $analisisPorEquipo[$eq['team']] ?? null;
                $eq['radiografia'] = $radiografiaPorEquipo[$eq['team']] ?? null;
            }
            unset($eq);

            return view('volver-a-casa/resultados', [
                'sesion' => $sesion,
                'equipos' => $equipos,
                'analisisGlobal' => $analisisPorEquipo['__global__'] ?? null,
                'radiografiaGlobal' => $respuestaModel->radiografiaGlobal((int) $sesion['id']),
            ]);
        }

        if ($sesion['dinamica_slug'] === 'codigo-azul') {
            $respuestaModel = new CodigoAzulAnalisisModel();
            $equipos = $respuestaModel->resultadosPorEquipo((int) $sesion['id']);
            $analisisPorEquipo = json_decode((string) ($sesion['analisis_ia'] ?? ''), true) ?? [];

            $radiografiaPorEquipo = [];
            foreach ($respuestaModel->radiografiaPorEquipo((int) $sesion['id']) as $r) {
                $radiografiaPorEquipo[$r['team']] = $r;
            }

            foreach ($equipos as &$eq) {
                $eq['analisisIa'] = $analisisPorEquipo[$eq['team']] ?? null;
                $eq['radiografia'] = $radiografiaPorEquipo[$eq['team']] ?? null;
            }
            unset($eq);

            return view('codigo-azul/resultados', [
                'sesion' => $sesion,
                'equipos' => $equipos,
                'analisisGlobal' => $analisisPorEquipo['__global__'] ?? null,
                'radiografiaGlobal' => $respuestaModel->radiografiaGlobal((int) $sesion['id']),
            ]);
        }

        $participants = (new ParticipantModel())->porSesion((int) $sesion['id']);
        $byTeam = [];
        foreach ($participants as $p) {
            $byTeam[$p['team'] ?? 'Sin equipo asignado (pendiente)'][] = $p;
        }

        return view('sesiones/resultados', [
            'sesion'       => $sesion,
            'participants' => $participants,
            'byTeam'       => $byTeam,
            'answers'      => liderazgo_comunicacion_role_answers(),
            'rolBaseUrl'   => site_url($sesion['dinamica_slug'] . '/rol/'),
        ]);
    }

    /**
     * Los dinámicas que soportan el consolidado entre sesiones cerradas del
     * mismo cliente (a través del tiempo). Cada una mapea a su propio
     * modelo de análisis, librería de Kimi, vista y nombre legible — los
     * tres siguen exactamente la misma forma (ver VolverACasaAnalisisModel/
     * CodigoAzulAnalisisModel).
     *
     * @return array{modelo: object, kimi: object, vista: string, vistaEmail: string, nombre: string}|null
     */
    private function configConsolidable(string $dinamicaSlug): ?array
    {
        return match ($dinamicaSlug) {
            'el-meridian' => [
                'modelo'     => new RespuestaMomentoModel(),
                'kimi'       => new KimiAnalisis(),
                'vista'      => 'el-meridian/consolidado',
                'vistaEmail' => 'emails/el_meridian_consolidado',
                'nombre'     => 'El Meridián',
            ],
            'volver-a-casa' => [
                'modelo'     => new VolverACasaAnalisisModel(),
                'kimi'       => new KimiAnalisisVolverACasa(),
                'vista'      => 'volver-a-casa/consolidado',
                'vistaEmail' => 'emails/volver_a_casa_consolidado',
                'nombre'     => 'Volver a Casa',
            ],
            'codigo-azul' => [
                'modelo'     => new CodigoAzulAnalisisModel(),
                'kimi'       => new KimiAnalisisCodigoAzul(),
                'vista'      => 'codigo-azul/consolidado',
                'vistaEmail' => 'emails/codigo_azul_consolidado',
                'nombre'     => 'Código Azul',
            ],
            default => null,
        };
    }

    /**
     * Atajo usado por listar() para saber si vale la pena calcular los
     * clientes consolidables de esta dinámica.
     */
    private function analisisModelConsolidable(string $dinamicaSlug): ?object
    {
        return $this->configConsolidable($dinamicaSlug)['modelo'] ?? null;
    }

    /**
     * Consolidado entre sesiones CERRADAS del mismo cliente, a través del
     * tiempo (no confundir con el resumen global de resultados(), que
     * consolida equipos dentro de UNA sola sesión). Cualquier token de una
     * sesión de ese cliente sirve como punto de entrada — se listan todas
     * las que compartan el mismo texto en "cliente".
     */
    public function consolidado(string $token)
    {
        $sesion = (new SesionModel())->findByToken($token);
        $config = $sesion ? $this->configConsolidable($sesion['dinamica_slug']) : null;
        if (!$config) {
            return redirect()->to('/');
        }

        [$sesionesCliente, $porSesion, $radiografiaConsolidada] = $this->datosConsolidadoCliente($sesion, $config);

        return view($config['vista'], [
            'sesion'                 => $sesion,
            'sesionesCliente'        => $sesionesCliente,
            'porSesion'              => $porSesion,
            'radiografiaConsolidada' => $radiografiaConsolidada,
            'analisisConsolidado'    => null,
            'correoEnviado'          => false,
        ]);
    }

    /**
     * Uso exclusivo del facilitador: genera (con Kimi) y envía por correo el
     * análisis consolidado del cliente. Es una acción explícita del
     * facilitador, no algo que se recalcule solo con entrar a mirar.
     */
    public function enviarConsolidadoEmail(string $token)
    {
        $sesion = (new SesionModel())->findByToken($token);
        $config = $sesion ? $this->configConsolidable($sesion['dinamica_slug']) : null;
        if (!$config) {
            return redirect()->to('/');
        }

        [$sesionesCliente, $porSesion, $radiografiaConsolidada] = $this->datosConsolidadoCliente($sesion, $config);

        $analisisConsolidado = null;
        if (count($sesionesCliente) >= 2) {
            $analisisConsolidado = $config['kimi']->analizarConsolidadoCliente($porSesion, $radiografiaConsolidada);
        }

        $destino = session('usuario_email');
        $correoEnviado = false;
        if ($destino && count($sesionesCliente) >= 2) {
            $html = view($config['vistaEmail'], [
                'sesion'                 => $sesion,
                'sesionesCliente'        => $sesionesCliente,
                'radiografiaConsolidada' => $radiografiaConsolidada,
                'analisisConsolidado'    => $analisisConsolidado,
                'consolidadoUrl'         => site_url('sesiones/consolidado/' . $sesion['token']),
            ]);

            $resultado = (new SendGridMailer())->send(
                [$destino],
                $config['nombre'] . ' — consolidado de "' . $sesion['cliente'] . '" (' . count($sesionesCliente) . ' sesiones)',
                $html
            );
            $correoEnviado = $resultado['ok'];
        }

        return view($config['vista'], [
            'sesion'                 => $sesion,
            'sesionesCliente'        => $sesionesCliente,
            'porSesion'              => $porSesion,
            'radiografiaConsolidada' => $radiografiaConsolidada,
            'analisisConsolidado'    => $analisisConsolidado,
            'correoEnviado'          => $correoEnviado,
        ]);
    }

    /**
     * @return array{0: array<int, array<string, mixed>>, 1: array<int, array{fecha: string, dimensiones: array<string, string>, escuchados: string}>, 2: array{dimensiones: array<string, string>, escuchados: string}}
     */
    private function datosConsolidadoCliente(array $sesion, array $config): array
    {
        $respuestaModel = $config['modelo'];
        $sesionesCliente = $respuestaModel->sesionesCerradasPorCliente((int) $sesion['dinamica_id'], $sesion['cliente']);

        $porSesion = [];
        foreach ($sesionesCliente as $s) {
            $radio = $respuestaModel->radiografiaGlobal((int) $s['id']);
            $porSesion[] = [
                'token'       => $s['token'],
                'fecha'       => substr((string) $s['cerrada_at'], 0, 10),
                'dimensiones' => $radio['dimensiones'] ?? [],
                'escuchados'  => $radio['escuchados'] ?? '—',
            ];
        }

        $radiografiaConsolidada = count($sesionesCliente) >= 2
            ? $respuestaModel->radiografiaConsolidadaPorCliente(array_column($sesionesCliente, 'id'))
            : [];

        return [$sesionesCliente, $porSesion, $radiografiaConsolidada];
    }

    public function liderazgo(string $token)
    {
        $sesion = (new SesionModel())->findByToken($token);
        if (!$sesion) {
            return redirect()->to('/');
        }

        $participantId = (int) $this->request->getPost('participant_id');
        $value = $this->request->getPost('mostro');
        $bool = $value === '1' ? true : ($value === '0' ? false : null);

        $model = new ParticipantModel();
        $participant = $model->find($participantId);
        if ($participant && (int) $participant['sesion_id'] === (int) $sesion['id']) {
            $model->update($participantId, ['mostro_liderazgo' => $bool]);
        }

        return redirect()->to('/sesiones/resultados/' . $token);
    }
}
