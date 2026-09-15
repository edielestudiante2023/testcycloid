# Guía para crear módulos de simulación conductual basados en historias

Este documento explica cómo construir, sobre esta misma plataforma, una dinámica
nueva del mismo tipo que **El Meridián**: una historia que provoca decisiones
observables, en vez de preguntarle a la persona directamente cómo es o cómo
actuaría. Úsalo como punto de partida cada vez que quieras un módulo nuevo.

## Qué es esto, en términos técnicos

El nombre correcto de este género es **análisis conductual basado en
escenarios narrativos** (behavioral simulation). Es primo de otras técnicas
conocidas:

- **Critical Incident Technique** — analizar una situación concreta para ver
  qué comportamientos llevaron a un resultado bueno o malo.
- **Método del caso** — presentar una historia problemática y que el grupo
  decida qué habría hecho.
- **Story-based learning** — usar narrativa para provocar reflexión.

La diferencia de El Meridián frente a esas técnicas clásicas es que **no se
pregunta directamente** ("¿usted es buen líder?"), sino que la historia
**provoca comportamiento observable**: la persona elige, bajo presión y con
información incompleta, y esa elección —no su autoevaluación— es el dato.

## Por qué funciona (el principio pedagógico central)

Casi todos los fenómenos de liderazgo/comunicación que vale la pena enseñar
tienen la misma estructura: existe una **brecha entre lo que alguien piensa
en privado y lo que termina diciendo o sosteniendo en público**, sobre todo
bajo presión de jerarquía o tiempo. El diseño técnico de estos módulos existe
para capturar esa brecha con datos, no para narrar una historia bonita.

Todo lo que sigue está al servicio de esa idea. Si un módulo nuevo no puede
capturar esa brecha (o el fenómeno equivalente que quieras medir), probablemente
no necesita esta arquitectura — bastaría con una encuesta simple.

## 1. Diseño narrativo

### 1.1 Ficciona, no reciclas casos reales con víctimas

El Meridián partió de un caso real (un naufragio con 33 muertos). Se descartó
explícitamente usarlo tal cual: una tragedia real con víctimas reales no debe
tratarse como material de taller. Se inventó un barco, una tripulación y una
tormenta ficticios, conservando solo el *patrón* humano (información
asimétrica, presión de jerarquía, mensajes que se suavizan).

**Regla:** si el punto de partida es un caso real, ficcionalízalo por completo
— nombres, lugares, fechas — antes de escribirlo. Si el tema es sensible
(muertes, accidentes, discriminación), redobla el cuidado.

### 1.2 Asimetría de información entre roles

Cada rol conoce una porción distinta de la verdad. Nadie tiene el panorama
completo. La tarea del equipo es reconstruirlo hablando — la app nunca
simula esa conversación, solo la provoca y después mide qué pasó con ella.

En El Meridián (`app/Data/el_meridian_momentos.php`) los 4-5 roles cubren:
información técnica/objetiva (el clima, la ruta), información social (lo que
la gente dice cuando el jefe no está delante), y observación del propio grupo
(quién nota que las opiniones se están empequeñeciendo).

### 1.3 El arco narrativo de 5 momentos

Reutiliza esta curva — está probada y le da ritmo al ejercicio:

1. **Lectura inicial** — cada quien recibe su pieza de información parcial.
2. **Después de hablar con el equipo** — ¿cambió algo tras la conversación?
3. **El giro** — algo empeora o se aclara; suele ser el pico de claridad del
   equipo.
4. **Presión de jerarquía o tiempo** — alguien con más peso en el grupo dice
   algo decisivo; aquí es donde típicamente se produce el "punto de quiebre"
   (ver sección 3).
5. **Cierre reflexivo** — cada quien evalúa, en privado, si sintió que su
   opinión influyó en la decisión final del equipo.

No es obligatorio que sean exactamente 5 — el motor no asume un número fijo
(`app/Data/*_momentos.php` define lo que quiera cada rol) — pero mantener
1-2-3-4-5 con ese propósito facilita mucho el diseño de las dimensiones
cuantitativas (sección 3).

### 1.4 Estilo de escritura

Nada de prosa expositiva tipo manual. Usa: frases cortas, sensorial (sonidos,
cuerpo, silencios), sin explicar la mecánica del juego dentro de la historia.
Escribe cada momento como si fuera literatura corta, no como un enunciado de
examen. Compara el primer borrador del Vigía de Tormenta (denso, expositivo)
contra la versión final (fragmentada, con alma) en el historial de commits de
`el_meridian_momentos.php` — la diferencia de calidad es enorme y vale la pena
invertir ahí el tiempo.

### 1.5 Consistencia de hechos entre roles

Todos los roles viven la misma línea de tiempo desde ángulos distintos. La
misma persona con más peso en el grupo que dice la frase decisiva en el
Momento 4 debe aparecer, coherente, en las tarjetas de todos los roles. Antes
de dar por cerrado el contenido, léelo de corrido rol por rol y verifica que
nadie contradiga un hecho básico (hora, evento, decisión de otro).

## 2. Arquitectura técnica reutilizable

Toda esta parte ya existe genérica en el código — para un módulo nuevo,
créala en paralelo a El Meridián, sin tocar sus archivos.

| Pieza | Dónde vive (ejemplo de El Meridián) | Qué cambia por módulo |
|---|---|---|
| Dinámica en BD | migración tipo `AddElMeridianDinamica` | slug, nombre, descripción |
| Contenido narrativo | `app/Data/el_meridian_momentos.php` | todo el archivo |
| Controlador | `app/Controllers/ElMeridianController.php` | nombre de clase + slug hardcodeado |
| Vistas | `app/Views/el-meridian/*.php` | textos, mismo esqueleto |
| Rutas | grupo `el-meridian` en `Routes.php` | prefijo del grupo |
| Helper | `app/Helpers/el_meridian_helper.php` | nombre de función |

**Piezas que ya son genéricas y NO necesitas duplicar:**

- Asignación de equipos/roles al azar (`ParticipantModel::iniciarEjercicio`) —
  funciona para cualquier dinámica con roles A-E.
- El motor de momentos: tabla `respuestas_momento`, gating por equipo
  (`RespuestaMomentoModel::momentoActualDelEquipo`), polling de espera, y el
  botón "Forzar avance" del facilitador — todo está escrito genérico, solo
  necesitas tu propio archivo de datos con la narrativa.
- El envío de correos y el asunto usan `$sesion['dinamica_nombre']`
  dinámicamente — no hay que tocar `SesionesController::enviar()`.

**Flujo de participante** (igual para cualquier módulo de este tipo):
QR → registro → confirmación → correo con enlace de **intro** (contexto +
lista real del equipo) → enlace de **rol** (momentos secuenciales, con
pantalla de "esperando a tu equipo" entre cada uno) → pantalla de cierre.

## 3. Diseñar las dimensiones cuantitativas

Esta es la parte que convierte "una historia bonita" en "una herramienta de
evaluación seria". No calcules nada a ojo — defínelo matemáticamente.

### 3.1 Elige qué vas a medir

Antes de tocar código, responde: ¿qué comportamiento específico quiere
observar este módulo? En El Meridián son 6 dimensiones
(`app/Models/ElMeridianDimensiones.php`):

1. Detección temprana del riesgo (Momento 1)
2. Intercambio de información (Momento 2)
3. Claridad al comunicar (Momento 3)
4. Capacidad de disentir bajo presión (Momento 4)
5. Persistencia ante presión (comparación Momento 3 vs Momento 4 — el "punto
   de quiebre")
6. Percepción de haber sido escuchado (Momento 5)

Para otro módulo, las dimensiones serán distintas — pero el patrón "una
dimensión por momento, más una dimensión de comparación entre el pico y la
presión" es reutilizable.

### 3.2 Puntúa cada opción, no cada persona

Por cada rol, por cada momento, asigna un puntaje (0 = bajo/evasivo,
1 = medio, 2 = alto/directo) a cada opción de respuesta. Es trabajo manual,
opción por opción, pero es la única forma de que el número final sea
defendible. Documenta el razonamiento en el propio archivo — no lo dejes
implícito.

### 3.3 Agrega a nivel de equipo, nunca a nivel de dato crudo

Promedia los puntajes de todos los integrantes del equipo en un momento dado,
y convierte ese promedio en Alta/Media/Baja con umbrales fijos
(`ElMeridianDimensiones::nivel()`). Para la dimensión de "quiebre", compara el
promedio de dos momentos consecutivos — si cae, ahí está el quiebre.

### 3.4 No lo guardes calculado — calcúlalo al leer

La radiografía se recalcula cada vez que se pide (`radiografiaPorEquipo()`),
a partir de las respuestas crudas (`respuestas_momento`) más la tabla de
puntajes en código. **No creamos una migración ni una tabla nueva para
guardar los números calculados.** Motivo: si algún día se ajustan los
puntajes, todas las sesiones —viejas y nuevas— reflejan el criterio vigente
automáticamente, sin quedar "congeladas" con un cálculo obsoleto. Guarda en
base de datos solo el dato crudo (qué opción eligió cada quien) y el
resultado final de un análisis costoso de recrear (como el texto de la IA,
ver sección 4.5) — nunca un número que se puede recalcular gratis.

## 4. Diseñar el prompt de análisis con IA

### 4.1 Sepára hechos de interpretación, explícitamente

Instruye al modelo a marcar la diferencia en el propio texto: lo que alguien
respondió es un hecho (cítalo textual); lo que eso podría significar es una
lectura, y debe sonar como lectura ("esto podría indicar..."). Prohíbe
explícitamente los contrafactuales ("si tal persona no hubiera hecho tal
cosa, el equipo habría...") — eso es invención, no dato.

### 4.2 Manda trayectorias, no tablas sueltas

No le des al modelo una lista de respuestas aisladas. Dale, por persona, la
secuencia completa con flechas (`Mucho → cambió de opinión → cambiar ruta →
cede → poca influencia`). El patrón está en la secuencia, no en el dato
suelto — igual que se lee mejor una película que un conjunto de fotos sin
orden.

### 4.3 Pásale los números ya calculados, no le pidas que los invente

La radiografía (sección 3) se calcula en código y se le entrega al modelo
como hecho dado ("no la recalcules, es un hecho"). El modelo explica los
números con las trayectorias; no los inventa ni los cuestiona. Esto evita que
la IA alucine estadísticas y hace el análisis verificable.

### 4.4 Pide detección explícita de fenómenos concretos

En vez de "dame un análisis", pide que detecte fenómenos nombrados y
específicos del módulo. En El Meridián son: cambio de posición, suavización,
sostener-o-ceder ante presión, y brecha entre convicción privada y percepción
de haber influido. Para otro módulo, nombra los fenómenos propios de ese
tema (por ejemplo, en un módulo sobre delegar: "microgestión", "delegación sin
seguimiento", "ambigüedad de rol").

### 4.5 Nunca culpes a un individuo — reformula a lo sistémico

Regla dura y explícita en el prompt: si alguien cedió o suavizó, el fenómeno
es del sistema (la dinámica del grupo, la jerarquía, la falta de espacio para
disentir), no un defecto personal. Prohíbe preguntas tipo "¿por qué fulano no
insistió?" y exige la forma sistémica: "¿qué ocurrió en el equipo para que
una posición clara se hiciera pequeña?". Esto no es solo ético — también
hace que el debrief real sea más productivo (nadie se pone a la defensiva).

### 4.6 Fija una estructura de salida rígida

Dale al modelo una plantilla con secciones nombradas (en El Meridián: EL
PUNTO DE QUIEBRE / PREGUNTA PARA ABRIR LA CONVERSACIÓN / PARA PROFUNDIZAR /
CIERRE DEL FACILITADOR). Sin esa plantilla, la calidad varía mucho de una
llamada a otra.

### 4.7 Reglas de forma, siempre

- **Sin Markdown** — nada de `##`, `**`, listas con guiones. El modelo tiende
  a usarlo por defecto; hay que prohibirlo explícitamente y verificarlo en
  las pruebas.
- **Sin jerga interna** — nunca abreviaturas tipo "M1/M2/M3"; describe cada
  etapa con palabras.
- **Sin anglicismos** — nada de "debrief", "feedback"; usa "cierre",
  "conversación de cierre", "retroalimentación". Aplica también a lo que el
  modelo genera, no solo al texto fijo de la app.
- **Límite de palabras explícito** — si no lo pones, el modelo se puede
  extender de más.

### 4.8 Nunca bloquees el ejercicio si la IA falla

La llamada a la IA debe tener un timeout razonable (30-60s) y, si falla,
tarda demasiado, o no hay configuración, la función debe devolver `null` sin
lanzar una excepción. Quien llama siempre debe tener un resumen alternativo
calculado por reglas simples (ver `resumenTexto()` en
`RespuestaMomentoModel.php`) que no dependa de ningún servicio externo. Un
ejercicio en vivo con gente esperando nunca debe trabarse por una API caída.

### 4.9 Privacidad de los datos que le mandas a la IA

Decide conscientemente si el prompt lleva nombres reales o solo roles
anónimos — es una decisión de negocio, no técnica, y depende de qué autorizó
la gente al registrarse. Si hay duda, empieza sin nombres (solo rol) y súbelo
a nombres reales solo con autorización explícita.

## 5. Checklist para arrancar un módulo nuevo

1. Define el fenómeno humano que quieres medir (la "brecha" central,
   sección "Por qué funciona").
2. Escribe la historia ficcionalizada (sección 1) — idealmente con ayuda
   externa (ChatGPT u otro asistente), usando un brief tipo
   `EL_MERIDIAN_BRIEF.md` como plantilla para no perder el hilo.
3. Diseña los roles y su asimetría de información.
4. Escribe los momentos de un solo rol primero, valida el tono, y luego
   replica el patrón en los demás roles.
5. Crea la migración de la dinámica nueva + el archivo de datos
   (`app/Data/{modulo}_momentos.php`).
6. Duplica el controlador/vistas/rutas de El Meridián, cambiando solo el
   slug y los textos — no toques el motor genérico.
7. Define las dimensiones cuantitativas y puntúa cada opción
   (sección 3).
8. Escribe el prompt de IA siguiendo la sección 4, reusando
   `app/Libraries/KimiAnalisis.php` como plantilla.
9. Prueba todo end-to-end con datos sintéticos (nombres falsos) antes de
   usar nombres reales — nunca uses tu propio tester para mandar PII real a
   una API externa sin que el usuario lo haya autorizado explícitamente.
10. Despliega primero en local, corre las migraciones, y solo después de
    confirmar que funciona, despliega a producción.
