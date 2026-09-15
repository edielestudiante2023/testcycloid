<?php

namespace App\Models;

/**
 * Puntajes deterministas (0=bajo/suavizado, 1=medio, 2=alto/directo) para
 * cada opcion de cada rol en cada momento de El Meridian. Sirven para
 * calcular la "radiografia" del equipo matematicamente, sin que ningun
 * modelo de IA tenga que inventarse los numeros.
 *
 * Cada momento alimenta una dimension distinta:
 *  Momento 1 -> Deteccion temprana del riesgo
 *  Momento 2 -> Intercambio de informacion
 *  Momento 3 -> Claridad al comunicar
 *  Momento 4 -> Capacidad de disentir bajo presion
 *  Persistencia ante presion -> compara el promedio del Momento 3 contra el 4
 *  Momento 5 -> Percepcion de haber sido escuchado (se calcula aparte, ya
 *               existe en RespuestaMomentoModel::POLARIDAD_MOMENTO_FINAL)
 */
class ElMeridianDimensiones
{
    public const PUNTAJES = [
        1 => [
            'A' => ['poco' => 0, 'algo' => 1, 'mucho' => 2],
            'B' => ['recuperar' => 0, 'saber_mas' => 1, 'alejarnos' => 2],
            'C' => ['comentarios' => 0, 'cautela' => 1, 'importante' => 2],
            'D' => ['no_afecta' => 0, 'manejable' => 1, 'importante_decidir' => 2],
            'E' => ['no_preocupado' => 0, 'lo_callado' => 1, 'situacion_afuera' => 2],
        ],
        2 => [
            'A' => ['no' => 1, 'si' => 2],
            'B' => ['insinuado' => 0, 'suavizado' => 1, 'claro' => 2],
            'C' => ['no_hablo_por_otros' => 0, 'menciono_cautela' => 1, 'digo_claro' => 2],
            'D' => ['dejo_pasar' => 0, 'aclaro_despues' => 1, 'corrijo_ya' => 2],
            'E' => ['alguien_tome_mando' => 0, 'mas_info' => 1, 'cada_uno_recomienda' => 2],
        ],
        3 => [
            'A' => ['mantener' => 1, 'cambiar' => 2],
            'B' => ['esperar' => 0, 'mantener' => 1, 'cambiar_ahora' => 2],
            'C' => ['escucho' => 0, 'espero_otro' => 1, 'digo_observacion' => 2],
            'D' => ['esperar' => 0, 'menos_cambios' => 1, 'cambiar_ahora' => 2],
            'E' => ['seguir_analizando' => 0, 'decide_mas_experiencia' => 1, 'cada_uno_recomienda' => 2],
        ],
        4 => [
            'A' => ['callo' => 0, 'cedo' => 1, 'insisto' => 2],
            'B' => ['silencio' => 0, 'expongo_datos' => 1, 'digo_claro' => 2],
            'C' => ['dejo_continuar' => 0, 'pregunto_quien' => 1, 'digo_desacuerdos' => 2],
            'D' => ['no_digo_nada' => 0, 'espero_aclarar' => 1, 'corrijo_ahora' => 2],
            'E' => ['silencio' => 0, 'pregunto_desacuerdo' => 1, 'detengo_pido_posiciones' => 2],
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
