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
        <?php if (!empty($sesionesRecientes)): ?>
        <div class="card">
            <h2>Reciclar participantes de otra sesión</h2>
            <p class="muted">Si es el mismo grupo de personas que ya se registró en otro ejercicio, cópialos aquí en vez de pedirles que llenen el formulario de nuevo. Equipo y rol se vuelven a asignar desde cero para esta sesión.</p>
            <?php if ($reciclados > 0): ?>
                <p style="color:#1a7f37;"><?= (int) $reciclados ?> participante(s) copiado(s).</p>
            <?php endif; ?>
            <form method="post" action="<?= site_url('sesiones/reciclar/' . $sesion['token']) ?>">
                <select name="sesion_origen" required>
                    <option value="">Selecciona una sesión…</option>
                    <?php foreach ($sesionesRecientes as $s): ?>
                        <option value="<?= esc($s['token']) ?>">
                            <?= esc($s['dinamica_nombre']) ?> — <?= esc($s['cliente']) ?> — <?= esc(substr((string) $s['created_at'], 0, 16)) ?> (<?= (int) $s['total_participantes'] ?> personas)
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" style="margin-top:12px;">Copiar participantes aquí</button>
            </form>
        </div>
        <?php endif; ?>

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
                  onsubmit="return confirm('¿Cerrar el ejercicio? Pasarás a la vista de resultados.');">
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
            <table id="tablaProgreso">
                <thead><tr><th>Equipo</th><th>Momento</th><th>Respondieron</th><th></th></tr></thead>
                <tbody id="progresoBody"></tbody>
            </table>
        </div>
        <script>
        var progresoUrl = <?= json_encode(site_url('sesiones/progreso/' . $sesion['token'])) ?>;
        var forzarAvanceUrl = <?= json_encode(site_url('sesiones/forzar-avance/' . $sesion['token'])) ?>;

        function celda(texto) {
            var td = document.createElement('td');
            td.textContent = texto;
            return td;
        }

        function pintarFila(eq, abiertos) {
            var tr = document.createElement('tr');

            var tdEquipo = document.createElement('td');
            tdEquipo.appendChild(document.createTextNode(eq.team));
            if (eq.pendientes && eq.pendientes.length > 0) {
                var det = document.createElement('details');
                det.style.marginTop = '4px';
                if (abiertos.indexOf(eq.team) !== -1) {
                    det.open = true;
                }
                var sum = document.createElement('summary');
                sum.style.cursor = 'pointer';
                sum.style.color = '#c0392b';
                sum.style.fontSize = '0.8rem';
                sum.textContent = 'Faltan ' + eq.pendientes.length;
                det.appendChild(sum);
                var div = document.createElement('div');
                div.className = 'muted';
                div.style.fontSize = '0.8rem';
                div.style.marginTop = '4px';
                div.textContent = eq.pendientes.join(', ');
                det.appendChild(div);
                tdEquipo.appendChild(det);
            }
            tr.appendChild(tdEquipo);

            tr.appendChild(celda(eq.terminado ? 'Terminado' : (eq.momentoActual + ' de ' + eq.totalMomentos)));
            tr.appendChild(celda(eq.respondidos + ' / ' + eq.totalEquipo));

            var tdAccion = document.createElement('td');
            if (!eq.terminado && eq.respondidos < eq.totalEquipo) {
                var form = document.createElement('form');
                form.method = 'post';
                form.action = forzarAvanceUrl;
                form.style.margin = '0';
                form.addEventListener('submit', function (ev) {
                    if (!confirm('¿Forzar el avance del ' + eq.team + '? A quien no haya respondido se le va a registrar una respuesta vacía en este momento.')) {
                        ev.preventDefault();
                    }
                });
                var inTeam = document.createElement('input');
                inTeam.type = 'hidden'; inTeam.name = 'team'; inTeam.value = eq.team;
                var inMomento = document.createElement('input');
                inMomento.type = 'hidden'; inMomento.name = 'momento'; inMomento.value = eq.momentoActual;
                var btn = document.createElement('button');
                btn.type = 'submit';
                btn.style.margin = '0'; btn.style.padding = '6px 12px'; btn.style.fontSize = '0.85rem';
                btn.textContent = 'Forzar avance';
                form.appendChild(inTeam);
                form.appendChild(inMomento);
                form.appendChild(btn);
                tdAccion.appendChild(form);
            }
            tr.appendChild(tdAccion);

            return tr;
        }

        function actualizarProgreso() {
            var body = document.getElementById('progresoBody');
            var abiertos = Array.prototype.map.call(body.querySelectorAll('tr'), function (tr) {
                var det = tr.querySelector('details');
                return (det && det.open) ? tr.dataset.team : null;
            }).filter(Boolean);

            fetch(progresoUrl)
                .then(function (r) { return r.json(); })
                .then(function (d) {
                    body.innerHTML = '';
                    (d.equipos || []).forEach(function (eq) {
                        var fila = pintarFila(eq, abiertos);
                        fila.dataset.team = eq.team;
                        body.appendChild(fila);
                    });
                })
                .catch(function () {});
        }

        actualizarProgreso();
        setInterval(actualizarProgreso, 8000);
        </script>
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
                    <td><?= esc($p['role'] ? ($nombresRol[$p['role']] ?? $p['role']) : '—') ?></td>
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
