<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Resultados — <?= esc($sesion['cliente']) ?></title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <div class="top-nav">
        <a href="<?= site_url('sesiones/' . $sesion['dinamica_slug']) ?>">← Sesiones</a>
        <a href="<?= site_url('sesiones/qr/' . $sesion['token']) ?>">Ver QR</a>
    </div>
    <div class="brand-header"><img src="<?= base_url('assets/img/cycloid-logo-azul.png?v=3') ?>" alt="Cycloid Talent"></div>

    <div class="card">
        <h1><?= esc($sesion['dinamica_nombre']) ?></h1>
        <p class="muted">Sesión para: <strong><?= esc($sesion['cliente']) ?></strong> — estado: <?= esc($sesion['estado']) ?></p>
    </div>

    <div class="card">
        <h2>Equipos</h2>
        <?php if (empty($participants)): ?>
            <p class="muted">Todavía no hay registros en esta sesión.</p>
        <?php endif; ?>
        <?php foreach ($byTeam as $team => $rows): ?>
            <h2><?= esc((string) $team) ?></h2>
            <table>
                <thead><tr><th>Nombre</th><th>Cargo</th><th>Rol</th><th>Personal a cargo</th><th>¿Mostró liderazgo?</th><th>Respuesta esperada</th></tr></thead>
                <tbody>
                <?php foreach ($rows as $p): ?>
                    <tr>
                        <td><?= esc($p['nombre']) ?></td>
                        <td><?= esc($p['cargo']) ?></td>
                        <td><?php if ($p['role']): ?><span class="role-badge"><?= esc($p['role']) ?></span><?php else: ?><span class="muted">pendiente</span><?php endif; ?></td>
                        <td><?= $p['tiene_personal_a_cargo'] ? 'Sí' : 'No' ?></td>
                        <td>
                            <form method="post" action="<?= site_url('sesiones/liderazgo/' . $sesion['token']) ?>" style="margin:0;">
                                <input type="hidden" name="participant_id" value="<?= (int) $p['id'] ?>">
                                <select name="mostro" onchange="this.form.submit()" style="width:auto; padding:4px;">
                                    <option value="" <?= $p['mostro_liderazgo'] === null ? 'selected' : '' ?>>—</option>
                                    <option value="1" <?= $p['mostro_liderazgo'] == 1 ? 'selected' : '' ?>>Sí</option>
                                    <option value="0" <?= $p['mostro_liderazgo'] === 0 || $p['mostro_liderazgo'] === '0' ? 'selected' : '' ?>>No</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <?php if ($p['role']): ?>
                            <details class="answer-toggle">
                                <summary>Ver</summary>
                                <div class="answer-box"><?= esc($answers[$p['role']] ?? '') ?></div>
                            </details>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>

        <?php if ($sesion['estado'] !== 'cerrada'): ?>
        <form method="post" action="<?= site_url('sesiones/cerrar/' . $sesion['token']) ?>"
              onsubmit="return confirm('¿Cerrar el ejercicio de esta sesión?');">
            <button type="submit" class="btn-danger">Cerrar ejercicio</button>
        </form>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Minuto 6 — Actualización del cliente (lee en voz alta y muestra la diapositiva)</h2>
        <p class="confidential">⚠️ ACTUALIZACIÓN DEL CLIENTE

La entrega debe salir 30 minutos antes de lo previsto.

El Entregable C deja de ser obligatorio hoy.

El cliente solicita confirmación inmediata de:
1. Qué recibirá hoy.
2. Quién será su único punto de contacto.

Reorganicen su plan.</p>
        <p class="muted">Cuando queden 3 minutos, solo di en voz alta: "Les quedan tres minutos. No habrá extensión." — nada más.</p>
    </div>

    <div class="card">
        <h2>Solución correcta (para el cierre reflexivo)</h2>
        <ul>
            <li>Prioridad: B es indispensable.</li>
            <li>Versión: V6.</li>
            <li>C: ya no se entrega (tras la actualización del cliente).</li>
            <li>Soporte remoto: debe dejar C y apoyar B.</li>
            <li>Calidad: valida B sobre V6.</li>
            <li>Punto de contacto: el equipo debe elegir uno.</li>
            <li>A: depende de capacidad; puede aplazarse justificadamente.</li>
        </ul>
        <p><strong>Mensaje sugerido al cliente:</strong> "Confirmamos para hoy la entrega del Entregable B, elaborado sobre la versión V6. El Entregable C se reprogramará de acuerdo con su actualización. [Nombre] será el punto de contacto para esta entrega."</p>
    </div>

    <div class="card">
        <h2>Enlaces directos por rol</h2>
        <table>
            <thead><tr><th>Equipo</th><th>Nombre</th><th>Rol</th><th>Enlace</th></tr></thead>
            <tbody>
            <?php foreach ($participants as $p): if (!$p['role']) continue; ?>
                <tr>
                    <td><?= esc($p['team']) ?></td>
                    <td><?= esc($p['nombre']) ?></td>
                    <td><span class="role-badge"><?= esc($p['role']) ?></span></td>
                    <td><a href="<?= esc($rolBaseUrl . $p['token']) ?>" target="_blank">abrir</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
