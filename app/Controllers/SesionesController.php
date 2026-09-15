<?php

namespace App\Controllers;

use App\Libraries\KimiAnalisis;
use App\Libraries\SendGridMailer;
use App\Models\DinamicaModel;
use App\Models\ParticipantModel;
use App\Models\RespuestaMomentoModel;
use App\Models\SesionModel;

class SesionesController extends BaseController
{
    public function listar(string $slug)
    {
        $dinamica = (new DinamicaModel())->findBySlug($slug);
        if (!$dinamica) {
            return redirect()->to('/');
        }

        return view('sesiones/listar', [
            'dinamica' => $dinamica,
            'sesiones' => (new SesionModel())->porDinamica((int) $dinamica['id']),
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
        }

        return view('sesiones/qr', [
            'sesion'          => $sesion,
            'registroUrl'     => $registroUrl,
            'count'           => (new ParticipantModel())->contarRegistrados((int) $sesion['id']),
            'progresoEquipos' => $progresoEquipos,
            'participants'    => (new ParticipantModel())->porSesion((int) $sesion['id']),
            'nombresRol'      => $this->nombresRolPorDinamica($sesion['dinamica_slug']),
        ]);
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
            foreach ($asignados as $p) {
                $rolUrl = site_url($sesion['dinamica_slug'] . '/rol/' . $p['token']);
                $introUrl = $sesion['dinamica_slug'] === 'el-meridian'
                    ? site_url('el-meridian/intro/' . $p['token'])
                    : null;
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
        if (!$sesion || $sesion['dinamica_slug'] !== 'el-meridian') {
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
