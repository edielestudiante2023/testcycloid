<div style="font-family: system-ui, -apple-system, Arial, sans-serif; max-width: 480px; margin: 0 auto; color: #1b1f27;">
    <h1 style="font-size: 20px;">Recupera tu contraseña</h1>
    <p>Hola<?= !empty($nombre) ? ' ' . esc($nombre) : '' ?>,</p>
    <p>Recibimos una solicitud para restablecer tu contraseña en Cycloid Talent. Haz clic en el siguiente botón para crear una nueva — el enlace es válido por 60 minutos.</p>
    <p style="text-align:center; margin: 24px 0;">
        <a href="<?= esc($resetUrl) ?>" style="background:#0345BF; color:#fff; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600;">Crear nueva contraseña</a>
    </p>
    <p style="font-size: 13px; color:#5b6472;">Si el botón no funciona, copia y pega este enlace en tu navegador:<br><?= esc($resetUrl) ?></p>
    <p style="font-size: 13px; color:#5b6472;">Si no solicitaste este cambio, puedes ignorar este correo — tu contraseña actual sigue funcionando.</p>
</div>
