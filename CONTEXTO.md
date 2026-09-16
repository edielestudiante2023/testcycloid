# Contexto del proyecto — Dinámicas de Capacitación Virtual

Documento de traspaso para retomar este proyecto con otro agente/sesión sin perder contexto.

## Qué es esto

Sitio interno de Cycloid Talent para correr dinámicas de capacitación (liderazgo/comunicación)
con múltiples clientes en paralelo, sin que se mezclen resultados entre ellos. Vive en producción en
**https://test.cycloidtalent.com/**.

Primera dinámica implementada: **Liderazgo y Comunicación** (coordinación bajo presión, roles
secretos A–E, entrega simulada a un cliente ficticio, con un giro a mitad del ejercicio y debrief
sobre "liderazgo sin cargo"). Slug: `liderazgo-comunicacion`. **"Ardurra" es el nombre de un
cliente** (un valor más en la columna `cliente` de `sesiones`), no el nombre de la dinámica — ver
nota en "Decisiones y hallazgos".

## Stack

- **CodeIgniter 4.7.4** (PHP `^8.2`, corre sobre PHP 8.4 en el servidor). Instalado vía Composer
  (`codeigniter4/appstarter`) — **no es PHP plano**, es un proyecto CI4 completo con Models,
  Controllers, Views, Filters, Migrations y Seeds.
- **MySQL gestionado (DigitalOcean)** — base `testcycloidtalent`, tanto local (XAMPP, `root` sin
  password) como producción (conexión cifrada, ver más abajo).
- **SendGrid API** (no SMTP) para el envío de correos, con click tracking desactivado
  explícitamente (`tracking_settings.click_tracking.enable = false`).
- **Servidor de producción**: aaPanel/BT, acceso root por SSH. Credenciales en
  `D:\DESARROLLO\KEYS\ssh.txt`.

## Regla operativa que hay que seguir siempre (`D:\DESARROLLO\KEYS\sql.txt`)

- **Nunca** ejecutar SQL manual (phpMyAdmin, cliente MySQL, DBeaver, etc.).
- Todo cambio de esquema o dato pasa por un **script PHP** (las migraciones/seeds de CI4, o un
  script PHP puntual si hace falta algo fuera de eso).
- Orden obligatorio: **primero LOCAL**, y solo si sale bien, **producción**.

## Flujo de la aplicación

1. `/login` — login del sitio (tabla `usuarios`, email + password hasheado).
2. `/` (dashboard) — lista las dinámicas activas (tabla `dinamicas`).
3. `/sesiones/{slug}` — lista las sesiones (una por cliente) de esa dinámica.
4. `/sesiones/nueva/{slug}` → `/sesiones/crear` — el facilitador define **cliente**, **tamaño de
   equipo (4 o 5)** y **duración del ejercicio en minutos**. Se crea una `sesion` con un token
   único.
5. `/sesiones/qr/{token}` — pantalla de control en vivo del facilitador:
   - Antes de enviar: QR de registro (`/ardurra/registro/{token}`) + contador de registrados que
     se actualiza solo cada 5s (fetch AJAX a `/sesiones/contador/{token}`, sin recargar la
     página) + botón **"Enviar taller vía email"**.
   - El QR lleva a un formulario público (sin login) donde el participante llena datos personales
     (nombre, documento, cargo, email corporativo, email personal, WhatsApp, si tiene personal a
     cargo) y acepta el aviso de tratamiento de datos (Ley 1581 de 2012). **Todavía no recibe
     equipo ni rol** — eso se decide al enviar el taller.
   - Al hacer clic en "Enviar taller": el sistema toma a **todos** los registrados hasta ese
     momento, los reparte en equipos del tamaño configurado (aleatorio, sin roles repetidos
     dentro de cada equipo — pool A,B,C,D,E si son 5, A,B,C,E si son 4), genera un token por
     participante y envía por SendGrid (a email corporativo Y personal) un enlace a su rol
     (`/ardurra/rol/{token}`).
   - Después de enviado: cronómetro visual (basado en la duración configurada) — **nunca cierra
     nada solo**. El cierre es manual, con el botón "Cerrar ejercicio" (clic del moderador).
6. `/sesiones/resultados/{token}` — vista de resultados por sesión: equipos, roles, respuesta
   esperada por rol (para el debrief), y un selector por participante "¿Mostró liderazgo
   visible?" para cruzarlo después contra "¿tiene personal a cargo?" — esa es la evidencia que se
   le puede mostrar al cliente en el cierre.

## Estructura relevante

```
app/Controllers/   AuthController, DashboardController, SesionesController, LiderazgoComunicacionController
app/Models/         UsuarioModel, DinamicaModel, SesionModel, ParticipantModel
app/Filters/        AuthFilter (protege dashboard + /sesiones/*)
app/Libraries/      SendGridMailer (API directa por cURL, sin SDK de Composer)
app/Database/       Migrations/ y Seeds/AdminSeeder.php
app/Data/           liderazgo_comunicacion_roles.php, liderazgo_comunicacion_answers.php (contenido de las 5 tarjetas + debrief)
app/Views/          auth/, dashboard/, sesiones/, liderazgo-comunicacion/, emails/
public/assets/      logo y favicon de Cycloid Talent, style.css compartido
```

## Archivos con secretos (NO están en git, hay que recrearlos si se clona de cero)

- `.env` — conexión a base de datos + `app.baseURL`. Ver `.env` de ejemplo en el repo (`env`).
- `app/Config/SeedAdmin.{development|production}.php` — email/password del usuario semilla.
- `app/Config/Mail.{development|production}.php` — API key de SendGrid + remitente.

En producción estos tres archivos ya están creados directamente en el servidor
(`/www/wwwroot/test/`), con permisos `640`, owner `www:www`. **Las credenciales no están en el
repo ni en este documento** — si hace falta reconstruirlas, pedírselas directamente al
desarrollador (no improvisar valores). Una vez obtenidas, guardarlas para futuras sesiones en
`D:\DESARROLLO\KEYS\sql.txt` (MySQL producción) y `D:\DESARROLLO\KEYS\sendrid.txt` (API key) —
son archivos locales fuera del repo, nunca commitearlos ni pegar su contenido en este documento.

## Cómo levantar el proyecto en local

1. Clonar el repo y correr `composer install`.
2. Crear `.env` a partir del ejemplo del repo (`env`), apuntando al MySQL local de XAMPP (`root`
   sin password) y con `CI_ENVIRONMENT = development`.
3. Crear en local una base de datos vacía llamada `testcycloidtalent` (por ejemplo desde
   phpMyAdmin) — **no hay que armar el esquema a mano**, lo hacen las migraciones.
4. Correr `php spark migrate` para crear todas las tablas.
5. Correr `php spark db:seed AdminSeeder` para el usuario admin de prueba — usa
   `app/Config/SeedAdmin.development.php`; si no existe, crearlo con datos de prueba propios,
   **nunca** con las credenciales de producción.
6. Crear `app/Config/Mail.development.php` con una API key de SendGrid de pruebas si se necesita
   probar el envío real de correos (si se deja sin configurar, el resto del flujo funciona igual).
7. Abrir `http://localhost/test/` — debería mostrar el login.

## Cómo desplegar un cambio

```bash
git add .
git commit -m "..."
git checkout main
git merge cycloid
git push origin main
git checkout cycloid
git push origin cycloid
```

Luego, por SSH (ver credenciales en `ssh.txt`):

```bash
cd /www/wwwroot/test
git pull origin main
composer install --no-dev --optimize-autoloader   # solo si cambiaron dependencias
php spark migrate                                  # solo si hay migraciones nuevas
chown -R www:www /www/wwwroot/test
```

## Decisiones y hallazgos que vale la pena no repetir

- El proyecto **empezó en PHP plano** (sin framework) y se migró por completo a CodeIgniter 4 a
  petición explícita, para "hacer las cosas bien" con Composer. Todo lo descrito arriba ya está
  en CI4 — no queda nada del PHP plano.
- `orderBy('team IS NULL', ...)` en el Query Builder de CI4 necesita el tercer parámetro `false`
  para no romperse (si no, escapa mal la expresión SQL).
- El MySQL gestionado de DigitalOcean exige conexión cifrada: `Config\Database` activa
  `encrypt = ['ssl_verify' => false]` **solo cuando `ENVIRONMENT === 'production'`** (el MySQL
  local no tiene SSL, así que esto rompería el entorno local si se aplicara siempre).
- `indexPage` en `Config/App.php` está vacío (`''`) para que las URLs generadas (QR, emails,
  redirecciones) no incluyan `index.php`. Funciona igual en local (servidor embebido de PHP) y en
  producción (Apache con `mod_rewrite`, `AllowOverride All` ya configurado en el vhost).
- El dominio `test.cycloidtalent.com` está detrás de **Cloudflare** — los assets estáticos
  (CSS/imágenes) se cachean ahí. Si cambias `style.css` o el logo y no se ve el cambio, sube la
  versión en el query string (`?v=3` → `?v=4`) en vez de esperar a que expire la caché.
- Existe un repo de GitHub llamado `test` (distinto de `testcycloid`, que es este) que pertenece a
  un proyecto viejo de otro cliente (Technoliner) — no tocar, no tiene relación con esto.
- La dinámica se llamó originalmente "Reto Ardurra" en slug, controlador (`ArdurraController`),
  rutas (`/ardurra/...`), vistas (`app/Views/ardurra/`), helper y asunto del email — pero Ardurra
  es el nombre de un **cliente**, no de la dinámica. Se corrigió todo a
  `liderazgo-comunicacion` / `LiderazgoComunicacionController` / "Liderazgo y Comunicación"
  (migración `2026-09-14-150005_RenameArdurraToLiderazgoComunicacion` actualiza el slug/nombre
  existente en `dinamicas`). Si aparece "Ardurra" en el código de aquí en adelante, debe ser
  únicamente como valor de la columna `cliente` en `sesiones`.

## Pendiente / conversación abierta

No se ha resuelto todavía cómo hacer que la herramienta en sí (no solo la logística de QR/roles)
empuje la reflexión sobre comunicación y liderazgo — por ejemplo, un cierre de reflexión para el
participante, o un reporte para el cliente que cruce "tiene personal a cargo" vs. "mostró
liderazgo visible" (ese campo ya existe en `resultados.php`, pero no hay un reporte armado
todavía). Vale la pena retomar esa conversación antes de dar la dinámica por terminada.
