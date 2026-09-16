<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Volver a Casa — Antes de empezar</title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <img src="<?= base_url('assets/img/volver-a-casa-hero.jpg') ?>" alt="Volver a Casa — información distinta, una sola decisión" style="width:100%; height:auto; border-radius:12px; margin-bottom:16px; display:block;">
    <div class="card">
        <h1>Volver a Casa</h1>

        <div class="confidential"><strong>Cincuenta y cinco horas de vuelo.</strong>

Hace más de dos días que la misión Polaris IX abandonó la Tierra.

Desde entonces, el viaje ha sido casi perfecto.

La nave de mando <em>Aurora</em> y el módulo de repuesto <em>Refugio</em> avanzan acoplados en el silencio del espacio. Sin alarmas. Sin sobresaltos. Solo la oscuridad extendiéndose alrededor y, a lo lejos, la Tierra haciéndose cada vez más pequeña.

En el Centro Deneb, todo parece igual de tranquilo.

Las voces se cruzan entre consolas. Alguien confirma una lectura. Otro responde. Las pantallas mantienen su ritmo habitual.

Hasta que una cifra cambia.

Después otra.

Y otra más.

Al principio parecen pequeñas anomalías. Nada que una misión como esta no haya visto antes.

Pero entonces la energía deja de comportarse como debería.

La trayectoria comienza a desviarse.

Y algo ocurre con el aire.

La sala cambia.

Nadie tiene todavía la historia completa. Cada especialista observa apenas un fragmento: un número extraño, una señal inesperada, una pieza de algo que todavía no tiene nombre.

El Director de Vuelo permanece en silencio.

No porque no tenga nada que decir.

Está escuchando.

Intentando construir, con las voces de todos, una imagen que todavía nadie puede ver.

Y entonces miras tu propia consola.

Hay algo allí.

Algo que tú sabes y que podría cambiar lo que ocurra después.

Pero hay un problema:

<strong>no sabes qué saben los demás.</strong>

Y ellos tampoco saben lo que sabes tú.</div>

        <h2>Tu equipo</h2>
        <table>
            <thead><tr><th>Especialista</th><th>Rol</th></tr></thead>
            <tbody>
            <?php foreach ($companeros as $c): ?>
                <tr>
                    <td><?= esc($c['nombre']) ?><?= $c['esTu'] ? ' <span class="muted">(tú)</span>' : '' ?></td>
                    <td class="muted"><?= esc($c['rol']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="confidential" style="margin-top:20px;">En los próximos minutos vas a recibir tu propia pieza de esta emergencia.

Algunas piezas parecerán importantes de inmediato. Otras quizá no… hasta que sea demasiado tarde para ignorarlas.

<strong>Nadie en la sala tiene el panorama completo.</strong>

Ni siquiera tú.

Tendrás que leer lo que llegue a tu consola, decidir qué significa y hablar con tu equipo.

Pero recuerda algo:

<strong>saber algo no significa que los demás lo sepan.</strong>

<strong>Pensarlo no significa haberlo dicho.</strong>

Y decirlo…

<strong>no significa que alguien realmente lo haya escuchado.</strong>

Cuando estés listo, entra.

A partir de este momento, lo que sabes es confidencial.

<strong>No muestres tu pantalla. No compartas tu rol.</strong>

La emergencia ya está en marcha. Y las próximas decisiones se van a tomar con la información que ustedes sean capaces de poner sobre la mesa.</div>

        <a class="btn" href="<?= site_url('volver-a-casa/rol/' . $participant['token']) ?>">Ver mi rol</a>
    </div>
</div>
</body>
</html>
