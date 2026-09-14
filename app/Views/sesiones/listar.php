<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sesiones — <?= esc($dinamica['nombre']) ?></title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=3') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <div class="top-nav">
        <a href="<?= site_url('/') ?>">← Dinámicas</a>
        <a href="<?= site_url('logout') ?>">Cerrar sesión</a>
    </div>
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>
    <div class="card">
        <h1><?= esc($dinamica['nombre']) ?></h1>
        <p class="muted"><?= esc((string) $dinamica['descripcion']) ?></p>
        <a class="btn" href="<?= site_url('sesiones/nueva/' . $dinamica['slug']) ?>">+ Nueva sesión (nuevo cliente)</a>
    </div>

    <div class="card">
        <h2>Sesiones anteriores</h2>
        <?php if (empty($sesiones)): ?>
            <p class="muted">Todavía no hay sesiones de esta dinámica.</p>
        <?php else: ?>
            <table>
                <thead><tr><th>Cliente</th><th>Fecha</th><th>Equipo</th><th>Participantes</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($sesiones as $s): ?>
                    <tr>
                        <td><?= esc($s['cliente']) ?></td>
                        <td><?= esc(substr((string) $s['created_at'], 0, 16)) ?></td>
                        <td><?= (int) $s['team_size'] ?> pers. / <?= (int) $s['duracion_min'] ?> min</td>
                        <td><?= (int) $s['total_participantes'] ?></td>
                        <td><?= esc($s['estado']) ?></td>
                        <td>
                            <a href="<?= site_url('sesiones/qr/' . $s['token']) ?>">QR</a> ·
                            <a href="<?= site_url('sesiones/resultados/' . $s['token']) ?>">Resultados</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
