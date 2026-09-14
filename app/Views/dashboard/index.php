<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dinámicas de Capacitación Virtual</title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <div class="top-nav">
        <span class="muted"><?= esc($usuario['email']) ?></span>
        <a href="<?= site_url('logout') ?>">Cerrar sesión</a>
    </div>
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>
    <div class="card">
        <h1>Dinámicas de Capacitación Virtual</h1>
        <p class="muted">Selecciona una dinámica para crear una sesión nueva o ver sus sesiones anteriores.</p>
    </div>
    <?php foreach ($dinamicas as $d): ?>
        <div class="card">
            <h2><?= esc($d['nombre']) ?></h2>
            <p><?= esc((string) $d['descripcion']) ?></p>
            <a class="btn" href="<?= site_url('sesiones/' . $d['slug']) ?>">Ver sesiones</a>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
