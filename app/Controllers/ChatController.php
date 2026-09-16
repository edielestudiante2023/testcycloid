<?php

namespace App\Controllers;

use App\Models\ParticipantModel;
use App\Models\TeamChatModel;

/**
 * Chat por equipo. Genérico — no depende de la dinámica, funciona igual
 * para El Meridián, Volver a Casa y Código Azul, porque solo necesita el
 * token del participante para saber a qué sesión y equipo pertenece.
 */
class ChatController extends BaseController
{
    public function enviar(string $participantToken)
    {
        $participant = (new ParticipantModel())->findByToken($participantToken);
        if (!$participant || !$participant['team']) {
            return $this->response->setJSON(['ok' => false]);
        }

        $mensaje = (string) $this->request->getPost('mensaje');
        (new TeamChatModel())->enviar(
            (int) $participant['sesion_id'],
            $participant['team'],
            (int) $participant['id'],
            $participant['nombre'],
            $mensaje
        );

        return $this->response->setJSON(['ok' => true]);
    }

    public function mensajes(string $participantToken)
    {
        $participant = (new ParticipantModel())->findByToken($participantToken);
        if (!$participant || !$participant['team']) {
            return $this->response->setJSON(['mensajes' => []]);
        }

        $desdeId = (int) $this->request->getGet('desde');
        $mensajes = (new TeamChatModel())->mensajesDe(
            (int) $participant['sesion_id'],
            $participant['team'],
            $desdeId
        );

        return $this->response->setJSON(['mensajes' => $mensajes]);
    }
}
