<?php

if (!function_exists('ardurra_role_cards')) {
    function ardurra_role_cards(): array
    {
        return require APPPATH . 'Data/ardurra_roles.php';
    }
}

if (!function_exists('ardurra_role_answers')) {
    function ardurra_role_answers(): array
    {
        return require APPPATH . 'Data/ardurra_answers.php';
    }
}
