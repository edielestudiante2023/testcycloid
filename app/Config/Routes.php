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
    $routes->get('progreso/(:segment)', 'SesionesController::progreso/$1');
    $routes->post('enviar/(:segment)', 'SesionesController::enviar/$1');
    $routes->post('cerrar/(:segment)', 'SesionesController::cerrar/$1');
    $routes->post('forzar-avance/(:segment)', 'SesionesController::forzarAvance/$1');
    $routes->post('reciclar/(:segment)', 'SesionesController::reciclarParticipantes/$1');
    $routes->get('resultados/(:segment)', 'SesionesController::resultados/$1');
    $routes->get('consolidado/(:segment)', 'SesionesController::consolidado/$1');
    $routes->post('consolidado-enviar/(:segment)', 'SesionesController::enviarConsolidadoEmail/$1');
    $routes->post('liderazgo/(:segment)', 'SesionesController::liderazgo/$1');
    $routes->get('(:segment)', 'SesionesController::listar/$1');
});

$routes->group('liderazgo-comunicacion', static function (RouteCollection $routes) {
    $routes->get('registro/(:segment)', 'LiderazgoComunicacionController::registro/$1');
    $routes->post('asignar', 'LiderazgoComunicacionController::asignar');
    $routes->get('registrado/(:segment)', 'LiderazgoComunicacionController::registrado/$1');
    $routes->get('rol/(:segment)', 'LiderazgoComunicacionController::rol/$1');
});

$routes->group('el-meridian', static function (RouteCollection $routes) {
    $routes->get('registro/(:segment)', 'ElMeridianController::registro/$1');
    $routes->post('asignar', 'ElMeridianController::asignar');
    $routes->get('registrado/(:segment)', 'ElMeridianController::registrado/$1');
    $routes->get('intro/(:segment)', 'ElMeridianController::intro/$1');
    $routes->get('rol/(:segment)', 'ElMeridianController::rol/$1');
    $routes->post('momento/(:segment)', 'ElMeridianController::responderMomento/$1');
    $routes->get('estado/(:segment)', 'ElMeridianController::estado/$1');
});

$routes->group('volver-a-casa', static function (RouteCollection $routes) {
    $routes->get('registro/(:segment)', 'VolverACasaController::registro/$1');
    $routes->post('asignar', 'VolverACasaController::asignar');
    $routes->get('registrado/(:segment)', 'VolverACasaController::registrado/$1');
    $routes->get('intro/(:segment)', 'VolverACasaController::intro/$1');
    $routes->get('rol/(:segment)', 'VolverACasaController::rol/$1');
    $routes->post('momento/(:segment)', 'VolverACasaController::responderMomento/$1');
    $routes->get('estado/(:segment)', 'VolverACasaController::estado/$1');
});

$routes->group('codigo-azul', static function (RouteCollection $routes) {
    $routes->get('registro/(:segment)', 'CodigoAzulController::registro/$1');
    $routes->post('asignar', 'CodigoAzulController::asignar');
    $routes->get('registrado/(:segment)', 'CodigoAzulController::registrado/$1');
    $routes->get('intro/(:segment)', 'CodigoAzulController::intro/$1');
    $routes->get('rol/(:segment)', 'CodigoAzulController::rol/$1');
    $routes->post('momento/(:segment)', 'CodigoAzulController::responderMomento/$1');
    $routes->get('estado/(:segment)', 'CodigoAzulController::estado/$1');
});
