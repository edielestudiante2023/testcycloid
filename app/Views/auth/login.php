<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ingresar — Dinámicas de Capacitación Virtual</title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=3') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>
    <div class="card">
        <h1>Ingresar</h1>
        <?php if (!empty($error)): ?><p style="color:#c0392b;"><?= esc($error) ?></p><?php endif; ?>
        <form method="post" action="<?= site_url('login') ?>">
            <label for="email">Correo</label>
            <input type="email" id="email" name="email" required autocomplete="username">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
            <button type="submit">Entrar</button>
        </form>
    </div>
</div>
</body>
</html>
