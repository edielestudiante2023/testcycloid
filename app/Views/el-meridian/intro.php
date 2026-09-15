<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>El Meridián — Antes de empezar</title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <img src="<?= base_url('assets/img/el-meridian-hero.jpg') ?>" alt="El Meridián — Distintas miradas, una decisión" style="width:100%; height:auto; border-radius:12px; margin-bottom:16px; display:block;">
    <div class="card">
        <h1>El Meridián</h1>

        <div class="confidential"><strong>Son las 4:40 de la tarde.</strong>

Hasta hace unos minutos, este era un viaje como cualquier otro.

El Meridián zarpó de madrugada desde Puerto Almenara con destino a Bahía Coral. La tripulación conoce esta ruta de memoria. La han recorrido tantas veces que algunos podrían señalar sus puntos de referencia sin mirar el mapa.

Pero hoy hay algo diferente.

El barco salió con retraso.

En las oficinas ya están preguntando a qué hora llegarán.

Y allá afuera, en algún lugar del horizonte, el cielo comienza a cambiar.

Todavía nadie habla de peligro.

Todavía.

A bordo, cada persona continúa haciendo su trabajo. Alguien observa el cielo. Alguien estudia la ruta. Alguien escucha lo que ocurre entre la tripulación. Alguien conoce cosas del barco que los demás desconocen.

Y tú también sabes algo.

<strong>Solo que todavía no sabes qué saben los demás.</strong></div>

        <h2>Tu equipo</h2>
        <table>
            <thead><tr><th>Tripulante</th><th>Rol</th></tr></thead>
            <tbody>
            <?php foreach ($companeros as $c): ?>
                <tr>
                    <td><?= esc($c['nombre']) ?><?= $c['esTu'] ? ' <span class="muted">(tú)</span>' : '' ?></td>
                    <td class="muted"><?= esc($c['rol']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="confidential" style="margin-top:20px;">En los próximos minutos algo va a cambiar.

Cada integrante recibirá información diferente. Algunas piezas parecerán importantes. Otras quizá no… hasta que sea demasiado tarde para ignorarlas.

<strong>Nadie tiene el panorama completo.</strong>

Ni siquiera tú.

Tendrás que leer lo que llegue a tus manos, decidir qué significa y hablar con tu equipo.

Pero recuerda algo:

<strong>saber algo no significa que los demás lo sepan.</strong>

<strong>Pensarlo no significa haberlo dicho.</strong>

Y decirlo…

<strong>no significa que alguien realmente lo haya escuchado.</strong>

Cuando estés listo, entra.

A partir de este momento, lo que sabes es confidencial.

<strong>No muestres tu pantalla. No compartas tu rol.</strong>

El Meridián ya está en marcha. Y las próximas decisiones se tomarán con la información que ustedes sean capaces de poner sobre la mesa.</div>

        <a class="btn" href="<?= site_url('el-meridian/rol/' . $participant['token']) ?>">Ver mi rol</a>
    </div>
</div>
</body>
</html>
