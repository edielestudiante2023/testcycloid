<?php

namespace App\Models;

/**
 * Puntajes deterministas (0=bajo/evasivo, 1=medio, 2=alto/directo) para cada
 * opción de cada rol en cada momento de "Volver a Casa". Mismo patrón que
 * ElMeridianDimensiones — la radiografía del equipo sale matemáticamente de
 * aquí, ningún modelo de IA la inventa.
 *
 * Cada momento alimenta una dimensión distinta:
 *  Momento 1 -> Detección temprana del riesgo
 *  Momento 2 -> Intercambio de información
 *  Momento 3 -> Claridad al comunicar
 *  Momento 4 -> Capacidad de disentir bajo presión
 *  Persistencia ante presión -> compara el promedio del Momento 3 contra el 4
 *  Momento 5 -> Percepción de haber sido escuchado (ver
 *               VolverACasaAnalisisModel::POLARIDAD_MOMENTO_FINAL)
 */
class VolverACasaDimensiones
{
    public const PUNTAJES = [
        1 => [
            'A' => ['poco' => 0, 'algo' => 1, 'mucho' => 2],
            'B' => ['nada' => 0, 'algo' => 1, 'mucho' => 2],
            'C' => ['poco' => 0, 'algo' => 1, 'mucho' => 2],
            'D' => ['poco' => 0, 'algo' => 1, 'mucho' => 2],
            'E' => ['poco' => 0, 'algo' => 1, 'mucho' => 2],
        ],
        2 => [
            'A' => ['dejo_pasar' => 0, 'matizo' => 1, 'sostengo' => 2],
            'B' => ['espero_a_otros' => 0, 'la_matizo' => 1, 'la_afirmo' => 2],
            'C' => ['lo_pospongo' => 0, 'con_margen' => 1, 'el_numero_corto' => 2],
            'D' => ['no_lo_menciono' => 0, 'lo_resumo_tecnico' => 1, 'lo_digo_completo' => 2],
            'E' => ['me_quedo_observando' => 0, 'lo_sugiero_en_privado' => 1, 'lo_digo' => 2],
        ],
        3 => [
            'A' => ['lo_dejo' => 0, 'espero_pausa' => 1, 'corrijo_ya' => 2],
            'B' => ['camino_corto' => 0, 'pido_mas_datos' => 1, 'camino_largo' => 2],
            'C' => ['lo_dejo' => 0, 'espero_pausa' => 1, 'corrijo_ya' => 2],
            'D' => ['pregunto_al_director' => 0, 'dosificada' => 1, 'todo' => 2],
            'E' => ['no_hago_nada' => 0, 'lo_guardo_para_despues' => 1, 'lo_señalo' => 2],
        ],
        4 => [
            'A' => ['doy_optimista' => 0, 'pido_tiempo' => 1, 'doy_la_real' => 2],
            'B' => ['el_estimado' => 0, 'pido_un_minuto' => 1, 'el_verificado' => 2],
            'C' => ['el_generoso' => 0, 'pido_un_minuto' => 1, 'el_del_peor_caso' => 2],
            'D' => ['tranquilizo' => 0, 'pido_tiempo_al_director' => 1, 'plan_en_marcha' => 2],
            'E' => ['confio_en_el_equipo' => 0, 'espero_a_ver_que_pasa' => 1, 'aviso_al_director' => 2],
        ],
    ];

    public const NOMBRES_DIMENSION = [
        1 => 'Detección temprana del riesgo',
        2 => 'Intercambio de información',
        3 => 'Claridad al comunicar',
        4 => 'Capacidad de disentir bajo presión',
    ];

    public static function puntaje(int $momento, string $rol, string $valor): ?int
    {
        return self::PUNTAJES[$momento][$rol][$valor] ?? null;
    }

    public static function nivel(float $promedio): string
    {
        if ($promedio >= 1.35) {
            return 'Alta';
        }
        if ($promedio >= 0.65) {
            return 'Media';
        }
        return 'Baja';
    }
}
