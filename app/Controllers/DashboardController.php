<?php

namespace App\Controllers;

use App\Models\DinamicaModel;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('dashboard/index', [
            'usuario'   => ['email' => session('usuario_email')],
            'dinamicas' => (new DinamicaModel())->activas(),
        ]);
    }
}
