<?php

namespace App\Controllers;

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
}
