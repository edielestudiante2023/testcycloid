<div style="font-family: system-ui, -apple-system, Arial, sans-serif; max-width: 480px; margin: 0 auto; color: #1b1f27;">
    <h1 style="font-size: 20px;">Liderazgo y Comunicación</h1>
    <p>Hola <?= esc($nombre) ?>,</p>
    <p>El ejercicio está por comenzar. Haz clic en el siguiente botón para ver tu rol — es información confidencial, solo para ti.</p>
    <p style="text-align:center; margin: 24px 0;">
        <a href="<?= esc($rolUrl) ?>" style="background:#0345BF; color:#fff; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600;">Ver mi rol</a>
    </p>
    <p style="font-size: 13px; color:#5b6472;">Si el botón no funciona, copia y pega este enlace en tu navegador:<br><?= esc($rolUrl) ?></p>
</div>
