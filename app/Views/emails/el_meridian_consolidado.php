<div style="font-family: system-ui, -apple-system, Arial, sans-serif; max-width: 480px; margin: 0 auto; color: #1b1f27;">
    <h1 style="font-size: 20px;">El Meridián — consolidado</h1>
    <p>Cliente: <strong><?= esc($sesion['cliente']) ?></strong></p>
    <p style="font-size: 13px; color: #5b6472;"><?= count($sesionesCliente) ?> sesiones cerradas consolidadas.</p>

    <?php if (!empty($radiografiaConsolidada['dimensiones'])): ?>
    <table style="width:100%; border-collapse:collapse; margin: 16px 0; font-size:13px;">
        <?php foreach ($radiografiaConsolidada['dimensiones'] as $dimension => $nivel): ?>
            <tr>
                <td style="padding:3px 0; color:#5b6472;"><?= esc($dimension) ?></td>
                <td style="padding:3px 0; text-align:right; font-weight:600;"><?= esc($nivel) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td style="padding:3px 0; color:#5b6472;">Percepción de haber sido escuchado</td>
            <td style="padding:3px 0; text-align:right; font-weight:600;"><?= esc($radiografiaConsolidada['escuchados']) ?></td>
        </tr>
    </table>
    <?php endif; ?>

    <?php if (!empty($analisisConsolidado)): ?>
    <div style="margin-top:14px; padding-top:14px; border-top:1px solid #e2e5eb; white-space:pre-line; font-size:13px; color:#1b1f27;">
        <p style="margin:0 0 6px; text-transform:uppercase; font-size:11px; letter-spacing:0.05em; color:#5b6472;">Análisis de evolución en el tiempo</p><?= esc($analisisConsolidado) ?>
    </div>
    <?php endif; ?>

    <p style="text-align:center; margin: 24px 0;">
        <a href="<?= esc($consolidadoUrl) ?>" style="background:#0345BF; color:#fff; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600;">Ver el detalle completo</a>
    </p>
</div>
