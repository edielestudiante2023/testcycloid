<div style="font-family: system-ui, -apple-system, Arial, sans-serif; max-width: 480px; margin: 0 auto; color: #1b1f27;">
    <h1 style="font-size: 20px;"><?= esc($sesion['dinamica_nombre']) ?> — resultados</h1>
    <p>Sesión: <strong><?= esc($sesion['cliente']) ?></strong></p>
    <p>Adjunto va el PDF con la radiografía y el análisis de cierre de este ejercicio.</p>
    <p style="text-align:center; margin: 24px 0;">
        <a href="<?= esc($resultadosUrl) ?>" style="background:#0345BF; color:#fff; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600;">Ver el detalle en línea</a>
    </p>
</div>
