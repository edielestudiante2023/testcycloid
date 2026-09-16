<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registro — Volver a Casa</title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>
    <div class="card">
        <h1>Volver a Casa</h1>
        <p class="muted">Sesión: <?= esc($sesion['cliente']) ?></p>
        <p>Regístrate para participar. En unos minutos recibirás tu rol por correo, en tu email corporativo y personal.</p>

        <?php if (!empty($error)): ?>
            <p style="color:#c0392b;"><?= esc($error) ?></p>
        <?php endif; ?>

        <form method="post" action="<?= site_url('volver-a-casa/asignar') ?>">
            <input type="hidden" name="s" value="<?= esc($sesionToken) ?>">

            <label for="nombre">Nombre completo</label>
            <input type="text" id="nombre" name="nombre" required maxlength="120" placeholder="Ej. María Pérez">

            <label for="documento">Documento de identidad</label>
            <input type="text" id="documento" name="documento" required maxlength="40" placeholder="Ej. 1030602799">

            <label for="cargo">Cargo</label>
            <input type="text" id="cargo" name="cargo" required maxlength="120" placeholder="Ej. Analista de proyectos">

            <label for="email_corporativo">Email corporativo</label>
            <input type="email" id="email_corporativo" name="email_corporativo" required maxlength="190" placeholder="nombre@empresa.com">

            <label for="email_personal">Email personal</label>
            <input type="email" id="email_personal" name="email_personal" maxlength="190" placeholder="nombre@gmail.com">

            <label for="whatsapp">Número de WhatsApp</label>
            <input type="text" id="whatsapp" name="whatsapp" maxlength="30" placeholder="Ej. 3001234567">

            <label style="display:flex; align-items:center; gap:8px; font-weight:400; margin-top:16px;">
                <input type="checkbox" name="tiene_personal_a_cargo" value="1" style="width:auto;">
                ¿Tienes personal a cargo en tu rol actual?
            </label>

            <div class="confidential" style="margin-top:16px; font-size:0.85rem;">
                <strong>Autorización de tratamiento de datos personales.</strong> Autorizo a Cycloid Talent a
                recolectar y tratar los datos personales suministrados en este formulario (nombre, documento,
                cargo, correos electrónicos y número de WhatsApp), con la única finalidad de gestionar mi
                participación en esta actividad de formación y enviarme la información asociada al ejercicio.
                El tratamiento se realiza conforme a la Ley 1581 de 2012 (Habeas Data) y sus decretos
                reglamentarios. Puedo ejercer mis derechos de conocimiento, actualización, rectificación y
                supresión de mis datos escribiendo a notificacion.cycloidtalent@cycloidtalent.com.
            </div>
            <label style="display:flex; align-items:center; gap:8px; font-weight:600; margin-top:12px;">
                <input type="checkbox" name="autorizo_datos" value="1" required style="width:auto;">
                Acepto el tratamiento de mis datos personales
            </label>

            <button type="submit">Registrarme</button>
        </form>
    </div>
</div>
</body>
</html>
