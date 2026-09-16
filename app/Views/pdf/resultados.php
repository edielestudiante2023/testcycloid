<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1b1f27; }
    h1 { font-size: 20px; margin: 0 0 4px; }
    h2 { font-size: 15px; margin: 18px 0 8px; }
    p { margin: 4px 0; }
    .muted { color: #5b6472; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    td { padding: 4px 0; border-bottom: 1px solid #e2e5eb; }
    td.valor { text-align: right; font-weight: bold; }
    .bloque { margin-bottom: 22px; padding-bottom: 10px; border-bottom: 2px solid #e2e5eb; }
    .analisis { background: #EEF2FB; border: 1px solid #c7d3ea; border-radius: 6px; padding: 10px; margin-top: 8px; white-space: pre-line; font-size: 11px; }
    .analisis-titulo { text-transform: uppercase; font-size: 9px; letter-spacing: 0.05em; color: #5b6472; margin: 0 0 6px; }
    .footer { margin-top: 24px; font-size: 9px; color: #9aa2ad; }
</style>
</head>
<body>
    <h1><?= esc($sesion['dinamica_nombre']) ?></h1>
    <p class="muted">Sesión para: <strong><?= esc($sesion['cliente']) ?></strong> — cerrada el <?= esc(substr((string) ($sesion['cerrada_at'] ?? ''), 0, 16)) ?></p>

    <?php if (count($equipos) > 1 && !empty($radiografiaGlobal['dimensiones'])): ?>
    <div class="bloque">
        <h2>Resumen global — toda la sesión (<?= count($equipos) ?> equipos)</h2>
        <table>
            <?php foreach ($radiografiaGlobal['dimensiones'] as $dimension => $nivel): ?>
                <tr><td><?= esc($dimension) ?></td><td class="valor"><?= esc($nivel) ?></td></tr>
            <?php endforeach; ?>
            <tr><td>Percepción de haber sido escuchado</td><td class="valor"><?= esc($radiografiaGlobal['escuchados']) ?></td></tr>
        </table>
        <?php if (!empty($analisisGlobal)): ?>
        <div class="analisis">
            <p class="analisis-titulo">Análisis de cierre general</p>
            <?= esc($analisisGlobal) ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php foreach ($equipos as $eq): ?>
    <div class="bloque">
        <h2><?= esc($eq['team']) ?></h2>

        <?php if (!empty($eq['radiografia']['dimensiones'])): ?>
        <table>
            <?php foreach ($eq['radiografia']['dimensiones'] as $dimension => $nivel): ?>
                <tr><td><?= esc($dimension) ?></td><td class="valor"><?= esc($nivel) ?></td></tr>
            <?php endforeach; ?>
            <tr><td>Percepción de haber sido escuchado</td><td class="valor"><?= esc($eq['radiografia']['escuchados']) ?></td></tr>
        </table>
        <?php endif; ?>

        <p><strong><?= (int) $eq['positivos'] ?> de <?= (int) $eq['totalConDato'] ?></strong> sintieron que su opinión llegó con claridad a la decisión final</p>
        <p class="muted"><?= esc($eq['resumenTexto']) ?></p>

        <?php if (!empty($eq['analisisIa'])): ?>
        <div class="analisis">
            <p class="analisis-titulo">Análisis de cierre</p>
            <?= esc($eq['analisisIa']) ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <p class="footer">Generado el <?= esc(date('Y-m-d H:i')) ?> — Cycloid Talent. Este documento es de uso interno del equipo y del facilitador.</p>
</body>
</html>
