<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>QR — <?= esc($sesion['cliente']) ?></title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>
<div class="wrap">
    <div class="top-nav">
        <a href="<?= site_url('sesiones/' . $sesion['dinamica_slug']) ?>">← Sesiones</a>
        <a href="<?= site_url('sesiones/resultados/' . $sesion['token']) ?>">Ver resultados</a>
    </div>
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>
    <div class="card">
        <h1><?= esc($sesion['dinamica_nombre']) ?></h1>
        <p class="muted">Sesión para: <strong><?= esc($sesion['cliente']) ?></strong> — equipos de <?= (int) $sesion['team_size'] ?>, <?= (int) $sesion['duracion_min'] ?> min</p>
        <div id="qrcode"></div>
        <p class="muted" style="text-align:center; word-break: break-all;"><?= esc($registroUrl) ?></p>
    </div>

    <?php if (empty($sesion['iniciada_at'])): ?>
        <div class="card">
            <h2>Registrados: <span id="contador"><?= (int) $count ?></span></h2>
            <p class="muted">Se actualiza solo cada 5 segundos. También puedes forzarlo.</p>
            <button type="button" id="btnActualizar">Actualizar ahora</button>

            <form method="post" action="<?= site_url('sesiones/enviar/' . $sesion['token']) ?>"
                  onsubmit="return confirm('¿Enviar el taller por email a todos los registrados e iniciar el ejercicio? Ya no se podrán agregar más participantes a los equipos.');">
                <button type="submit" style="margin-top:20px;">Enviar taller vía email</button>
            </form>
        </div>
        <script>
        function actualizarContador() {
            fetch(<?= json_encode(site_url('sesiones/contador/' . $sesion['token'])) ?>)
                .then(r => r.json())
                .then(d => { document.getElementById('contador').textContent = d.count; })
                .catch(() => {});
        }
        document.getElementById('btnActualizar').addEventListener('click', actualizarContador);
        setInterval(actualizarContador, 5000);
        </script>
    <?php else: ?>
        <div class="card">
            <h2>Ejercicio en curso</h2>
            <p class="muted">El correo ya se envió. El cronómetro es solo una referencia visual — tú decides cuándo cerrar.</p>
            <p style="font-size:2.5rem; font-weight:700; text-align:center;" id="cronometro">--:--</p>
            <form method="post" action="<?= site_url('sesiones/cerrar/' . $sesion['token']) ?>"
                  onsubmit="return confirm('¿Cerrar el ejercicio? Pasarás a la vista de resultados/debrief.');">
                <button type="submit" class="btn-danger">Cerrar ejercicio</button>
            </form>
        </div>
        <script>
        var inicio = new Date(<?= json_encode(str_replace(' ', 'T', $sesion['iniciada_at'])) ?>).getTime();
        var duracionMs = <?= (int) $sesion['duracion_min'] ?> * 60 * 1000;
        var el = document.getElementById('cronometro');
        function tick() {
            var restante = Math.round((inicio + duracionMs - Date.now()) / 1000);
            var signo = restante < 0 ? '-' : '';
            restante = Math.abs(restante);
            var m = Math.floor(restante / 60);
            var s = restante % 60;
            el.textContent = signo + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
        }
        tick();
        setInterval(tick, 1000);
        </script>
    <?php endif; ?>
</div>
<script>
new QRCode(document.getElementById("qrcode"), {
    text: <?= json_encode($registroUrl) ?>,
    width: 220,
    height: 220
});
</script>
</body>
</html>
