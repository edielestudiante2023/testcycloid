<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>QR — <?= esc($sesion['cliente']) ?></title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.11/css/jquery.dataTables.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.11/js/jquery.dataTables.min.js"></script>
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
    <?php elseif ($sesion['estado'] === 'cerrada'): ?>
        <div class="card">
            <h2>Ejercicio cerrado</h2>
            <p class="muted">Este ejercicio ya se cerró<?= !empty($sesion['cerrada_at']) ? ' el ' . esc(substr($sesion['cerrada_at'], 0, 16)) : '' ?>. No se puede volver a enviar ni a cerrar.</p>
            <a class="btn" href="<?= site_url('sesiones/resultados/' . $sesion['token']) ?>">Ver resultados</a>
        </div>
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
        var inicio = new Date(<?= json_encode(str_replace(' ', 'T', $sesion['iniciada_at']) . 'Z') ?>).getTime();
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

        <?php if (!empty($progresoEquipos)): ?>
        <div class="card">
            <h2>Progreso por equipo</h2>
            <table>
                <thead><tr><th>Equipo</th><th>Momento</th><th>Respondieron</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($progresoEquipos as $p): ?>
                    <tr>
                        <td><?= esc($p['team']) ?></td>
                        <td><?= $p['terminado'] ? 'Terminado' : ($p['momentoActual'] . ' de ' . $p['totalMomentos']) ?></td>
                        <td><?= (int) $p['respondidos'] ?> / <?= (int) $p['totalEquipo'] ?></td>
                        <td>
                            <?php if (!$p['terminado'] && $p['respondidos'] < $p['totalEquipo']): ?>
                            <form method="post" action="<?= site_url('sesiones/forzar-avance/' . $sesion['token']) ?>"
                                  onsubmit="return confirm('¿Forzar el avance del <?= esc($p['team'], 'js') ?>? A quien no haya respondido se le va a registrar una respuesta vacía en este momento.');" style="margin:0;">
                                <input type="hidden" name="team" value="<?= esc($p['team']) ?>">
                                <input type="hidden" name="momento" value="<?= (int) $p['momentoActual'] ?>">
                                <button type="submit" style="margin:0; padding:6px 12px; font-size:0.85rem;">Forzar avance</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <script>setInterval(function () { window.location.reload(); }, 8000);</script>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (!empty($participants)): ?>
    <div class="card">
        <h2>Participantes registrados</h2>
        <table id="tablaParticipantes" class="display" style="width:100%;">
            <thead>
                <tr><th>Nombre</th><th>Documento</th><th>Cargo</th><th>Equipo</th><th>Rol</th></tr>
            </thead>
            <tbody>
            <?php foreach ($participants as $p): ?>
                <tr>
                    <td><?= esc($p['nombre']) ?></td>
                    <td><?= esc($p['documento']) ?></td>
                    <td><?= esc($p['cargo']) ?></td>
                    <td><?= esc($p['team'] ?? 'Sin asignar') ?></td>
                    <td><?= esc($p['role'] ?? '—') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
    $(document).ready(function () {
        $('#tablaParticipantes').DataTable({
            language: {
                search: 'Buscar:',
                lengthMenu: 'Mostrar _MENU_ participantes',
                info: 'Mostrando _START_ a _END_ de _TOTAL_',
                paginate: { previous: 'Anterior', next: 'Siguiente' },
                zeroRecords: 'Sin resultados',
                emptyTable: 'Todavía no hay participantes registrados'
            }
        });
    });
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
