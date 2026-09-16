<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>El Meridián — Esperando a tu equipo</title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>
    <div class="card">
        <p class="muted"><?= esc($participant['nombre']) ?> · <?= esc($participant['team']) ?> · Momento <?= (int) $momentoActual ?> de <?= (int) $totalMomentos ?></p>
        <h1>Esperando a tu equipo</h1>
        <p>Ya respondiste. En cuanto todo tu equipo termine este momento, va a aparecer lo que sigue.</p>
        <p style="font-size:1.6rem; font-weight:700; text-align:center;" id="contador"><?= (int) $respondidos ?> / <?= (int) $totalEquipo ?></p>
        <p class="muted">Aprovecha para seguir hablando con tu equipo.</p>
    </div>

    <?= view('partials/chat', ['participant' => $participant]) ?>
</div>
<script>
function revisar() {
    fetch(<?= json_encode(site_url('el-meridian/estado/' . $participant['token'] . '?momento=' . $momentoActual)) ?>)
        .then(r => r.json())
        .then(d => {
            document.getElementById('contador').textContent = d.respondidos + ' / ' + d.total;
            if (d.listo) {
                window.location.reload();
            }
        })
        .catch(() => {});
}
setInterval(revisar, 4000);
</script>
</body>
</html>
