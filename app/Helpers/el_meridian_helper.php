<?php

if (!function_exists('el_meridian_momentos')) {
    function el_meridian_momentos(): array
    {
        return require APPPATH . 'Data/el_meridian_momentos.php';
    }
}
