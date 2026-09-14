# El Meridián — brief de contenido narrativo

Este documento es para pedir ayuda externa (ChatGPT u otro asistente) a escribir el
contenido que falta de esta dinámica, sin perder el hilo de lo que ya está decidido
y construido. Pégale este archivo completo antes de pedirle que escriba nada.

## Qué es esto

"El Meridián" es una dinámica nueva de capacitación (liderazgo y comunicación) para
Cycloid Talent, dentro de un sitio ya construido en CodeIgniter 4. Es la hermana de
otra dinámica que ya existe ("Liderazgo y Comunicación", antes con el nombre de un
cliente por error — ver nota de naming más abajo).

**Historia (ficticia, no basada en ningún hecho real que se deba citar):** un
carguero llamado **El Meridián** navega hacia una tormenta llamada **Selene**, que
crece más rápido de lo esperado. El equipo del puente debe decidir, juntos, si
mantienen el rumbo o cambian de ruta. Cada persona del equipo solo conoce una parte
de la situación — nadie tiene el cuadro completo. La tensión real no es la tormenta:
es si la gente dice con suficiente claridad lo que piensa, y si quien lidera
realmente escucha.

**Importante — por qué es ficción:** el punto de partida fue un caso real (el
hundimiento del SS El Faro, 2015, 33 muertos). Decidimos **no** usar el caso real
porque es una tragedia con víctimas reales y no queremos que un taller de
capacitación lo trate como trivia. "El Meridián" y "Selene" son nombres inventados a
propósito. **No menciones el caso real, ni nombres, fechas o hechos de él.** Esto es
una ficción original inspirada libremente en el tema (tormenta + decisión bajo
presión + jerarquía que dificulta hablar claro), nada más.

## Objetivo pedagógico

No es "quién tiene razón sobre la tormenta". Es esto: la gente sí percibe el
problema, sí lo comenta entre compañeros — pero al hablar con quien tiene más
autoridad o peso en el grupo, suaviza el mensaje (sugerencias, indirectas, silencio)
en vez de decirlo con claridad. El ejercicio debe hacer que cada participante *viva*
esa tensión en carne propia, no que se la expliquen.

## Cómo funciona mecánicamente (ya construido, no rediseñar esto)

- Cada sesión tiene equipos de 4 o 5 personas. Cada persona recibe un **rol**
  distinto por correo (un enlace privado con su token).
- Cada rol tiene varios **"momentos"** secuenciales. En cada momento:
  1. El participante lee un fragmento narrativo (solo su perspectiva parcial).
  2. Responde una pregunta privada de opción múltiple.
  3. La app espera a que **todo su equipo** responda ese momento antes de destrabar
     el siguiente (esto ya está diseñado, se construye en el próximo paso técnico).
  4. Mientras tanto hablan entre ellos en persona — la app no simula ese diálogo,
     solo lo provoca.
- Al final, el equipo llega a una decisión conjunta: **mantener el rumbo o cambiar
  de ruta** (esto no lo pregunta cada rol individualmente al final del ejercicio —
  eso lo captura el facilitador aparte).
- La revelación del debrief compara lo que cada quien pensaba en privado contra lo
  que el equipo realmente decidió y dijo en voz alta.

## Roles — estado actual

Archivo real: `app/Data/el_meridian_momentos.php` (formato PHP, ver la ficha técnica
abajo). Roles con letra A-E (mismo esquema que la otra dinámica del sitio).

| Rol | Nombre | Estado |
|---|---|---|
| A | Vigía de Tormenta | ✅ Completo — 5 momentos, usar como referencia de tono y longitud |
| B | Piloto de Ruta | ❌ Pendiente |
| C | Oficial de Guardia | ❌ Pendiente |
| D | Ingeniero de Máquinas | ❌ Pendiente |
| E | Contramaestre | ❌ Pendiente |

### Referencia — Rol A completo (Vigía de Tormenta), para copiar el tono

**Momento 1:** Son las 4:40 de la tarde. Llevas seis horas de guardia y el aire ya
cambió — lo notaste antes de mirar cualquier pantalla. El cielo, que esta mañana era
de un azul aburrido, tiene ahora esa palidez sucia que los marinos viejos no
necesitan que nadie les explique. Bajo tus pies, el motor del Meridián sigue con su
zumbido de siempre, pero el vaivén del casco ya no es igual.

En tu monitor parpadea un reporte que debió llegar hace más de una hora. Los datos
vienen incompletos, con huecos, como si el mismo cielo estuviera indeciso: hay una
tormenta ahí afuera —la están bautizando Selene— pero nadie parece saber con
certeza hacia dónde se dirige.

Eres la única persona a bordo que ha visto este reporte. Todavía nadie más lo sabe.

*Pregunta: Con lo que sabes hasta ahora, ¿qué tan preocupante te parece la
situación? → Poco / Algo / Mucho*

**Momento 2:** Bajaste al puente. El café se enfría en tu mano mientras hablas — ni
siquiera lo notas. Cada compañero trae su propio pedazo de la historia: alguien
menciona algo sobre la ruta, alguien más sobre el retraso con el que salieron. Nadie
suelta todo lo que sabe de una sola vez; lo van dejando caer en fragmentos, como
quien no quiere ser el primero en sonar alarmista.

Escuchas. Intentas armar el rompecabezas con lo que dicen. Pero incluso después de
hablar, sientes que faltan piezas — y no sabes si son piezas que nadie tiene, o
piezas que alguien se está guardando.

*Pregunta: Después de escucharlos, ¿tu opinión cambió? → Sí, ahora lo veo distinto /
No, sigo pensando lo mismo*

**Momento 3:** El monitor vuelve a parpadear. Esta vez no hay ambigüedad: la
tormenta no se alejó como se esperaba. Cambió de rumbo. Y está ganando fuerza más
rápido de lo que cualquiera calculó.

Afuera, algo cambió también. El vaivén del Meridián ya no es el balanceo tranquilo
de siempre — ahora hay un tirón más brusco, una pausa incómoda antes de que el casco
vuelva a nivelarse. Alguien en la cubierta grita algo que no alcanzas a escuchar
bien.

*Pregunta: ¿Esto cambia tu recomendación? → Sí, hay que cambiar de ruta / No,
mantener el curso*

**Momento 4:** Quedan minutos, no horas. Alguien del equipo —más años a bordo que el
resto, más autoridad en la voz aunque nadie se la haya dado formalmente— dice que
prefiere mantener el rumbo. Que han cruzado tormentas peores. Que desviarse cuesta
un tiempo que, según él, no tienen.

Nadie lo contradice en voz alta. Ves las caras de los demás: hay dudas, hay
incomodidad, pero también ese silencio pesado que se instala cuando alguien con más
peso ya dijo lo que piensa.

*Pregunta: ¿Qué haces? → Insisto en mi punto, aunque incomode / Cedo, ya lo dije una
vez / Prefiero no repetirlo, ya quedó dicho*

**Momento 5:** La decisión ya está tomada. En el puente todos se movieron a lo que
sigue — a preparar lo que haya que preparar. Ya no hay espacio para cambiar de
opinión.

Te quedas un momento con la pregunta que nadie hizo en voz alta: si de verdad
dijiste lo que pensabas, o si, como los demás, dejaste que se perdiera entre los
comentarios de todos.

*Pregunta: ¿Sientes que tu opinión influyó en la decisión final? → Sí, bastante / Un
poco / Casi nada*

## Lo que necesito que redactes para los 4 roles que faltan

Para cada uno de **Piloto de Ruta (B)**, **Oficial de Guardia (C)**, **Ingeniero de
Máquinas (D)** y **Contramaestre (E)**:

- **5 momentos** (misma cantidad que el rol A — pueden ser menos o más si hay una
  buena razón narrativa, pero 5 es el punto de partida).
- Cada momento: un fragmento narrativo (2-4 párrafos cortos, mismo tono que el
  ejemplo — sensorial, con cuerpo, con silencios que pesan, nunca expositivo tipo
  manual) + una pregunta de opción múltiple (2-3 opciones, cortas).
- **Cada rol debe saber algo que los otros NO saben**, y desconocer algo que otros sí
  saben. Ideas de qué información parcial le puede tocar a cada uno (puedes
  cambiarlas, son solo punto de partida):
  - **Piloto de Ruta:** conoce las dos alternativas de ruta (la habitual, cerca del
    peligro, y una desviación más larga). Sabe que ya salieron con retraso y que la
    puntualidad se evalúa como parte del desempeño.
  - **Oficial de Guardia:** ha escuchado conversaciones informales entre la
    tripulación — la preocupación real que existe "entre bambalinas", distinta a lo
    que se dice después frente a quien decide.
  - **Ingeniero de Máquinas:** sabe algo técnico y concreto sobre el estado del
    barco/motor que condiciona qué tan rápido o seguro es desviarse (inventa un dato
    técnico creíble, sin tecnicismos reales de náutica que puedan sonar falsos).
  - **Contramaestre:** es quien más ha notado si el equipo, al final, logra decir
    las cosas con claridad o se queda en insinuaciones — su información es más sobre
    la dinámica humana del grupo que sobre datos del barco.
- Los momentos de los 5 roles deben poder coexistir en la misma línea de tiempo (le
  están pasando a todos al mismo tiempo, desde ángulos distintos) — no deben
  contradecirse entre sí en los hechos básicos (la tormenta, el retraso, el tiempo
  que queda).
- Streaming de tensión: los momentos 1-2 son de descubrimiento/diálogo inicial, el
  momento 3 es el giro (la tormenta empeora), el momento 4 es la presión de
  jerarquía/tiempo, el momento 5 es el cierre reflexivo — mantén esa curva en los 4
  roles nuevos.

## Restricciones duras (no negociables)

1. Nada de hechos, nombres, fechas o víctimas del caso real. Ficción 100% original.
2. No inventar mecánicas nuevas de interacción (botones, chats, minijuegos) — el
   formato es narrativa + pregunta de opción múltiple, nada más.
3. Mantener la asimetría de información — ningún rol puede "saberlo todo".
4. El tono tiene que sentirse humano y con alma, no como un caso de estudio de
   escuela de negocios.

## Formato de entrega esperado

Por cada rol, en este formato exacto (para poder copiarlo directo al archivo PHP):

```
ROL: [nombre del rol]

Momento 1:
[narrativa]
Pregunta: [pregunta]
Opciones: [opción 1] / [opción 2] / [opción 3 si aplica]

Momento 2:
...
```
