<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Nueva contraseña — Dinámicas de Capacitación Virtual</title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>
    <div class="card">
        <h1>Nueva contraseña</h1>

        <?php if (empty($valido)): ?>
            <p style="color:#c0392b;">Este enlace no es válido o ya expiró. Solicita uno nuevo.</p>
            <a class="btn" href="<?= site_url('login/olvide') ?>">Solicitar enlace nuevo</a>
        <?php else: ?>
            <?php if (!empty($error)): ?><p style="color:#c0392b;"><?= esc($error) ?></p><?php endif; ?>
            <form method="post" action="<?= site_url('login/restablecer/' . $token) ?>">
                <label for="password">Nueva contraseña</label>
                <div class="password-field">
                    <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password">
                    <button type="button" class="password-toggle" data-target="password" aria-label="Mostrar contraseña">👁</button>
                </div>
                <label for="password_confirm">Confirmar contraseña</label>
                <div class="password-field">
                    <input type="password" id="password_confirm" name="password_confirm" required minlength="8" autocomplete="new-password">
                    <button type="button" class="password-toggle" data-target="password_confirm" aria-label="Mostrar contraseña">👁</button>
                </div>
                <p class="muted">Mínimo 8 caracteres.</p>
                <button type="submit">Guardar contraseña</button>
            </form>
        <?php endif; ?>
    </div>
</div>
<script>
document.querySelectorAll('.password-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var input = document.getElementById(btn.dataset.target);
        var showing = input.type === 'text';
        input.type = showing ? 'password' : 'text';
        btn.setAttribute('aria-label', showing ? 'Mostrar contraseña' : 'Ocultar contraseña');
        btn.classList.toggle('is-visible', !showing);
    });
});
</script>
</body>
</html>
