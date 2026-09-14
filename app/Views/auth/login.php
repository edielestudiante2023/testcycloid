<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ingresar — Dinámicas de Capacitación Virtual</title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">

<!-- PWA -->
<meta name="theme-color" content="#0345BF">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Cycloid Talent">
<link rel="manifest" href="<?= base_url('manifest_login.json') ?>">
<link rel="apple-touch-icon" href="<?= base_url('assets/icons/icon-192.png') ?>">
</head>
<body>
<div class="wrap">
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>
    <div class="card">
        <h1>Ingresar</h1>
        <?php if (!empty($mensaje)): ?><p style="color:#1a7f37;"><?= esc($mensaje) ?></p><?php endif; ?>
        <?php if (!empty($error)): ?><p style="color:#c0392b;"><?= esc($error) ?></p><?php endif; ?>
        <form method="post" action="<?= site_url('login') ?>">
            <label for="email">Correo</label>
            <input type="email" id="email" name="email" required autocomplete="username">
            <label for="password">Contraseña</label>
            <div class="password-field">
                <input type="password" id="password" name="password" required autocomplete="current-password">
                <button type="button" class="password-toggle" data-target="password" aria-label="Mostrar contraseña">👁</button>
            </div>
            <p style="margin: 8px 0 0;"><a href="<?= site_url('login/olvide') ?>" class="muted">¿Olvidaste tu contraseña?</a></p>
            <button type="submit">Entrar</button>
        </form>

        <div class="pwa-install-section" id="pwaInstallSection">
            <img src="<?= base_url('assets/icons/icon-192.png') ?>" alt="App" class="pwa-install-icon">
            <div class="pwa-install-info">
                <h5>Instala la app</h5>
                <p>Acceso rápido desde la pantalla de inicio de tu dispositivo.</p>
                <button type="button" class="btn-pwa-install" id="pwaInstallBtn">
                    <span id="pwaInstallBtnText">Descargar app</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal iOS -->
<div class="pwa-ios-modal" id="pwaIosModal">
    <div class="pwa-ios-modal-content">
        <h4>Cómo instalar en iPhone/iPad</h4>
        <ol>
            <li>Toca el botón <strong>Compartir</strong> en la barra de Safari.</li>
            <li>Elige <strong>"Añadir a pantalla de inicio"</strong>.</li>
            <li>Confirma con <strong>Añadir</strong>.</li>
        </ol>
        <button type="button" class="btn-close-ios" id="pwaIosModalClose">Entendido</button>
    </div>
</div>

<script>
document.querySelectorAll('.password-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var input = document.getElementById(btn.dataset.target);
        var showing = input.type === 'text';
        input.type = showing ? 'password' : 'text';
        btn.setAttribute('aria-label', showing ? 'Mostrar contraseña' : 'Ocultar contraseña');
        btn.classList.toggle('is-visible', !showing);
    });
});
</script>
<script>
(function() {
    var deferredPrompt = null;
    var section = document.getElementById('pwaInstallSection');
    var btn = document.getElementById('pwaInstallBtn');
    var btnText = document.getElementById('pwaInstallBtnText');
    var iosModal = document.getElementById('pwaIosModal');
    var iosClose = document.getElementById('pwaIosModalClose');

    var ua = window.navigator.userAgent;
    var isIOS = /iPad|iPhone|iPod/.test(ua) && !window.MSStream;
    var isStandalone = window.matchMedia('(display-mode: standalone)').matches
                    || window.navigator.standalone === true;

    if (isStandalone) { return; }

    if (isIOS) {
        section.classList.add('visible');
        btnText.textContent = 'Cómo instalar';
        btn.addEventListener('click', function() { iosModal.classList.add('visible'); });
        iosClose.addEventListener('click', function() { iosModal.classList.remove('visible'); });
        iosModal.addEventListener('click', function(e) { if (e.target === iosModal) iosModal.classList.remove('visible'); });
        return;
    }

    window.addEventListener('beforeinstallprompt', function(e) {
        e.preventDefault();
        deferredPrompt = e;
        section.classList.add('visible');
    });

    btn.addEventListener('click', function() {
        if (!deferredPrompt) return;
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then(function(choice) {
            if (choice.outcome === 'accepted') section.classList.remove('visible');
            deferredPrompt = null;
        });
    });

    window.addEventListener('appinstalled', function() {
        section.classList.remove('visible');
        deferredPrompt = null;
    });
})();

if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('<?= base_url('sw_login.js') ?>', {
            scope: '/',
            updateViaCache: 'none'
        }).catch(function(err) { console.log('SW login error:', err); });
    });
}
</script>
</body>
</html>
