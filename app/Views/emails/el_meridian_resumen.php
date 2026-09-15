<div style="font-family: system-ui, -apple-system, Arial, sans-serif; max-width: 480px; margin: 0 auto; color: #1b1f27;">
    <h1 style="font-size: 20px;">El Meridián — resumen</h1>
    <p>Sesión: <strong><?= esc($sesion['cliente']) ?></strong></p>

    <?php foreach ($equipos as $eq): ?>
        <div style="margin: 20px 0; padding: 16px; background: #f8fafc; border-radius: 8px;">
            <p style="margin: 0 0 6px; font-weight: 700;"><?= esc($eq['team']) ?></p>
            <p style="margin: 0 0 6px; font-size: 14px;">
                <?= (int) $eq['positivos'] ?> de <?= (int) $eq['totalConDato'] ?> sintieron que su opinión llegó con claridad a la decisión final.
            </p>
            <p style="margin: 0; font-size: 14px; color: #5b6472;"><?= esc($eq['resumenTexto']) ?></p>

            <?php if (!empty($eq['analisisIa'])): ?>
            <div style="margin-top:14px; padding-top:14px; border-top:1px solid #e2e5eb; white-space:pre-line; font-size:13px; color:#1b1f27;">
                <p style="margin:0 0 6px; text-transform:uppercase; font-size:11px; letter-spacing:0.05em; color:#5b6472;">Análisis para el debrief</p><?= esc($eq['analisisIa']) ?>
            </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <p style="text-align:center; margin: 24px 0;">
        <a href="<?= esc($resultadosUrl) ?>" style="background:#0345BF; color:#fff; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600;">Ver el detalle completo</a>
    </p>
</div>
