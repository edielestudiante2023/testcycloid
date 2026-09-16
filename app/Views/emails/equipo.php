<div style="font-family: system-ui, -apple-system, Arial, sans-serif; max-width: 480px; margin: 0 auto; color: #1b1f27;">
    <h1 style="font-size: 20px;"><?= esc($dinamicaNombre) ?> — conoce a tu equipo</h1>
    <p>Sesión: <strong><?= esc($cliente) ?></strong></p>
    <p>Quedaste en el <strong><?= esc($team) ?></strong>, con estas personas:</p>

    <table style="width:100%; border-collapse:collapse; margin: 16px 0; font-size:14px;">
        <?php foreach ($miembros as $m): ?>
            <tr>
                <td style="padding:6px 0; border-bottom:1px solid #e2e5eb;"><?= esc($m['nombre']) ?></td>
                <td style="padding:6px 0; border-bottom:1px solid #e2e5eb; text-align:right; color:#5b6472;"><?= esc($m['rol']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p style="font-size: 13px; color: #5b6472;">A cada quien le llegó, por separado, un correo con el enlace a su propio rol — ese es confidencial, no lo compartan en este hilo.</p>

    <p>Pueden usar este mismo correo para hablar entre ustedes — <strong>respondan a todos</strong> para organizarse antes de empezar.</p>
</div>
