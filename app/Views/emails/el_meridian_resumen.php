<div style="font-family: system-ui, -apple-system, Arial, sans-serif; max-width: 480px; margin: 0 auto; color: #1b1f27;">
    <h1 style="font-size: 20px;">El Meridián — resumen</h1>
    <p>Sesión: <strong><?= esc($sesion['cliente']) ?></strong></p>

    <?php if (count($equipos) > 1 && !empty($radiografiaGlobal['dimensiones'])): ?>
    <div style="margin: 20px 0; padding: 16px; background: #EEF2FB; border: 1px solid #0345BF; border-radius: 8px;">
        <p style="margin: 0 0 6px; font-weight: 700;">Resumen global — toda la sesión (<?= count($equipos) ?> equipos)</p>
        <table style="width:100%; border-collapse:collapse; margin-bottom:12px; font-size:13px;">
            <?php foreach ($radiografiaGlobal['dimensiones'] as $dimension => $nivel): ?>
                <tr>
                    <td style="padding:3px 0; color:#5b6472;"><?= esc($dimension) ?></td>
                    <td style="padding:3px 0; text-align:right; font-weight:600;"><?= esc($nivel) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td style="padding:3px 0; color:#5b6472;">Percepción de haber sido escuchado</td>
                <td style="padding:3px 0; text-align:right; font-weight:600;"><?= esc($radiografiaGlobal['escuchados']) ?></td>
            </tr>
        </table>
        <?php if (!empty($analisisGlobal)): ?>
        <div style="padding-top:12px; border-top:1px solid #c7d3ea; white-space:pre-line; font-size:13px; color:#1b1f27;">
            <p style="margin:0 0 6px; text-transform:uppercase; font-size:11px; letter-spacing:0.05em; color:#5b6472;">Análisis de cierre general</p><?= esc($analisisGlobal) ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php foreach ($equipos as $eq): ?>
        <div style="margin: 20px 0; padding: 16px; background: #f8fafc; border-radius: 8px;">
            <p style="margin: 0 0 6px; font-weight: 700;"><?= esc($eq['team']) ?></p>

            <?php if (!empty($eq['radiografia']['dimensiones'])): ?>
            <table style="width:100%; border-collapse:collapse; margin-bottom:12px; font-size:13px;">
                <?php foreach ($eq['radiografia']['dimensiones'] as $dimension => $nivel): ?>
                    <tr>
                        <td style="padding:3px 0; color:#5b6472;"><?= esc($dimension) ?></td>
                        <td style="padding:3px 0; text-align:right; font-weight:600;"><?= esc($nivel) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td style="padding:3px 0; color:#5b6472;">Percepción de haber sido escuchado</td>
                    <td style="padding:3px 0; text-align:right; font-weight:600;"><?= esc($eq['radiografia']['escuchados']) ?></td>
                </tr>
            </table>
            <?php endif; ?>

            <p style="margin: 0 0 6px; font-size: 14px;">
                <?= (int) $eq['positivos'] ?> de <?= (int) $eq['totalConDato'] ?> sintieron que su opinión llegó con claridad a la decisión final.
            </p>
            <p style="margin: 0; font-size: 14px; color: #5b6472;"><?= esc($eq['resumenTexto']) ?></p>

            <?php if (!empty($eq['analisisIa'])): ?>
            <div style="margin-top:14px; padding-top:14px; border-top:1px solid #e2e5eb; white-space:pre-line; font-size:13px; color:#1b1f27;">
                <p style="margin:0 0 6px; text-transform:uppercase; font-size:11px; letter-spacing:0.05em; color:#5b6472;">Análisis de cierre</p><?= esc($eq['analisisIa']) ?>
            </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <p style="text-align:center; margin: 24px 0;">
        <a href="<?= esc($resultadosUrl) ?>" style="background:#0345BF; color:#fff; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600;">Ver el detalle completo</a>
    </p>
</div>
