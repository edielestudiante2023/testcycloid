<?php

namespace App\Controllers;

use App\Models\ParticipantModel;
use App\Models\SesionModel;
use App\Models\VolverACasaAnalisisModel;

class VolverACasaController extends BaseController
{
    public function registro(string $sesionToken)
    {
        $sesion = (new SesionModel())->findByToken($sesionToken);
        if (!$sesion || $sesion['dinamica_slug'] !== 'volver-a-casa') {
            return $this->response->setStatusCode(404)->setBody(
                view('volver-a-casa/enlace_invalido')
            );
        }

        return view('volver-a-casa/registro', [
            'sesion' => $sesion,
            'sesionToken' => $sesionToken,
            'error'  => $this->request->getGet('error'),
        ]);
    }

    public function asignar()
    {
        $sesionToken = (string) $this->request->getPost('s');
        $sesion = (new SesionModel())->findByToken($sesionToken);

        if (!$sesion || $sesion['dinamica_slug'] !== 'volver-a-casa') {
            return $this->response->setStatusCode(404)->setBody('Sesión no válida.');
        }

        $redir = '/volver-a-casa/registro/' . $sesionToken;

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

        return redirect()->to('/volver-a-casa/registrado/' . $sesionToken);
    }

    public function registrado(string $sesionToken)
    {
        $sesion = (new SesionModel())->findByToken($sesionToken);
        if (!$sesion) {
            return redirect()->to('/volver-a-casa/registro/' . $sesionToken);
        }

        return view('volver-a-casa/registrado');
    }

    /**
     * Pantalla de contexto, previa al rol: qué es "Volver a Casa", qué acaba
     * de pasar, quién más está en tu equipo. No revela nada confidencial —
     * solo lo que cualquiera en el Centro Deneb ya sabría.
     */
    public function intro(string $participantToken)
    {
        $participant = (new ParticipantModel())->findByToken($participantToken);
        if (!$participant || !$participant['role']) {
            return view('volver-a-casa/enlace_invalido');
        }

        $momentosDefinidos = volver_a_casa_momentos();
        $equipo = (new ParticipantModel())
            ->where('sesion_id', $participant['sesion_id'])
            ->where('team', $participant['team'])
            ->findAll();

        $companeros = [];
        foreach ($equipo as $miembro) {
            $companeros[] = [
                'nombre' => $miembro['nombre'],
                'rol'    => $momentosDefinidos[$miembro['role']]['nombre'] ?? $miembro['role'],
                'esTu'   => (int) $miembro['id'] === (int) $participant['id'],
            ];
        }

        if (empty($participant['intro_visto_at'])) {
            (new ParticipantModel())->update((int) $participant['id'], ['intro_visto_at' => date('Y-m-d H:i:s')]);
        }

        return view('volver-a-casa/intro', [
            'participant' => $participant,
            'companeros'  => $companeros,
        ]);
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
            return view('volver-a-casa/enlace_invalido');
        }

        if (empty($participant['intro_visto_at'])) {
            return redirect()->to('/volver-a-casa/intro/' . $participantToken);
        }

        $momentos = volver_a_casa_momentos()[$participant['role']]['momentos'] ?? [];
        $totalMomentos = count($momentos);
        if ($totalMomentos === 0) {
            return view('volver-a-casa/enlace_invalido');
        }

        $respuestaModel = new VolverACasaAnalisisModel();
        $totalEquipo = (new ParticipantModel())
            ->where('sesion_id', $participant['sesion_id'])
            ->where('team', $participant['team'])
            ->where('activo', 1)
            ->countAllResults();

        $momentoActual = $respuestaModel->momentoActualDelEquipo(
            (int) $participant['sesion_id'],
            $participant['team'],
            $totalEquipo,
            $totalMomentos
        );

        if ($momentoActual > $totalMomentos) {
            return view('volver-a-casa/cierre', ['participant' => $participant]);
        }

        $yaRespondio = $respuestaModel->respuestaDe((int) $participant['id'], $momentoActual);
        if ($yaRespondio) {
            $respondidos = $respuestaModel->respondidosEnMomento(
                (int) $participant['sesion_id'],
                $participant['team'],
                $momentoActual
            );

            return view('volver-a-casa/esperando', [
                'participant'   => $participant,
                'momentoActual' => $momentoActual,
                'totalMomentos' => $totalMomentos,
                'respondidos'   => $respondidos,
                'totalEquipo'   => $totalEquipo,
            ]);
        }

        return view('volver-a-casa/momento', [
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
            return view('volver-a-casa/enlace_invalido');
        }

        $momento = (int) $this->request->getPost('momento');
        $respuesta = (string) $this->request->getPost('respuesta');

        if ($momento > 0 && $respuesta !== '') {
            (new VolverACasaAnalisisModel())->guardar((int) $participant['id'], $momento, $respuesta);
        }

        return redirect()->to('/volver-a-casa/rol/' . $participantToken);
    }

    /**
     * Endpoint de polling para la pantalla de espera.
     */
    public function estado(string $participantToken)
    {
        $participant = (new ParticipantModel())->findByToken($participantToken);
        if (!$participant || !$participant['role']) {
            return $this->response->setJSON(['listo' => false]);
        }

        $momento = (int) $this->request->getGet('momento');
        $respuestaModel = new VolverACasaAnalisisModel();
        $totalEquipo = (new ParticipantModel())
            ->where('sesion_id', $participant['sesion_id'])
            ->where('team', $participant['team'])
            ->where('activo', 1)
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
