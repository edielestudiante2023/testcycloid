<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Recuperar contraseña — Dinámicas de Capacitación Virtual</title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>
    <div class="card">
        <h1>Recuperar contraseña</h1>

        <?php if (!empty($enviado)): ?>
            <p>Si el correo está registrado, te enviamos un enlace para restablecer tu contraseña. Revisa tu bandeja de entrada (y spam).</p>
            <a class="btn" href="<?= site_url('login') ?>">Volver a ingresar</a>
        <?php else: ?>
            <?php if (!empty($error)): ?><p style="color:#c0392b;"><?= esc($error) ?></p><?php endif; ?>
            <p class="muted">Escribe tu correo y te enviaremos un enlace para crear una nueva contraseña.</p>
            <form method="post" action="<?= site_url('login/olvide') ?>">
                <label for="email">Correo</label>
                <input type="email" id="email" name="email" required autocomplete="username">
                <button type="submit">Enviar enlace</button>
            </form>
            <p style="margin-top:16px;"><a href="<?= site_url('login') ?>" class="muted">← Volver a ingresar</a></p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
