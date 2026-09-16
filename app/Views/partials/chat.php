<div class="card">
    <h2>Chat de tu equipo</h2>
    <p class="muted" style="margin-top:-8px;">Solo lo ve tu equipo. Úsalo para contarse lo que está pasando en su rol.</p>
    <div id="chatMensajes" style="max-height:220px; overflow-y:auto; border:1px solid #e2e5eb; border-radius:8px; padding:10px; margin-bottom:10px; font-size:0.9rem;"></div>
    <form id="chatForm" style="display:flex; gap:8px; margin:0;">
        <input type="text" id="chatInput" maxlength="500" placeholder="Escribe un mensaje…" autocomplete="off" style="flex:1;">
        <button type="submit" style="width:auto; margin:0;">Enviar</button>
    </form>
</div>
<script>
(function () {
    var token = <?= json_encode($participant['token']) ?>;
    var enviarUrl = <?= json_encode(site_url('chat/enviar/')) ?> + token;
    var mensajesUrl = <?= json_encode(site_url('chat/mensajes/')) ?> + token;
    var ultimoId = 0;
    var box = document.getElementById('chatMensajes');

    function pintarMensaje(m) {
        var div = document.createElement('div');
        div.style.marginBottom = '6px';
        var strong = document.createElement('strong');
        strong.textContent = m.nombre + ': ';
        div.appendChild(strong);
        div.appendChild(document.createTextNode(m.mensaje));
        var hora = document.createElement('span');
        hora.className = 'muted';
        hora.style.fontSize = '0.75rem';
        hora.textContent = ' (' + m.hora + ')';
        div.appendChild(hora);
        box.appendChild(div);
    }

    function revisarChat() {
        fetch(mensajesUrl + '?desde=' + ultimoId)
            .then(function (r) { return r.json(); })
            .then(function (d) {
                var mensajes = d.mensajes || [];
                if (mensajes.length === 0) {
                    return;
                }
                var estabaAbajo = box.scrollTop + box.clientHeight >= box.scrollHeight - 10;
                mensajes.forEach(function (m) {
                    pintarMensaje(m);
                    ultimoId = m.id;
                });
                if (estabaAbajo) {
                    box.scrollTop = box.scrollHeight;
                }
            })
            .catch(function () {});
    }

    document.getElementById('chatForm').addEventListener('submit', function (ev) {
        ev.preventDefault();
        var input = document.getElementById('chatInput');
        var texto = input.value.trim();
        if (texto === '') {
            return;
        }
        input.value = '';
        var body = new URLSearchParams();
        body.set('mensaje', texto);
        fetch(enviarUrl, { method: 'POST', body: body })
            .then(revisarChat)
            .catch(function () {});
    });

    revisarChat();
    setInterval(revisarChat, 3000);
})();
</script>
