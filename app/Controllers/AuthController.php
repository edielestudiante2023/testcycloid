<?php

namespace App\Controllers;

use App\Libraries\SendGridMailer;
use App\Models\UsuarioModel;

class AuthController extends BaseController
{
    public function showLogin()
    {
        return view('auth/login', ['error' => null]);
    }

    public function login()
    {
        $email = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        $usuario = (new UsuarioModel())->findByEmail($email);

        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            session()->set('usuario_id', $usuario['id']);
            session()->set('usuario_email', $usuario['email']);
            return redirect()->to('/');
        }

        return view('auth/login', ['error' => 'Correo o contraseña incorrectos.']);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function forgotPassword()
    {
        return view('auth/olvide', ['error' => null, 'enviado' => false]);
    }

    public function sendResetLink()
    {
        $email = trim((string) $this->request->getPost('email'));

        if ($email === '') {
            return view('auth/olvide', ['error' => 'Ingresa tu correo.', 'enviado' => false]);
        }

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->findByEmail($email);

        if ($usuario) {
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', time() + 60 * 60);
            $usuarioModel->setResetToken((int) $usuario['id'], $token, $expiresAt);

            $resetUrl = site_url('login/restablecer/' . $token);
            $html = view('emails/reset_password', ['nombre' => $usuario['nombre'], 'resetUrl' => $resetUrl]);
            (new SendGridMailer())->send([$usuario['email']], 'Recupera tu contraseña — Cycloid Talent', $html);
        }

        // Mensaje siempre genérico: no revela si el correo existe o no.
        return view('auth/olvide', ['error' => null, 'enviado' => true]);
    }

    public function resetPassword(string $token)
    {
        $usuario = (new UsuarioModel())->findByValidResetToken($token);
        if (!$usuario) {
            return view('auth/restablecer', ['token' => $token, 'valido' => false, 'error' => null]);
        }

        return view('auth/restablecer', ['token' => $token, 'valido' => true, 'error' => null]);
    }

    public function updatePassword(string $token)
    {
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->findByValidResetToken($token);

        if (!$usuario) {
            return view('auth/restablecer', ['token' => $token, 'valido' => false, 'error' => null]);
        }

        $password = (string) $this->request->getPost('password');
        $confirmar = (string) $this->request->getPost('password_confirm');

        if (strlen($password) < 8) {
            return view('auth/restablecer', ['token' => $token, 'valido' => true, 'error' => 'La contraseña debe tener al menos 8 caracteres.']);
        }

        if ($password !== $confirmar) {
            return view('auth/restablecer', ['token' => $token, 'valido' => true, 'error' => 'Las contraseñas no coinciden.']);
        }

        $usuarioModel->update((int) $usuario['id'], ['password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
        $usuarioModel->clearResetToken((int) $usuario['id']);

        return view('auth/login', ['error' => null, 'mensaje' => 'Contraseña actualizada. Ya puedes ingresar.']);
    }
}
