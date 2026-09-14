<?php

namespace App\Controllers;

use App\Models\ParticipantModel;
use App\Models\RespuestaMomentoModel;
use App\Models\SesionModel;

class ElMeridianController extends BaseController
{
    public function registro(string $sesionToken)
    {
        $sesion = (new SesionModel())->findByToken($sesionToken);
        if (!$sesion || $sesion['dinamica_slug'] !== 'el-meridian') {
            return $this->response->setStatusCode(404)->setBody(
                view('el-meridian/enlace_invalido')
            );
        }

        return view('el-meridian/registro', [
            'sesion' => $sesion,
            'sesionToken' => $sesionToken,
            'error'  => $this->request->getGet('error'),
        ]);
    }

    public function asignar()
    {
        $sesionToken = (string) $this->request->getPost('s');
        $sesion = (new SesionModel())->findByToken($sesionToken);

        if (!$sesion || $sesion['dinamica_slug'] !== 'el-meridian') {
            return $this->response->setStatusCode(404)->setBody('Sesión no válida.');
        }

        $redir = '/el-meridian/registro/' . $sesionToken;

        $nombre = trim((string) $this->request->getPost('nombre'));
        $documento = trim((string) $this->request->getPost('documento'));
        $cargo = trim((string) $this->request->getPost('cargo'));
        $emailCorp = trim((string) $this->request->getPost('email_corporativo'));
        $emailPersonal = trim((string) $this->request->getPost('email_personal'));
        $whatsapp = trim((string) $this->request->getPost('whatsapp'));
        $tienePersonal = (bool) $this->request->getPost('tiene_personal_a_cargo');
        $autorizo = (bool) $this->request->getPost('autorizo_datos');

        if ($nombre === '' || $documento === '' || $cargo === '' || $emailCorp === '') {
            return redirect()->to($redir . '?error=' . urlencode('Nombre, documento, cargo y email corporativo son obligatorios.'));
        }

        if (!filter_var($emailCorp, FILTER_VALIDATE_EMAIL)) {
            return redirect()->to($redir . '?error=' . urlencode('El email corporativo no es válido.'));
        }

        if (!$autorizo) {
            return redirect()->to($redir . '?error=' . urlencode('Debes aceptar el tratamiento de datos personales para continuar.'));
        }

        (new ParticipantModel())->insert([
            'sesion_id'              => $sesion['id'],
            'nombre'                 => $nombre,
            'documento'               => $documento,
            'cargo'                  => $cargo,
            'email_corporativo'      => $emailCorp,
            'email_personal'         => $emailPersonal,
            'whatsapp'               => $whatsapp,
            'tiene_personal_a_cargo' => $tienePersonal ? 1 : 0,
            'autorizo_datos'         => 1,
        ]);

        return redirect()->to('/el-meridian/registrado/' . $sesionToken);
    }

    public function registrado(string $sesionToken)
    {
        $sesion = (new SesionModel())->findByToken($sesionToken);
        if (!$sesion) {
            return redirect()->to('/el-meridian/registro/' . $sesionToken);
        }

        return view('el-meridian/registrado');
    }

    /**
     * Pantalla del rol: decide si mostrar la pregunta del momento actual, la
     * pantalla de espera (ya respondió, falta el equipo), o el cierre (el
     * equipo ya completó todos los momentos).
     */
    public function rol(string $participantToken)
    {
        $participant = (new ParticipantModel())->findByToken($participantToken);
        if (!$participant || !$participant['role']) {
            return view('el-meridian/enlace_invalido');
        }

        $momentos = el_meridian_momentos()[$participant['role']]['momentos'] ?? [];
        $totalMomentos = count($momentos);
        if ($totalMomentos === 0) {
            return view('el-meridian/enlace_invalido');
        }

        $respuestaModel = new RespuestaMomentoModel();
        $totalEquipo = (new ParticipantModel())
            ->where('sesion_id', $participant['sesion_id'])
            ->where('team', $participant['team'])
            ->countAllResults();

        $momentoActual = $respuestaModel->momentoActualDelEquipo(
            (int) $participant['sesion_id'],
            $participant['team'],
            $totalEquipo,
            $totalMomentos
        );

        if ($momentoActual > $totalMomentos) {
            return view('el-meridian/cierre', ['participant' => $participant]);
        }

        $yaRespondio = $respuestaModel->respuestaDe((int) $participant['id'], $momentoActual);
        if ($yaRespondio) {
            $respondidos = $respuestaModel->respondidosEnMomento(
                (int) $participant['sesion_id'],
                $participant['team'],
                $momentoActual
            );

            return view('el-meridian/esperando', [
                'participant'   => $participant,
                'momentoActual' => $momentoActual,
                'totalMomentos' => $totalMomentos,
                'respondidos'   => $respondidos,
                'totalEquipo'   => $totalEquipo,
            ]);
        }

        return view('el-meridian/momento', [
            'participant'   => $participant,
            'momentoActual' => $momentoActual,
            'totalMomentos' => $totalMomentos,
            'momentoData'   => $momentos[$momentoActual],
        ]);
    }

    public function responderMomento(string $participantToken)
    {
        $participant = (new ParticipantModel())->findByToken($participantToken);
        if (!$participant || !$participant['role']) {
            return view('el-meridian/enlace_invalido');
        }

        $momento = (int) $this->request->getPost('momento');
        $respuesta = (string) $this->request->getPost('respuesta');

        if ($momento > 0 && $respuesta !== '') {
            (new RespuestaMomentoModel())->guardar((int) $participant['id'], $momento, $respuesta);
        }

        return redirect()->to('/el-meridian/rol/' . $participantToken);
    }

    /**
     * Endpoint de polling para la pantalla de espera: le dice al celular si
     * su equipo ya completó el momento actual, sin recargar toda la vista.
     */
    public function estado(string $participantToken)
    {
        $participant = (new ParticipantModel())->findByToken($participantToken);
        if (!$participant || !$participant['role']) {
            return $this->response->setJSON(['listo' => false]);
        }

        $momento = (int) $this->request->getGet('momento');
        $respuestaModel = new RespuestaMomentoModel();
        $totalEquipo = (new ParticipantModel())
            ->where('sesion_id', $participant['sesion_id'])
            ->where('team', $participant['team'])
            ->countAllResults();
        $respondidos = $respuestaModel->respondidosEnMomento(
            (int) $participant['sesion_id'],
            $participant['team'],
            $momento
        );

        return $this->response->setJSON([
            'listo'       => $respondidos >= $totalEquipo,
            'respondidos' => $respondidos,
            'total'       => $totalEquipo,
        ]);
    }
}
