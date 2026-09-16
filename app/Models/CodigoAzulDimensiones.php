<?php

namespace App\Models;

/**
 * Puntajes deterministas (0=bajo/evasivo, 1=medio, 2=alto/directo) para cada
 * opción de cada rol en cada momento de "Código Azul". Mismo patrón que
 * ElMeridianDimensiones/VolverACasaDimensiones — la radiografía del equipo
 * sale matemáticamente de aquí, ningún modelo de IA la inventa.
 *
 * Nota sobre el Rol D (Puente): su escala de "detección temprana" va en el
 * sentido contrario a los demás roles — "mucho" ahí significa "mucha
 * confianza para tranquilizar" (baja alerta), y "poco" significa "poca
 * confianza para tranquilizar" (alta alerta, más prudente). Por eso su fila
 * en el Momento 1 tiene los puntajes invertidos respecto a A/B/C/E.
 *
 * Cada momento alimenta una dimensión distinta:
 *  Momento 1 -> Detección temprana del riesgo
 *  Momento 2 -> Intercambio de información
 *  Momento 3 -> Claridad al comunicar
 *  Momento 4 -> Capacidad de disentir bajo presión
 *  Persistencia ante presión -> compara el promedio del Momento 3 contra el 4
 *  Momento 5 -> Percepción de haber sido escuchado (ver
 *               CodigoAzulAnalisisModel::POLARIDAD_MOMENTO_FINAL)
 */
class CodigoAzulDimensiones
{
    public const PUNTAJES = [
        1 => [
            'A' => ['poco' => 0, 'algo' => 1, 'mucho' => 2],
            'B' => ['poco' => 0, 'algo' => 1, 'mucho' => 2],
            'C' => ['poco' => 0, 'algo' => 1, 'mucho' => 2],
            'D' => ['mucho' => 0, 'algo' => 1, 'poco' => 2],
            'E' => ['poco' => 0, 'algo' => 1, 'mucho' => 2],
        ],
        2 => [
            'A' => ['cedo' => 0, 'matizo' => 1, 'sostengo' => 2],
            'B' => ['cedo' => 0, 'matizo' => 1, 'sostengo' => 2],
            'C' => ['cedo' => 0, 'matizo' => 1, 'sostengo' => 2],
            'D' => ['tranquilizo' => 0, 'matizo' => 1, 'prudente' => 2],
            'E' => ['cedo' => 0, 'matizo' => 1, 'sostengo' => 2],
        ],
        3 => [
            'A' => ['incierta' => 0, 'firme_reservas' => 1, 'muy_firme' => 2],
            'B' => ['incierta' => 0, 'firme_reservas' => 1, 'muy_firme' => 2],
            'C' => ['incierta' => 0, 'firme_reservas' => 1, 'muy_firme' => 2],
            'D' => ['poco_firme' => 0, 'firme_reservas' => 1, 'muy_firme' => 2],
            'E' => ['poco_firme' => 0, 'firme_reservas' => 1, 'muy_firme' => 2],
        ],
        4 => [
            'A' => ['cedo' => 0, 'matizo' => 1, 'sostengo' => 2],
            'B' => ['cedo' => 0, 'matizo' => 1, 'sostengo' => 2],
            'C' => ['cedo' => 0, 'matizo' => 1, 'sostengo' => 2],
            'D' => ['cedo' => 0, 'matizo' => 1, 'sostengo' => 2],
            'E' => ['cedo' => 0, 'matizo' => 1, 'sostengo' => 2],
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
