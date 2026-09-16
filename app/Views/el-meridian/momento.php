<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>El Meridián — Momento <?= (int) $momentoActual ?></title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>
    <div class="card">
        <p class="muted"><?= esc($participant['nombre']) ?> · <?= esc($participant['team']) ?> · Momento <?= (int) $momentoActual ?> de <?= (int) $totalMomentos ?></p>

        <p class="confidential"><?= esc($momentoData['narrativa']) ?></p>

        <form method="post" action="<?= site_url('el-meridian/momento/' . $participant['token']) ?>">
            <input type="hidden" name="momento" value="<?= (int) $momentoActual ?>">

            <label style="margin-top:20px;"><?= esc($momentoData['pregunta']) ?></label>
            <?php foreach ($momentoData['opciones'] as $valor => $etiqueta): ?>
                <label style="display:flex; align-items:center; gap:8px; font-weight:400; margin:10px 0;">
                    <input type="radio" name="respuesta" value="<?= esc($valor) ?>" required style="width:auto;">
                    <?= esc($etiqueta) ?>
                </label>
            <?php endforeach; ?>

            <button type="submit">Responder</button>
        </form>
    </div>

    <?= view('partials/chat', ['participant' => $participant]) ?>
</div>
</body>
</html>
