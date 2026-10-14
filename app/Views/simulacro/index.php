<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Simulacro de Evacuación — 21 de Octubre</title>
<meta name="description" content="Toda la información del simulacro de evacuación del 21 de octubre: video instructivo, poster para compartir y contacto directo con tus consultores.">
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=5') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>

    <div class="card">
        <h1>Simulacro de Evacuación</h1>
        <p class="muted">Toda la información que necesitas para el simulacro — míralo, descárgalo y compártelo con tu equipo o con la administración de tu copropiedad.</p>
    </div>

    <div class="reminder-box" style="margin-bottom:16px;">
        <p class="muted" style="margin-bottom:4px;">Marca tu calendario</p>
        <p class="fecha">21 de octubre — 9:45 a.m.</p>
        <p style="margin:0;">Debes estar listo <strong>5 minutos antes</strong> del simulacro para hacer el reporte.</p>
    </div>

    <div class="card">
        <h2>Video instructivo</h2>
        <div class="media-box">
            <video controls preload="metadata">
                <source src="<?= base_url('assets/simulacro/evaluacionsimulacro.mp4') ?>" type="video/mp4">
                Tu navegador no puede reproducir este video.
            </video>
        </div>
        <a class="btn-download-big" href="<?= base_url('assets/simulacro/evaluacionsimulacro.mp4') ?>" download>⬇ DESCARGAR VIDEO</a>
    </div>

    <div class="card">
        <h2>Poster del simulacro</h2>
        <p class="muted">Compártelo por WhatsApp o por el aplicativo de administración de tu copropiedad.</p>
        <div class="media-box">
            <img src="<?= base_url('assets/simulacro/poster-21-octubre.jpeg') ?>" alt="Poster Simulacro de Evacuación 21 de octubre">
        </div>
        <a class="btn-download-big" href="<?= base_url('assets/simulacro/poster-21-octubre.jpeg') ?>" download>⬇ DESCARGAR POSTER (IMAGEN)</a>
        <a class="btn" style="display:block; text-align:center; width:100%; background:var(--ink);" href="<?= base_url('assets/simulacro/poster-simulacro-2026.pdf') ?>" download>Descargar poster en PDF</a>
    </div>

    <div class="card">
        <h2>¿Dudas? Escríbele directo a tu consultor</h2>
        <div class="contact-row">
            <div class="contact-info">
                <strong>Natalia Jiménez</strong>
                <span>Consultora</span>
            </div>
            <a class="btn-whatsapp" target="_blank" rel="noopener"
               href="https://wa.me/573007898742?text=<?= rawurlencode('Hola Natalia, tengo una duda sobre el simulacro de evacuación del 21 de octubre') ?>">
                WhatsApp
            </a>
        </div>
        <div class="contact-row">
            <div class="contact-info">
                <strong>Edison Cuervo</strong>
                <span>Consultor</span>
            </div>
            <a class="btn-whatsapp" target="_blank" rel="noopener"
               href="https://wa.me/573132123799?text=<?= rawurlencode('Hola Edison, tengo una duda sobre el simulacro de evacuación del 21 de octubre') ?>">
                WhatsApp
            </a>
        </div>
        <div class="contact-row">
            <div class="contact-info">
                <strong>Servicio al cliente</strong>
                <span>Línea corporativa</span>
            </div>
            <a class="btn-whatsapp" target="_blank" rel="noopener"
               href="https://wa.me/573117919191?text=<?= rawurlencode('Hola, tengo una duda sobre el simulacro de evacuación del 21 de octubre') ?>">
                WhatsApp
            </a>
        </div>
    </div>
</div>
</body>
</html>
