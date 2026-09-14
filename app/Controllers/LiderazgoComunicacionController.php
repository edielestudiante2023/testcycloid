<?php

namespace App\Controllers;

use App\Models\ParticipantModel;
use App\Models\SesionModel;

class LiderazgoComunicacionController extends BaseController
{
    public function registro(string $sesionToken)
    {
        $sesion = (new SesionModel())->findByToken($sesionToken);
        if (!$sesion || $sesion['dinamica_slug'] !== 'liderazgo-comunicacion') {
            return $this->response->setStatusCode(404)->setBody(
                view('liderazgo-comunicacion/enlace_invalido')
            );
        }

        return view('liderazgo-comunicacion/registro', [
            'sesion' => $sesion,
            'sesionToken' => $sesionToken,
            'error'  => $this->request->getGet('error'),
        ]);
    }

    public function asignar()
    {
        $sesionToken = (string) $this->request->getPost('s');
        $sesion = (new SesionModel())->findByToken($sesionToken);

        if (!$sesion || $sesion['dinamica_slug'] !== 'liderazgo-comunicacion') {
            return $this->response->setStatusCode(404)->setBody('Sesión no válida.');
        }

        $redir = '/liderazgo-comunicacion/registro/' . $sesionToken;

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

        return redirect()->to('/liderazgo-comunicacion/registrado/' . $sesionToken);
    }

    public function registrado(string $sesionToken)
    {
        $sesion = (new SesionModel())->findByToken($sesionToken);
        if (!$sesion) {
            return redirect()->to('/liderazgo-comunicacion/registro/' . $sesionToken);
        }

        return view('liderazgo-comunicacion/registrado');
    }

    public function rol(string $participantToken)
    {
        $participant = (new ParticipantModel())->findByToken($participantToken);
        $card = $participant && $participant['role'] ? (liderazgo_comunicacion_role_cards()[$participant['role']] ?? null) : null;

        return view('liderazgo-comunicacion/rol', ['participant' => $participant, 'card' => $card]);
    }
}
