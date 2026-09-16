<?php

declare(strict_types=1);

/**
 * Contenido narrativo de la dinámica "Volver a Casa".
 *
 * Los 5 roles (A-E) están completos y validados en tono (ver
 * GUIA_MODULOS_CONDUCTUALES.md, sección 5, paso 4). Todavía falta el
 * resto del andamiaje técnico: migración de la dinámica, dimensiones
 * cuantitativas, controlador, vistas, rutas y prompt de IA.
 *
 * Historia ficcionalizada, inspirada libremente en un caso real de
 * emergencia espacial ampliamente documentado (sin víctimas), siguiendo
 * la regla 1.1 de la guía: nave, misión, centro de control y personajes
 * son inventados; solo se conserva el patrón humano (información
 * distribuida entre especialistas, presión de jerarquía y de tiempo,
 * coordinación bajo incertidumbre).
 *
 * @return array<string, array{nombre: string, momentos: array<int, array{narrativa: string, pregunta: string, opciones: array<string, string>}>}>
 */
return [
    'A' => [
        'nombre' => 'Guardián de Energía',
        'momentos' => [
            1 => [
                'narrativa' => "Las pantallas nunca mienten, pero a veces titubean.\n\n"
                    . "Llevas cuarenta minutos mirando la misma columna de números: el consumo de la nave, estable, aburrido, previsible. El zumbido de los ventiladores del centro de control es el único sonido que has escuchado en la última hora.\n\n"
                    . "Entonces cae.\n\n"
                    . "No un parpadeo. Una caída real, sostenida, como si alguien hubiera cerrado una llave en algún punto del circuito. La aguja no tiembla — simplemente deja de estar donde debería.\n\n"
                    . "Revisas el panel dos veces. Tres. Cambias de pantalla, vuelves a la anterior. El número no vuelve.\n\n"
                    . "A tu lado, alguien comenta algo sobre una posible falla de sensor. Suena razonable. Casi te convence. Llevas años oyendo esa frase cada vez que algo no cuadra a la primera.\n\n"
                    . "Pero tú conoces esa curva. La has visto miles de horas, en simulacro y en vuelo real. Esto no es un sensor. Un sensor falla distinto — con ruido, con saltos. Esto es liso, constante, real.\n\n"
                    . "Miras de reojo al resto de la sala. Nadie más está mirando tu pantalla todavía. Todos tienen la suya.\n\n"
                    . "Todavía nadie más lo ha dicho en voz alta.",
                'pregunta' => 'Con lo que ves en tu panel, ¿qué tan grave te parece lo que está pasando?',
                'opciones' => [
                    'poco' => 'Poco, probablemente un sensor',
                    'algo' => 'Algo, hay que confirmarlo ya',
                    'mucho' => 'Mucho, esto es real y se está extendiendo',
                ],
            ],
            2 => [
                'narrativa' => "Lo dices por el canal. Tu voz suena más firme de lo que esperabas — más firme, incluso, de lo que te sientes por dentro.\n\n"
                    . "«Tenemos una caída de energía sostenida en el sistema principal.»\n\n"
                    . "Silencio de dos segundos. Después, preguntas cruzadas desde otras consolas — alguien pide confirmar la lectura, alguien más ya está mirando otro parámetro que tampoco cuadra, alguien repite tu frase como si necesitara oírla dos veces para creerla.\n\n"
                    . "El Director de Vuelo no dice nada todavía. Escucha, con los brazos cruzados, la mirada fija en la pantalla central.\n\n"
                    . "Tú sigues mirando tu pantalla. El número no se recupera. Si acaso, empeora un poco — una décima menos, después otra.\n\n"
                    . "Alguien en la sala dice, casi para sí mismo, pero lo bastante alto para que todos lo oigan: «puede ser un problema de instrumentación, ya nos ha pasado antes, en el simulacro de marzo».\n\n"
                    . "Es cierto. Pasó en marzo. Y era, en efecto, un sensor.\n\n"
                    . "Nadie lo contradice. Todavía.",
                'pregunta' => 'Después de escuchar esa duda en la sala, ¿qué haces con tu lectura?',
                'opciones' => [
                    'sostengo' => 'Sostengo mi diagnóstico tal como lo vi',
                    'matizo' => 'Lo presento como posible, no como seguro',
                    'dejo_pasar' => 'No insisto, dejo que otros lo resuelvan',
                ],
            ],
            3 => [
                'narrativa' => "Entonces llega la confirmación, no de un instrumento, sino de la nave misma: un golpe, una sacudida que registran todos los sensores a la vez, como si algo hubiera empujado la estructura desde dentro.\n\n"
                    . "Por un segundo el canal de voz se llena de todos hablando encima de todos. Después, tan rápido como empezó, el silencio vuelve — más tenso que antes.\n\n"
                    . "Ahora no hay duda. La caída de energía no es un error de medición — es una pérdida real, y grande.\n\n"
                    . "Haces el cálculo rápido, el que llevas años entrenando para no tener que hacer nunca: con lo que queda, la energía no alcanza para todo. Hay que decidir, pronto, qué se apaga y qué se protege.\n\n"
                    . "Escribes el número en tu bitácora antes de decirlo en voz alta, como si el papel lo hiciera más real, más tuyo.\n\n"
                    . "Esa cifra —la que tú acabas de calcular— es distinta a la que alguien más dice en voz alta, dos consolas más allá. No mucho distinta. Lo suficiente para importar.\n\n"
                    . "Un número, otro número. La misma emergencia.",
                'pregunta' => 'Tu número no coincide exactamente con el que se acaba de decir en la sala. ¿Qué haces?',
                'opciones' => [
                    'corrijo_ya' => 'Interrumpo y corrijo la cifra de inmediato',
                    'espero_pausa' => 'Espero una pausa para aclararlo con calma',
                    'lo_dejo' => 'Asumo que el otro tiene mejor información y no digo nada',
                ],
            ],
            4 => [
                'narrativa' => "El Director de Vuelo corta el ruido de la sala con una frase seca, sin levantar la voz:\n\n"
                    . "«No empeoremos esto adivinando. Necesito la cifra real, no la más rápida.»\n\n"
                    . "La sala entera se queda quieta un instante, como si todos hubieran contenido el aire al mismo tiempo.\n\n"
                    . "Te mira a ti. Directamente. No a la consola de al lado, no a la pantalla central — a ti.\n\n"
                    . "Tienes dos números en la cabeza: el que sale de seguir el procedimiento completo, más lento y más seguro, verificado dos veces; y el que alguien más ya insinuó minutos atrás, más optimista, más rápido de decir, más fácil de aceptar.\n\n"
                    . "El reloj de la misión sigue corriendo, visible en la pantalla superior, y todos en la sala lo saben sin necesidad de mirarlo.\n\n"
                    . "El Director espera tu respuesta. No repite la pregunta. No aparta la mirada.",
                'pregunta' => '¿Qué cifra das?',
                'opciones' => [
                    'doy_la_real' => 'Doy el número exacto, aunque tarde más en explicarlo',
                    'doy_optimista' => 'Doy el número más rápido, el que ya se mencionó',
                    'pido_tiempo' => 'Pido un minuto más antes de responder',
                ],
            ],
            5 => [
                'narrativa' => "La sala se vacía poco a poco. El turno terminó hace rato, pero nadie se movió hasta tener la trayectoria confirmada en la pantalla central, en verde, estable.\n\n"
                    . "Alguien te palmea el hombro al pasar, sin decir nada. Otro te deja un café frío al lado del teclado, de cuándo, no sabrías decir.\n\n"
                    . "Apagas tu consola. El zumbido de los ventiladores, que llevaba horas de fondo, se nota ahora que casi no queda nadie.\n\n"
                    . "Piensas en el momento en que diste tu número, en la sala en silencio esperando, en si aquello cambió algo o si el resultado hubiera sido el mismo sin ti — si alguien más habría llegado a la misma cifra, dos minutos más tarde, o nunca.\n\n"
                    . "Es una pregunta que te vas a hacer más de una vez esta noche, camino a casa, y probablemente mañana también.",
                'pregunta' => 'Mirando atrás, ¿qué tanto sientes que tu criterio influyó en la decisión final del equipo?',
                'opciones' => [
                    'bastante' => 'Bastante, se notó en la decisión',
                    'poco' => 'Poco, se escuchó pero no cambió mucho',
                    'casi_nada' => 'Casi nada, sentí que ya estaba decidido',
                ],
            ],
        ],
    ],

    'B' => [
        'nombre' => 'Trazador de Rumbo',
        'momentos' => [
            1 => [
                'narrativa' => "Cincuenta y cinco horas de vuelo, y la trayectoria lleva casi todo ese tiempo sin necesitar una sola corrección tuya.\n\n"
                    . "Miras el monitor de actitud casi por costumbre, no porque esperes ver algo.\n\n"
                    . "Y ahí está: la nave gira. Despacio, pero gira — un movimiento que ningún comando ordenó.\n\n"
                    . "Revisas el registro de maniobras. No hay ninguna programada para esta hora. Nadie tocó nada desde tu consola ni desde ninguna otra, según el registro.\n\n"
                    . "Entonces algo, en algún lugar de la nave, la está empujando desde afuera. O desde dentro.\n\n"
                    . "Buscas una explicación simple — un impacto de micrometeorito, tal vez, algo pequeño y sin consecuencias. Las hay, de vez en cuando.\n\n"
                    . "Pero el giro no se detiene. Y no es pequeño.\n\n"
                    . "Todavía no lo has dicho por el canal.",
                'pregunta' => '¿Qué tan preocupante te parece este giro no ordenado?',
                'opciones' => [
                    'nada' => 'Nada, seguro se corrige solo',
                    'algo' => 'Algo, voy a seguir midiéndolo',
                    'mucho' => 'Mucho, hay una fuerza real actuando sobre la nave',
                ],
            ],
            2 => [
                'narrativa' => "Lo reportas justo cuando el Guardián de Energía también está hablando por el canal. Sus palabras y las tuyas casi se cruzan.\n\n"
                    . "«Tenemos un giro no comandado en la actitud de la nave», dices.\n\n"
                    . "Un segundo de silencio, y luego la sala entera empieza a atar cabos: energía que cae, actitud que gira. No son dos problemas. Es uno solo, visto desde dos consolas distintas.\n\n"
                    . "Alguien pregunta si podría ser una falla del giroscopio, no de la nave. Es una pregunta razonable — los giroscopios fallan, y cuando fallan, mienten de formas muy convincentes.\n\n"
                    . "Revisas una vez más los tres sensores redundantes. Los tres dicen lo mismo.\n\n"
                    . "No es el giroscopio.",
                'pregunta' => '¿Cómo presentas esa confirmación al resto del equipo?',
                'opciones' => [
                    'la_afirmo' => 'La afirmo sin rodeos: no es el giroscopio',
                    'la_matizo' => 'La presento con cautela, aunque estoy casi seguro',
                    'espero_a_otros' => 'Espero a que alguien más lo confirme primero',
                ],
            ],
            3 => [
                'narrativa' => "La sacudida llega también a tu consola — la misma que sintieron todos. Y después, la trayectoria que estabas siguiendo deja de tener sentido.\n\n"
                    . "La nave ya no gira sola: ahora se aleja, poco a poco, de la ruta que llevaba planeada desde el lanzamiento.\n\n"
                    . "Hay una fuga. En algún punto del casco, algo escapa al espacio, y ese escape empuja a la nave como un motor pequeño que nadie encendió a propósito.\n\n"
                    . "Hay dos caminos posibles de regreso: uno directo, que exige encender un motor dañado por la misma explosión; otro más largo, que usa la gravedad de un cuerpo cercano para rodear y volver — más lento, pero no depende de nada que pueda estar roto.\n\n"
                    . "Alguien más ya empezó a hablar del camino corto. Es el que todos preferirían, si funcionara.",
                'pregunta' => '¿Qué opción defiendes primero?',
                'opciones' => [
                    'camino_largo' => 'El camino largo, el que no depende del motor dañado',
                    'camino_corto' => 'El camino corto, si hay forma de confirmar que el motor aguanta',
                    'pido_mas_datos' => 'Pido más tiempo antes de defender ninguna de las dos',
                ],
            ],
            4 => [
                'narrativa' => "El Director de Vuelo corta el ruido de la sala con una frase seca, sin levantar la voz:\n\n"
                    . "«No empeoremos esto adivinando. Necesito la cifra real, no la más rápida.»\n\n"
                    . "No te mira solo a ti — mira a toda la fila de consolas de navegación. Pero la pregunta que sigue es para ti.\n\n"
                    . "«¿Cuánto tiempo da el camino largo, si el motor no se puede usar?»\n\n"
                    . "Tienes un número calculado con el procedimiento completo, verificado, pero que tardó veinte minutos en salir. Y tienes una estimación rápida, hecha a ojo, que alguien ya mencionó hace un momento y que nadie ha corregido todavía.\n\n"
                    . "El reloj de la misión sigue corriendo, visible en la pantalla superior.\n\n"
                    . "El Director espera. No aparta la mirada.",
                'pregunta' => '¿Qué número dices?',
                'opciones' => [
                    'el_verificado' => 'El número verificado, aunque ya casi no dé tiempo de decirlo con calma',
                    'el_estimado' => 'El estimado que ya circulaba, para no atrasar más la decisión',
                    'pido_un_minuto' => 'Pido un minuto exacto para terminar de verificarlo',
                ],
            ],
            5 => [
                'narrativa' => "La ruta queda fijada. Verde, estable, en la pantalla central — la misma que miraron todos antes de irse.\n\n"
                    . "Te quedas un rato más, revisando una vez más los números, aunque ya nadie te lo pidió.\n\n"
                    . "Piensas en el momento en que diste tu número — el verificado o el estimado, según lo que hayas elegido — y en si la ruta habría quedado igual sin esa respuesta tuya.\n\n"
                    . "Nadie te lo va a decir con certeza. Es una de esas preguntas que uno se responde solo, de camino a casa.",
                'pregunta' => 'Mirando atrás, ¿qué tanto sientes que tu criterio influyó en la decisión final del equipo?',
                'opciones' => [
                    'bastante' => 'Bastante, se notó en la decisión',
                    'poco' => 'Poco, se escuchó pero no cambió mucho',
                    'casi_nada' => 'Casi nada, sentí que ya estaba decidido',
                ],
            ],
        ],
    ],

    'C' => [
        'nombre' => 'Guardián del Aire',
        'momentos' => [
            1 => [
                'narrativa' => "Cincuenta y cinco horas de vuelo, y tu consola es la más aburrida de la sala. Números que apenas se mueven, guardia tras guardia.\n\n"
                    . "Hasta que uno de los dos tanques principales de oxígeno cae a cero. No despacio. De golpe.\n\n"
                    . "Miras la otra columna, la del tanque redundante — el que existe precisamente para esto, para que uno solo nunca sea un problema.\n\n"
                    . "También está bajando. Más lento que el primero, pero bajando.\n\n"
                    . "Eso no debería pasar nunca. Los dos tanques no comparten nada; una falla en uno no debería tocar al otro.\n\n"
                    . "Revisas la lectura tres veces, buscando el error que la explique. No lo encuentras.\n\n"
                    . "El aire que respiran tres personas, a cientos de miles de kilómetros de cualquier otra fuente, se está yendo por alguna parte.\n\n"
                    . "Todavía no lo has dicho.",
                'pregunta' => '¿Qué tan preocupante te parece esta doble caída?',
                'opciones' => [
                    'poco' => 'Poco, seguro es un error de lectura',
                    'algo' => 'Algo, voy a seguir vigilando los dos tanques',
                    'mucho' => 'Mucho, ambos tanques están comprometidos',
                ],
            ],
            2 => [
                'narrativa' => "Lo dices apenas confirmas la segunda lectura, sin esperar más.\n\n"
                    . "«El tanque de reserva también está cayendo.»\n\n"
                    . "Esta vez nadie duda ni pide confirmar dos veces. El resto de la sala ya está hablando de energía, de un giro raro en la actitud — y tu reporte hace que varias piezas sueltas empiecen a encajar en una sola imagen, y no es buena.\n\n"
                    . "El Director de Vuelo pregunta cuánto tiempo de aire queda si el tanque de reserva sigue cayendo a este ritmo.\n\n"
                    . "Haces el cálculo. Da un número más corto de lo que te gustaría decir en voz alta.\n\n"
                    . "Alguien sugiere que quizás se estabilice antes de llegar a cero. Es posible. También es una esperanza, no un dato.",
                'pregunta' => '¿Cómo das el cálculo del tiempo de aire restante?',
                'opciones' => [
                    'el_numero_corto' => 'Doy el número tal como salió, aunque sea corto e incómodo',
                    'con_margen' => 'Lo doy con un margen optimista, por si se estabiliza',
                    'lo_pospongo' => 'Digo que necesito un poco más de tiempo para confirmarlo',
                ],
            ],
            3 => [
                'narrativa' => "La sacudida que sintieron todos también movió algo en tu diagrama: ahora entiendes por qué caían los dos tanques a la vez. No son dos fallas. Es una sola, en el compartimento que los conecta.\n\n"
                    . "Y con esa pieza nueva llega otra, peor: seguir en la nave principal, tal como está, ya no es una opción para todo el trayecto de regreso.\n\n"
                    . "Queda un lugar donde protegerse — un módulo más pequeño, pensado para dos personas durante dos días, no para tres durante el tiempo que va a tomar volver por el camino largo.\n\n"
                    . "Haces el cálculo de cuánto puede estirarse el aire de ese módulo si lo usan como refugio. El número que te sale es ajustado. Muy ajustado.\n\n"
                    . "Otra persona en la sala, mirando el mismo problema desde el ángulo de la energía, ya está diciendo un número distinto al tuyo.",
                'pregunta' => 'Tu número de margen de aire no coincide con el que se acaba de decir. ¿Qué haces?',
                'opciones' => [
                    'corrijo_ya' => 'Interrumpo para aclarar la diferencia de inmediato',
                    'espero_pausa' => 'Espero un momento para explicarlo con calma',
                    'lo_dejo' => 'Asumo que el otro cálculo probablemente incluye algo que a mí se me escapó',
                ],
            ],
            4 => [
                'narrativa' => "El Director de Vuelo corta el ruido de la sala con una frase seca, sin levantar la voz:\n\n"
                    . "«No empeoremos esto adivinando. Necesito la cifra real, no la más rápida.»\n\n"
                    . "Esta vez la pregunta llega directo a ti, antes que a nadie más: «¿Cuánto aire real tienen, en el peor caso?»\n\n"
                    . "Tienes un número calculado con todos los márgenes de seguridad que exige el procedimiento, y tienes otro, más generoso, que ya mencionaste minutos atrás sin pensarlo del todo.\n\n"
                    . "El reloj de la misión sigue corriendo. Todos en la sala lo saben sin mirarlo.\n\n"
                    . "El Director espera. No repite la pregunta.",
                'pregunta' => '¿Qué número das?',
                'opciones' => [
                    'el_del_peor_caso' => 'Doy el número del peor caso, aunque sea el más duro de escuchar',
                    'el_generoso' => 'Doy el número más generoso, el que ya circulaba',
                    'pido_un_minuto' => 'Pido un minuto para recalcularlo con los márgenes completos',
                ],
            ],
            5 => [
                'narrativa' => "El refugio improvisado sostiene a los tres. Justo. Pero sostiene.\n\n"
                    . "Te quedas mirando la última lectura de aire, la que finalmente dejó de caer.\n\n"
                    . "Piensas en el número que diste — el duro o el generoso, según lo que hayas elegido — y en qué habría pasado si hubieras dado el otro.\n\n"
                    . "No es una pregunta cómoda. Tampoco es una que puedas evitarte esta noche.",
                'pregunta' => 'Mirando atrás, ¿qué tanto sientes que tu criterio influyó en la decisión final del equipo?',
                'opciones' => [
                    'bastante' => 'Bastante, se notó en la decisión',
                    'poco' => 'Poco, se escuchó pero no cambió mucho',
                    'casi_nada' => 'Casi nada, sentí que ya estaba decidido',
                ],
            ],
        ],
    ],

    'D' => [
        'nombre' => 'Voz de la Tripulación',
        'momentos' => [
            1 => [
                'narrativa' => "Cincuenta y cinco horas de vuelo, y la última transmisión de la tripulación fue una broma sobre la comida reconstituida. Todo normal.\n\n"
                    . "Entonces la radio trae otra cosa: un golpe seco, de fondo, seguido de un silencio de dos segundos que dura demasiado.\n\n"
                    . "Después, una voz —tranquila, entrenada para sonar tranquila incluso cuando no lo está— dice algo que no estaba en ningún guión: «Aquí hemos tenido un problema».\n\n"
                    . "No dice qué problema. No lo sabe todavía, o no quiere decirlo así, en abierto, sin estar seguro.\n\n"
                    . "Preguntas, con la calma que te enseñaron a usar en este micrófono, qué tipo de problema.\n\n"
                    . "La respuesta tarda más de lo normal en llegar. Cuando llega, la voz suena distinta. Más despacio. Eligiendo cada palabra.\n\n"
                    . "Todavía no se lo has dicho a nadie más en la sala — apenas estás procesando lo que acabas de escuchar.",
                'pregunta' => 'Solo por el tono de esa voz, ¿qué tan grave crees que es lo que están viviendo allá arriba?',
                'opciones' => [
                    'poco' => 'Poco, suenan controlados',
                    'algo' => 'Algo, hay algo que no están diciendo del todo',
                    'mucho' => 'Mucho, ese silencio y ese tono no son normales',
                ],
            ],
            2 => [
                'narrativa' => "Lo repites por el canal interno, palabra por palabra, tal como lo oíste — incluida la pausa.\n\n"
                    . "La sala reacciona rápido: alguien menciona la caída de energía, alguien más el giro raro de la nave. Tu reporte social conecta con datos técnicos que ya estaban sobre la mesa.\n\n"
                    . "El Director de Vuelo te pide que vuelvas a preguntarle a la tripulación qué ven, qué sienten, qué no está en los números.\n\n"
                    . "Vuelves al micrófono. Esta vez la voz que responde suena más cansada. Menciona un ruido que ya no se repite, una luz que se apagó y no volvió, y después, casi al final, algo que no le habían preguntado: que sintieron miedo, un segundo, antes de que el entrenamiento tomara el control otra vez.\n\n"
                    . "Es información que no cabe en ninguna columna de números.",
                'pregunta' => '¿Cómo trasladas ese último detalle —el miedo, sin que se los preguntaras— al resto del equipo?',
                'opciones' => [
                    'lo_digo_completo' => 'Lo repito completo, tal como lo dijeron',
                    'lo_resumo_tecnico' => 'Lo resumo como dato de estado, sin el detalle emocional',
                    'no_lo_menciono' => 'No lo menciono, no me parece relevante para la sala técnica',
                ],
            ],
            3 => [
                'narrativa' => "La sacudida que sintió toda la sala, la tripulación la sintió primero, minutos antes, en su propio cuerpo.\n\n"
                    . "Cuando por fin se confirma abajo lo que arriba ya sabían —un tanque perdido, una fuga real— la voz que responde por radio ya no suena tranquila del todo. Suena precisa. Corta. Al punto.\n\n"
                    . "Te piden, sin rodeos, que les digas la verdad completa de lo que se sabe en tierra, no una versión suavizada para que no se preocupen.\n\n"
                    . "Tienes delante dos versiones del mismo reporte: la que dice todo lo que sabe la sala, incluida la parte donde todavía no hay plan; y la que da la parte confirmada y deja el resto para más adelante.\n\n"
                    . "El micrófono está abierto. Están esperando.",
                'pregunta' => '¿Qué versión transmites?',
                'opciones' => [
                    'todo' => 'Todo lo que sabemos, incluso lo que aún no tiene plan',
                    'dosificada' => 'La parte confirmada, y el resto más adelante',
                    'pregunto_al_director' => 'Pido un momento para consultar antes de responder',
                ],
            ],
            4 => [
                'narrativa' => "El Director de Vuelo corta el ruido de la sala con una frase seca, sin levantar la voz:\n\n"
                    . "«No empeoremos esto adivinando. Necesito la cifra real, no la más rápida.»\n\n"
                    . "Te mira a ti también, aunque tu trabajo no maneja cifras técnicas. «¿Qué les vas a decir, exactamente, cuando vuelvas a abrir el canal?»\n\n"
                    . "Arriba, la tripulación espera una respuesta a una pregunta suya, todavía sin contestar. Abajo, el plan de regreso ni siquiera está cerrado del todo.\n\n"
                    . "Tienes que decir algo, y pronto, aunque no sea la respuesta final.\n\n"
                    . "El Director espera tu respuesta. No repite la pregunta.",
                'pregunta' => '¿Qué les dices?',
                'opciones' => [
                    'plan_en_marcha' => 'Que ya hay un plan en marcha, aunque falten detalles por confirmar',
                    'tranquilizo' => 'Algo que los tranquilice, aunque no sea toda la información',
                    'pido_tiempo_al_director' => 'Le pido al Director cinco minutos más antes de volver a hablarles',
                ],
            ],
            5 => [
                'narrativa' => "La última transmisión antes del reingreso es corta. Alguien allá arriba dice, casi de pasada, «gracias por no dejarnos solos en el silencio».\n\n"
                    . "No sabes si se referían a algo que dijiste tú específicamente, o a la sala entera.\n\n"
                    . "Piensas en la versión que elegiste darles —completa, dosificada, o la que hayas decidido— y en si eso cambió algo en cómo sobrellevaron las horas que faltaban.\n\n"
                    . "Es difícil saberlo desde una consola de tierra.",
                'pregunta' => 'Mirando atrás, ¿qué tanto sientes que tu criterio influyó en la decisión final del equipo?',
                'opciones' => [
                    'bastante' => 'Bastante, se notó en la decisión',
                    'poco' => 'Poco, se escuchó pero no cambió mucho',
                    'casi_nada' => 'Casi nada, sentí que ya estaba decidido',
                ],
            ],
        ],
    ],

    'E' => [
        'nombre' => 'Ojo de Sala',
        'momentos' => [
            1 => [
                'narrativa' => "Tu trabajo no es mirar un instrumento. Es mirar a las personas que miran los instrumentos.\n\n"
                    . "Cincuenta y cinco horas de vuelo, y la sala tiene el ritmo tranquilo de cualquier turno sin sobresaltos: alguien bosteza, alguien más comenta algo intrascendente por el canal privado.\n\n"
                    . "Entonces cambia. No de golpe —vas notando, uno por uno, los gestos: alguien se endereza en la silla, alguien más se queda quieto mirando su pantalla más tiempo del normal, alguien baja la voz para decirle algo al compañero de al lado en vez de decirlo por el canal general.\n\n"
                    . "Tres personas, tres reacciones distintas, ningún reporte oficial todavía.\n\n"
                    . "Es tu trabajo notar esto antes de que se convierta en un problema de coordinación. Todavía no lo es. Casi.\n\n"
                    . "Anotas la hora en tu propia bitácora, la que nadie más lee.",
                'pregunta' => '¿Qué tan relevante te parece esta tensión silenciosa que estás viendo, antes de que nadie diga nada oficial?',
                'opciones' => [
                    'poco' => 'Poco, es normal que haya variación de un turno a otro',
                    'algo' => 'Algo, vale la pena seguir observando de cerca',
                    'mucho' => 'Mucho, esto suele ser la antesala de algo más grande',
                ],
            ],
            2 => [
                'narrativa' => "Los reportes empiezan a llegar, uno detrás de otro: energía, actitud de la nave, aire. Cada especialista habla desde su propia consola, mirando su propia pantalla.\n\n"
                    . "Notas algo que los datos no muestran: nadie está mirando a nadie más. Cada quien reporta su pieza y vuelve a su pantalla, sin conectar en voz alta lo que empieza a ser evidente para cualquiera que mire el conjunto.\n\n"
                    . "Podrías decir algo —nombrar en voz alta que las piezas sueltas empiezan a formar una sola imagen, y que nadie lo ha dicho todavía.\n\n"
                    . "O podrías esperar a que alguien con más peso técnico lo diga primero, para no interrumpir un flujo que, a su manera, está funcionando.",
                'pregunta' => '¿Qué haces con esa observación?',
                'opciones' => [
                    'lo_digo' => 'Lo digo en voz alta: esto es un solo problema, no varios',
                    'lo_sugiero_en_privado' => 'Se lo comento en privado a alguien, no por el canal general',
                    'me_quedo_observando' => 'Me quedo observando, no es mi lugar para decirlo',
                ],
            ],
            3 => [
                'narrativa' => "La sacudida agita también algo en la sala misma: por un segundo, todos hablan a la vez, y al segundo siguiente, nadie habla.\n\n"
                    . "En ese silencio ves con más claridad que en cualquier reporte: quién se queda mirando fijo su pantalla sin decir nada, quién busca la mirada del Director de Vuelo esperando instrucciones, quién ya está calculando la siguiente pregunta antes de que se la hagan.\n\n"
                    . "También ves algo más pequeño, más fácil de perder: una de las voces que minutos antes hablaba con seguridad, ahora responde más corto, más bajo, como si se estuviera guardando algo.\n\n"
                    . "Nadie más en la sala parece haberlo notado todavía.",
                'pregunta' => '¿Qué haces con lo que acabas de notar en esa voz que se apagó un poco?',
                'opciones' => [
                    'lo_señalo' => 'Lo señalo, con tacto, para que esa persona vuelva a hablar con la misma claridad de antes',
                    'lo_guardo_para_despues' => 'Lo guardo para comentarlo después del cierre del ejercicio, no ahora',
                    'no_hago_nada' => 'No hago nada, no quiero exponer a nadie frente al grupo',
                ],
            ],
            4 => [
                'narrativa' => "El Director de Vuelo corta el ruido de la sala con una frase seca, sin levantar la voz:\n\n"
                    . "«No empeoremos esto adivinando. Necesito la cifra real, no la más rápida.»\n\n"
                    . "Ves, mejor que nadie en esa sala, lo que esa frase provoca: dos o tres personas se enderezan, buscando dar la respuesta perfecta; otra se hace, visiblemente, más pequeña en su silla.\n\n"
                    . "Es exactamente el momento en que, si alguien va a callarse algo importante por no sentirse con autoridad para decirlo, va a pasar ahora.\n\n"
                    . "Tienes un canal directo con el Director, uno que casi nunca usas.\n\n"
                    . "Podrías decirle, en ese mismo instante, lo que estás viendo en la sala —no un dato técnico, sino el estado del equipo. O podrías dejar que la sala resuelva esto sola, como lo ha hecho hasta ahora.",
                'pregunta' => '¿Qué haces?',
                'opciones' => [
                    'aviso_al_director' => 'Le aviso al Director, en privado, que alguien puede estar callando algo por presión',
                    'confio_en_el_equipo' => 'Confío en que el equipo lo va a manejar sin que yo intervenga',
                    'espero_a_ver_que_pasa' => 'Espero un poco más antes de decidir si intervengo',
                ],
            ],
            5 => [
                'narrativa' => "La sala vuelve, poco a poco, al ritmo tranquilo de antes —aunque nadie que estuvo ahí va a recordarlo como un turno cualquiera.\n\n"
                    . "Repasas tu bitácora privada: la hora en que notaste la primera tensión, la voz que se apagó un poco en el momento de más presión, lo que decidiste hacer con eso.\n\n"
                    . "Piensas en si tu observación —la que nadie pidió, pero tú diste de todas formas— cambió algo en cómo terminó la sala esa noche, o si simplemente fuiste testigo de algo que iba a resolverse igual, con o sin ti mirando.\n\n"
                    . "Es la pregunta que más te cuesta responder de todo el turno.",
                'pregunta' => 'Mirando atrás, ¿qué tanto sientes que tu criterio influyó en la decisión final del equipo?',
                'opciones' => [
                    'bastante' => 'Bastante, se notó en la decisión',
                    'poco' => 'Poco, se escuchó pero no cambió mucho',
                    'casi_nada' => 'Casi nada, sentí que ya estaba decidido',
                ],
            ],
        ],
    ],
];
