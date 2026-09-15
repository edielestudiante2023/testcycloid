<?php
declare(strict_types=1);

/**
 * Respuesta esperada por rol, para uso exclusivo del facilitador en el cierre reflexivo.
 * @return array<string, string>
 */
return [
    'A' => 'Prioridad: B es indispensable. A y C pueden aplazarse con justificación. '
         . 'El equipo debe definir un único punto de contacto para el cliente.',
    'B' => 'La versión correcta es V6, no V5. No debía empezar a trabajar sin validar '
         . 'con el equipo si había una versión más reciente.',
    'C' => 'Tenía la actualización clave (V5 anulada → usar V6) y sabía que el cliente '
         . 'pedía un punto de contacto. Pregunta para el cierre: ¿cuándo compartió esta '
         . 'información y qué hizo que apareciera en ese momento y no antes?',
    'D' => 'Debe validar el Entregable B sobre la versión V6. Si se preparó con V5, '
         . 'la revisión de calidad debe repetirse desde el comienzo.',
    'E' => 'Debe dejar el Entregable C (con la actualización del cliente ya no es '
         . 'obligatorio hoy) y apoyar al Entregable B para reducir su tiempo de entrega.',
];
