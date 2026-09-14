<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Nueva sesión — <?= esc($dinamica['nombre']) ?></title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <div class="top-nav">
        <a href="<?= site_url('sesiones/' . $dinamica['slug']) ?>">← Sesiones</a>
        <a href="<?= site_url('logout') ?>">Cerrar sesión</a>
    </div>
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>
    <div class="card">
        <h1>Nueva sesión: <?= esc($dinamica['nombre']) ?></h1>
        <p class="muted">Cada sesión queda aislada: los equipos y roles de un cliente nunca se mezclan con los de otro.</p>

        <?php if (!empty($error)): ?><p style="color:#c0392b;"><?= esc($error) ?></p><?php endif; ?>

        <form method="post" action="<?= site_url('sesiones/crear') ?>">
            <input type="hidden" name="dinamica" value="<?= esc($dinamica['slug']) ?>">
            <label for="cliente">Cliente</label>
            <input type="text" id="cliente" name="cliente" required maxlength="150" placeholder="Ej. Ardurra Ingeniería">

            <label for="team_size">Tamaño de equipo</label>
            <select id="team_size" name="team_size" required>
                <option value="5">5 personas (A, B, C, D, E)</option>
                <option value="4">4 personas (A, B, C, E)</option>
            </select>

            <label for="duracion_min">Duración del ejercicio (minutos)</label>
            <input type="number" id="duracion_min" name="duracion_min" min="1" max="120" value="12" required>
            <p class="muted">El cronómetro es solo visual — el ejercicio lo cierras tú con un clic, nunca se corta solo.</p>

            <button type="submit">Generar QR de la sesión</button>
        </form>
    </div>
</div>
</body>
</html>
