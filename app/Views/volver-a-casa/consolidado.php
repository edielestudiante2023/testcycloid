<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Consolidado — <?= esc($sesion['cliente']) ?></title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <div class="top-nav">
        <a href="<?= site_url('sesiones/' . $sesion['dinamica_slug']) ?>">← Sesiones</a>
        <a href="<?= site_url('sesiones/resultados/' . $sesion['token']) ?>">Ver esta sesión</a>
    </div>
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>

    <div class="card">
        <h1>Volver a Casa — consolidado</h1>
        <p class="muted">Cliente: <strong><?= esc($sesion['cliente']) ?></strong></p>
    </div>

    <?php if (count($sesionesCliente) < 2): ?>
        <div class="card">
            <p class="muted">Este cliente todavía no tiene suficientes sesiones cerradas para consolidar
            (se necesitan al menos 2). Hoy tiene <?= count($sesionesCliente) ?>.</p>
        </div>
    <?php else: ?>
        <div class="card">
            <h2>Sesiones incluidas (<?= count($sesionesCliente) ?>)</h2>
            <table>
                <thead><tr><th>Fecha de cierre</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($porSesion as $s): ?>
                    <tr>
                        <td><?= esc($s['fecha']) ?></td>
                        <td><a href="<?= site_url('sesiones/resultados/' . $s['token']) ?>">Ver resultados</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="card" style="border-color: var(--brand);">
            <h2>Radiografía consolidada — toda la organización, a través del tiempo</h2>
            <table style="margin-bottom:20px;">
                <thead><tr><th>Dimensión</th><th>Nivel</th></tr></thead>
                <tbody>
                <?php foreach ($radiografiaConsolidada['dimensiones'] as $dimension => $nivel): ?>
                    <tr><td><?= esc($dimension) ?></td><td><span class="role-badge"><?= esc($nivel) ?></span></td></tr>
                <?php endforeach; ?>
                    <tr><td>Percepción de haber sido escuchado</td><td><span class="role-badge"><?= esc($radiografiaConsolidada['escuchados']) ?></span></td></tr>
                </tbody>
            </table>

            <?php if (!empty($analisisConsolidado)): ?>
            <div class="confidential" style="background:#EEF2FB; border-style:solid; margin-bottom:20px; white-space:pre-line;">
                <p class="muted" style="margin:0 0 8px; text-transform:uppercase; font-size:0.75rem; letter-spacing:0.05em;">Análisis de evolución en el tiempo</p><?= esc($analisisConsolidado) ?>
            </div>
            <?php endif; ?>

            <?php if ($correoEnviado): ?>
                <p style="color:#1a7f37;">Correo enviado.</p>
            <?php endif; ?>

            <form method="post" action="<?= site_url('sesiones/consolidado-enviar/' . $sesion['token']) ?>"
                  onsubmit="return confirm('¿Generar el análisis con IA y enviarlo por correo? Puede tardar unos segundos.');">
                <button type="submit"><?= !empty($analisisConsolidado) ? 'Regenerar y reenviar por correo' : 'Generar análisis y enviar por correo' ?></button>
            </form>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
