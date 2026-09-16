<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Código Azul — Antes de empezar</title>
<link rel="stylesheet" href="<?= base_url('assets/style.css?v=4') ?>">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png?v=3') ?>">
</head>
<body>
<div class="wrap">
    <img src="<?= base_url('assets/img/codigo-azul-hero.jpg') ?>" alt="Código Azul — una decisión con información incompleta" style="width:100%; height:auto; border-radius:12px; margin-bottom:16px; display:block;">
    <div class="card">
        <h1>Código Azul</h1>

        <div class="confidential"><strong>Diecisiete minutos antes del Código Azul.</strong>

Nadie está corriendo.

Eso es importante.

El turno de la tarde en el Hospital Altamira transcurre con esa calma extraña que tienen los lugares donde nunca hay verdadero silencio.

Un monitor insiste en el mismo ritmo.

Una puerta se abre.

Ruedas sobre baldosa.

Alguien pregunta por un café que dejó enfriarse hace una hora.

En la habitación, un paciente descansa después de un procedimiento que salió como debía.

Todo parece estar donde tiene que estar.

Hasta que algo cambia.

No lo suficiente para activar una alarma.

No lo suficiente para detener la sala.

Apenas lo suficiente para que una persona mire dos veces.

Unos metros más allá, alguien espera un resultado que debería haber llegado.

Otra persona observa algo distinto.

Todavía no sabe si importa.

Ninguno sabe lo que saben los otros.

Y durante algunos minutos, eso no parece peligroso.

El monitor continúa.

Bip.

Bip.

Bip.

El médico tratante está fuera de la habitación.

Cuando vuelva, tendrá que decidir.

Pero no decidirá con todo lo que está ocurriendo.

Decidirá con algo mucho más frágil:

<strong>lo que el equipo consiga decirle.</strong></div>

        <h2>Tu equipo</h2>
        <table>
            <thead><tr><th>Integrante</th><th>Rol</th></tr></thead>
            <tbody>
            <?php foreach ($companeros as $c): ?>
                <tr>
                    <td><?= esc($c['nombre']) ?><?= $c['esTu'] ? ' <span class="muted">(tú)</span>' : '' ?></td>
                    <td class="muted"><?= esc($c['rol']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="confidential" style="margin-top:20px;">A partir de ahora, vas a estar dentro de esa habitación.

No vas a saberlo todo.

Vas a recibir una sola parte de la historia.

Quizá al principio parezca pequeña.

Quizá alguien vea algo que contradiga lo que tú estás viendo.

Quizá estés completamente seguro de algo…

hasta que tengas que decirlo frente a los demás.

Habla con tu equipo.

Escúchalos.

Decide qué sostienes.

Pero no muestres tu pantalla.

No reveles tu rol.

Porque en esta historia hay una diferencia enorme entre <strong>ver</strong>, <strong>pensar</strong>, <strong>decir</strong> y <strong>lograr que alguien escuche</strong>.

Y todavía faltan diecisiete minutos.</div>

        <a class="btn" href="<?= site_url('codigo-azul/rol/' . $participant['token']) ?>">Ver mi rol</a>
    </div>
</div>
</body>
</html>
