<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('login', 'AuthController::showLogin');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');
$routes->get('login/olvide', 'AuthController::forgotPassword');
$routes->post('login/olvide', 'AuthController::sendResetLink');
$routes->get('login/restablecer/(:segment)', 'AuthController::resetPassword/$1');
$routes->post('login/restablecer/(:segment)', 'AuthController::updatePassword/$1');

$routes->get('/', 'DashboardController::index', ['filter' => 'auth']);

$routes->group('sesiones', ['filter' => 'auth'], static function (RouteCollection $routes) {
    $routes->get('nueva/(:segment)', 'SesionesController::nueva/$1');
    $routes->post('crear', 'SesionesController::crear');
    $routes->get('qr/(:segment)', 'SesionesController::qr/$1');
    $routes->get('contador/(:segment)', 'SesionesController::contador/$1');
    $routes->post('enviar/(:segment)', 'SesionesController::enviar/$1');
    $routes->post('cerrar/(:segment)', 'SesionesController::cerrar/$1');
    $routes->get('resultados/(:segment)', 'SesionesController::resultados/$1');
    $routes->post('liderazgo/(:segment)', 'SesionesController::liderazgo/$1');
    $routes->get('(:segment)', 'SesionesController::listar/$1');
});

$routes->group('liderazgo-comunicacion', static function (RouteCollection $routes) {
    $routes->get('registro/(:segment)', 'LiderazgoComunicacionController::registro/$1');
    $routes->post('asignar', 'LiderazgoComunicacionController::asignar');
    $routes->get('registrado/(:segment)', 'LiderazgoComunicacionController::registrado/$1');
    $routes->get('rol/(:segment)', 'LiderazgoComunicacionController::rol/$1');
});
