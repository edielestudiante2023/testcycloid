<?php

if (!function_exists('codigo_azul_momentos')) {
    function codigo_azul_momentos(): array
    {
        return require APPPATH . 'Data/codigo_azul_momentos.php';
    }
}
