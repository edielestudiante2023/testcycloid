<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tu rol — Liderazgo y Comunicación</title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>
    <div class="card">
        <?php if (!$participant || !$card): ?>
            <h1>Enlace no válido</h1>
            <p>Este enlace de rol no existe o el ejercicio todavía no ha iniciado.</p>
        <?php else: ?>
            <p class="muted"><?= esc($participant['nombre']) ?> · <?= esc($participant['team']) ?></p>

            <div class="confidential" style="background:#EEF2FB; border-style:solid; margin-bottom:20px;">
                Su equipo debe coordinar la entrega de hoy al cliente. Cada uno de ustedes tiene información
                parcial y confidencial — nadie tiene el panorama completo. Hablen entre ustedes (sin mostrarse
                la pantalla) para decidir qué se entrega, en qué versión, y quién será el único punto de
                contacto con el cliente.<?php if (!empty($sesion['duracion_min'])): ?> Tienen
                <strong><?= (int) $sesion['duracion_min'] ?> minutos</strong> desde que recibieron este correo.<?php endif; ?>
            </div>

            <h1><?= esc($card['title']) ?></h1>
            <p class="confidential"><?= esc($card['body']) ?></p>
            <p class="muted">No muestres esta pantalla a tu equipo. Solo tú tienes esta información.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
