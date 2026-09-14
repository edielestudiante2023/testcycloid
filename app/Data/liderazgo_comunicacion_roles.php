<?php
declare(strict_types=1);

/**
 * Contenido de las tarjetas secretas de la dinámica Liderazgo y Comunicación.
 * @return array<string, array{title: string, body: string}>
 */
return [
    'A' => [
        'title' => 'TARJETA A — COORDINACIÓN',
        'body'  => <<<TXT
Información confidencial

El cliente indicó esta mañana que el Entregable B es indispensable para hoy.

Los entregables A y C podrían aplazarse si existe una justificación.

Además, el cliente quiere tener un único punto de contacto para esta entrega.

Tu equipo todavía no ha definido quién será.

Esta persona tiene información sobre prioridad.
TXT,
    ],
    'B' => [
        'title' => 'TARJETA B — DISEÑO',
        'body'  => <<<TXT
Información confidencial

Eres responsable técnico del Entregable B.

Para terminarlo necesitas trabajar sobre el plano correcto.

En tu documentación aparece:

VERSIÓN V5

Puedes empezar inmediatamente.

No sabes si después se publicó una nueva versión.
TXT,
    ],
    'C' => [
        'title' => 'TARJETA C — NUEVO INTEGRANTE',
        'body'  => <<<TXT
Información confidencial

Llevas poco tiempo en el equipo.

Hace una hora viste una actualización que decía:

"La versión V5 queda anulada. Utilizar V6 para la entrega de hoy."

También viste que el cliente preguntó quién será su punto de contacto.

Nadie del equipo te ha preguntado todavía por esta información.
TXT,
    ],
    'D' => [
        'title' => 'TARJETA D — CALIDAD',
        'body'  => <<<TXT
Información confidencial

Antes de enviar el Entregable B debes revisar la versión utilizada.

Sabes que:

Si B se prepara utilizando una versión incorrecta, la revisión debe repetirse desde el comienzo.

Tú no sabes cuál es la versión vigente.

Necesitas obtener esa información del equipo.
TXT,
    ],
    'E' => [
        'title' => 'TARJETA E — SOPORTE REMOTO',
        'body'  => <<<TXT
Información confidencial

Estás trabajando remotamente.

Puedes terminar el Entregable C rápidamente.

Sin embargo, también puedes apoyar el Entregable B y reducir significativamente su tiempo de preparación.

No sabes cuál de las dos tareas tiene mayor prioridad.

Necesitas que el equipo tome una decisión.
TXT,
    ],
];
