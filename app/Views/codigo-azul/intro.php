<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Código Azul — Antes de empezar</title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>
    <div class="card">
        <h1>Código Azul</h1>

        <div class="confidential"><strong>Turno de la tarde. Hospital Regional Altamira.</strong>

La sala nunca está realmente en silencio.

Hay un monitor marcando un ritmo constante. Pasos que entran y salen. Una conversación breve en el pasillo. El sonido de algo rodando sobre baldosa.

Nada de esto es una emergencia.

Todavía.

Un paciente lleva horas bajo observación después de un procedimiento sin complicaciones. Los números se ven bien. El equipo se mueve con la calma de quien ya ha hecho esto muchas veces.

Pero cada persona en esta sala está mirando algo distinto.

Alguien vigila una pantalla.

Alguien más espera un resultado que todavía no llega.

Alguien acaba de notar algo que no termina de encajar con lo que vio hace un minuto.

Y alguien, en algún punto, va a tener que unir todo eso antes de que sea demasiado tarde para que la unión sirva de algo.

Nadie tiene la historia completa.

Cada quien observa apenas un fragmento: una tendencia, un número, una sensación que todavía no tiene nombre.

El médico tratante no está en la sala en este momento.

Va y viene. Escucha lo que le dicen cuando vuelve. Decide con lo que el equipo logra ponerle enfrente.

Y ahí está el problema real:

<strong>lo que tú ves no es lo que ven los demás.</strong>

Y lo que ellos ven, tú no lo sabes.</div>

        <h2>Tu equipo</h2>
        <table>
            <thead><tr><th>Integrante</th><th>Rol</th></tr></thead>
            <tbody>
            <?php foreach ($companeros as $c): ?>
                <tr>
                    <td><?= esc($c['nombre']) ?><?= $c['esTu'] ? ' <span class="muted">(tú)</span>' : '' ?></td>
                    <td class="muted"><?= esc($c['rol']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="confidential" style="margin-top:20px;">En los próximos minutos vas a recibir tu propia pieza de esta situación.

Algunas piezas van a parecer urgentes desde el principio. Otras quizá no… hasta que ya no haya tiempo de ignorarlas.

<strong>Nadie en esta sala tiene el panorama completo.</strong>

Ni siquiera tú.

Vas a tener que leer lo que llegue a tu turno, decidir qué tan seguro estás de lo que ves, y decirlo — o guardártelo.

Pero recuerda algo:

<strong>ver algo no significa que los demás también lo hayan visto.</strong>

<strong>Pensarlo no significa haberlo dicho con claridad.</strong>

Y decirlo…

<strong>no significa que alguien lo haya tomado en cuenta.</strong>

Cuando estés listo, entra.

A partir de este momento, lo que sabes es confidencial.

<strong>No muestres tu pantalla. No compartas tu rol.</strong>

La situación ya está en marcha. Y lo que el equipo decida va a depender de lo que ustedes sean capaces de poner sobre la mesa a tiempo.</div>

        <a class="btn" href="<?= site_url('codigo-azul/rol/' . $participant['token']) ?>">Ver mi rol</a>
    </div>
</div>
</body>
</html>
