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
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>
    <div class="card">
        <h1>El Meridián</h1>

        <p class="confidential">Son las 4:40 de la tarde. El Meridián zarpó esta madrugada desde Puerto
Almenara, con destino a Bahía Coral — una ruta que la tripulación conoce de memoria, la han hecho
decenas de veces.

Esta vez salieron con retraso. Nada grave todavía, pero suficiente para que en las oficinas alguien ya
esté preguntando cuándo llegan.

A bordo va tu equipo — cada quien con una tarea distinta en el puente.</p>

        <h2>Tu equipo</h2>
        <table>
            <tbody>
            <?php foreach ($companeros as $c): ?>
                <tr>
                    <td><?= esc($c['nombre']) ?><?= $c['esTu'] ? ' <span class="muted">(tú)</span>' : '' ?></td>
                    <td class="muted"><?= esc($c['rol']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <p style="margin-top:20px;">En los próximos minutos vas a recibir información parcial — distinta
        a la de tus compañeros. Nadie a bordo tiene el panorama completo, ni siquiera tú. Vas a leer,
        responder en privado, y hablar con tu equipo antes de que se destrabe lo que sigue.</p>

        <p class="muted">Cuando estés listo, entra a ver tu rol. Es confidencial — no se lo muestres a nadie.</p>

        <a class="btn" href="<?= site_url('el-meridian/rol/' . $participant['token']) ?>">Ver mi rol</a>
    </div>
</div>
</body>
</html>
