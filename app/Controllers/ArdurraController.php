<?php

namespace App\Controllers;

use App\Models\ParticipantModel;
use App\Models\SesionModel;

class ArdurraController extends BaseController
{
    public function registro(string $sesionToken)
    {
        $sesion = (new SesionModel())->findByToken($sesionToken);
        if (!$sesion || $sesion['dinamica_slug'] !== 'ardurra') {
            return $this->response->setStatusCode(404)->setBody(
                view('ardurra/enlace_invalido')
            );
        }

        return view('ardurra/registro', [
            'sesion' => $sesion,
            'sesionToken' => $sesionToken,
            'error'  => $this->request->getGet('error'),
        ]);
    }

    public function asignar()
    {
        $sesionToken = (string) $this->request->getPost('s');
        $sesion = (new SesionModel())->findByToken($sesionToken);

        if (!$sesion || $sesion['dinamica_slug'] !== 'ardurra') {
            return $this->response->setStatusCode(404)->setBody('Sesión no válida.');
        }

        $redir = '/ardurra/registro/' . $sesionToken;

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

        return redirect()->to('/ardurra/registrado/' . $sesionToken);
    }

    public function registrado(string $sesionToken)
    {
        $sesion = (new SesionModel())->findByToken($sesionToken);
        if (!$sesion) {
            return redirect()->to('/ardurra/registro/' . $sesionToken);
        }

        return view('ardurra/registrado');
    }

    public function rol(string $participantToken)
    {
        $participant = (new ParticipantModel())->findByToken($participantToken);
        $card = $participant && $participant['role'] ? (ardurra_role_cards()[$participant['role']] ?? null) : null;

        return view('ardurra/rol', ['participant' => $participant, 'card' => $card]);
    }
}
