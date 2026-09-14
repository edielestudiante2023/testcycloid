<?php

if (!function_exists('liderazgo_comunicacion_role_cards')) {
    function liderazgo_comunicacion_role_cards(): array
    {
        return require APPPATH . 'Data/liderazgo_comunicacion_roles.php';
    }
}

if (!function_exists('liderazgo_comunicacion_role_answers')) {
    function liderazgo_comunicacion_role_answers(): array
    {
        return require APPPATH . 'Data/liderazgo_comunicacion_answers.php';
    }
}
