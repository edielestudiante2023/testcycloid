<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Resultados — <?= esc($sesion['cliente']) ?></title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <div class="top-nav">
        <a href="<?= site_url('sesiones/' . $sesion['dinamica_slug']) ?>">← Sesiones</a>
        <a href="<?= site_url('sesiones/qr/' . $sesion['token']) ?>">Ver QR</a>
    </div>
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>

    <div class="card">
        <h1>El Meridián</h1>
        <p class="muted">Sesión para: <strong><?= esc($sesion['cliente']) ?></strong> — estado: <?= esc($sesion['estado']) ?></p>
    </div>

    <?php if (empty($equipos)): ?>
        <div class="card"><p class="muted">Todavía no hay respuestas registradas en esta sesión.</p></div>
    <?php endif; ?>

    <?php foreach ($equipos as $eq): ?>
        <div class="card">
            <h2><?= esc($eq['team']) ?></h2>

            <div class="answer-box" style="margin-bottom:20px;">
                <p style="margin:0 0 6px; font-weight:700;">
                    <?= (int) $eq['positivos'] ?> de <?= (int) $eq['totalConDato'] ?> sintieron que su opinión llegó con claridad a la decisión final
                </p>
                <p class="muted" style="margin:0;"><?= esc($eq['resumenTexto']) ?></p>
            </div>

            <?php foreach ($eq['momentos'] as $numMomento => $respuestas): ?>
                <p class="muted" style="margin-bottom:4px;">Momento <?= (int) $numMomento ?></p>
                <?php if (empty($respuestas)): ?>
                    <p class="muted" style="margin-top:0;">Sin respuestas todavía.</p>
                <?php else: ?>
                    <table style="margin-bottom:16px;">
                        <tbody>
                        <?php foreach ($respuestas as $r): ?>
                            <tr>
                                <td><span class="role-badge"><?= esc($r['rol']) ?></span></td>
                                <td><?= esc($r['etiqueta']) ?></td>
                                <td class="muted" style="text-align:right;"><?= (int) $r['count'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <?php if ($sesion['estado'] !== 'cerrada'): ?>
    <div class="card">
        <form method="post" action="<?= site_url('sesiones/cerrar/' . $sesion['token']) ?>"
              onsubmit="return confirm('¿Cerrar el ejercicio? Se enviará un resumen a tu correo.');">
            <button type="submit" class="btn-danger">Cerrar ejercicio</button>
        </form>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
