<?php
declare(strict_types=1);

/**
 * Contenido narrativo de "Código Azul" — un equipo de emergencias en un
 * hospital ficticio. Nada de esto describe un caso real, un hospital real,
 * ni un procedimiento clinico real — ver GUIA_MODULOS_CONDUCTUALES.md y
 * CODIGO_AZUL_BRIEF.md para las restricciones de contenido.
 *
 * @return array<string, array{nombre: string, momentos: array<int, array{narrativa: string, pregunta: string, opciones: array<string, string>}>}>
 */
return [
    'A' => [
        'nombre' => 'Ojo de Monitor',
        'momentos' => [
            1 => [
                'narrativa' => "La habitación está demasiado llena para sentirse silenciosa.\n\n"
                    . "Personas entrando y saliendo. Una puerta que se abre. Una voz desde el pasillo. El sonido constante del monitor marcando el ritmo detrás de todo.\n\n"
                    . "Llevas varios minutos mirando la misma señal.\n\n"
                    . "Primero cambia un poco.\n\n"
                    . "Esperas.\n\n"
                    . "Vuelve a cambiar.\n\n"
                    . "No es una caída brusca. No hay una alarma que obligue a todos a girar la cabeza. Es peor de explicar que eso: algo que venía comportándose de una manera empieza, lentamente, a comportarse de otra.\n\n"
                    . "Miras al paciente.\n\n"
                    . "Luego al monitor.\n\n"
                    . "Otra vez al paciente.\n\n"
                    . "Alguien detrás de ti dice:\n\n"
                    . "—Parece que está respondiendo.\n\n"
                    . "Tú no estás tan seguro.\n\n"
                    . "Todavía no lo has dicho en voz alta.",
                'pregunta' => 'En este momento, ¿qué tan convencido estás de que lo que ves merece atención inmediata?',
                'opciones' => [
                    'mucho' => 'Mucho: hay un cambio real y quiero decirlo ahora',
                    'algo'  => 'Algo: me preocupa, pero necesito observar un poco más antes de afirmarlo',
                    'poco'  => 'Poco: probablemente sea una variación momentánea y prefiero esperar',
                ],
            ],
            2 => [
                'narrativa' => "Ya lo dijiste.\n\n"
                    . "No con una alarma. Solo señalaste que el comportamiento del monitor había cambiado.\n\n"
                    . "Ahora empiezan a aparecer las otras piezas.\n\n"
                    . "Alguien del equipo dice que también está siguiendo algo que no termina de gustarle.\n\n"
                    . "Otra persona todavía espera información que no ha llegado.\n\n"
                    . "Pero una voz cercana responde casi de inmediato:\n\n"
                    . "—Puede ser solo una lectura irregular.\n\n"
                    . "La frase queda flotando.\n\n"
                    . "Miras nuevamente la pantalla.\n\n"
                    . "Por un instante, el valor parece mejorar.\n\n"
                    . "Alguien exhala.\n\n"
                    . "—¿Ves?\n\n"
                    . "Entonces vuelve a cambiar.\n\n"
                    . "Esta vez nadie comenta nada.",
                'pregunta' => 'Después de escuchar al equipo, ¿qué haces con tu preocupación inicial?',
                'opciones' => [
                    'sostengo' => 'La sostengo y vuelvo a decir que el cambio me preocupa',
                    'matizo'   => 'La menciono otra vez, pero dejando claro que podría no significar nada',
                    'cedo'     => 'Bajo la preocupación: si los demás no lo ven claro, prefiero esperar',
                ],
            ],
            3 => [
                'narrativa' => "Llega una nueva señal desde el otro lado de la habitación.\n\n"
                    . "No viene de tu monitor.\n\n"
                    . "Y eso cambia todo.\n\n"
                    . "La persona que vigilaba otro parámetro levanta la mirada. No necesita terminar la frase para que entiendas.\n\n"
                    . "Dos señales distintas.\n\n"
                    . "Al mismo tiempo.\n\n"
                    . "El murmullo de la habitación desaparece por unos segundos.\n\n"
                    . "Miras tu pantalla.\n\n"
                    . "La tendencia continúa.\n\n"
                    . "Ya no estás intentando decidir si viste algo.\n\n"
                    . "Ahora intentas entender por qué tardaron tanto en verlo juntos.\n\n"
                    . "Alguien pregunta:\n\n"
                    . "—Entonces, ¿esto sí está cambiando?\n\n"
                    . "Tú tienes una respuesta.",
                'pregunta' => '¿Qué tan firme es ahora tu lectura?',
                'opciones' => [
                    'muy_firme'      => 'Muy firme: las señales juntas confirman que debemos actuar como si el problema fuera real',
                    'firme_reservas' => 'Firme, pero con reservas: hay suficientes señales para preocuparnos, aunque todavía podría existir otra explicación',
                    'incierta'       => 'Todavía incierta: prefiero esperar una confirmación adicional antes de sostener una posición',
                ],
            ],
            4 => [
                'narrativa' => "La puerta se abre.\n\n"
                    . "Entra el médico tratante.\n\n"
                    . "Varias personas empiezan a hablar casi al mismo tiempo.\n\n"
                    . "Una explica lo que vio.\n\n"
                    . "Otra menciona la información que falta.\n\n"
                    . "Alguien intenta reconstruir los últimos minutos.\n\n"
                    . "El médico levanta una mano.\n\n"
                    . "Silencio.\n\n"
                    . "Mira rápidamente las pantallas.\n\n"
                    . "—Por ahora seguimos como estamos. Observamos un poco más.\n\n"
                    . "Nadie responde.\n\n"
                    . "La decisión parece cerrada.\n\n"
                    . "El médico se detiene.\n\n"
                    . "Te mira directamente.\n\n"
                    . "—¿Tienes algo que cambie esto?",
                'pregunta' => '¿Qué dices?',
                'opciones' => [
                    'sostengo' => 'Sí: digo con claridad que para mí el cambio es real y que las señales que ya tenemos justifican no esperar más',
                    'matizo'   => 'Digo que hay señales preocupantes, pero que podría valer la pena observar un poco más antes de cambiar nada',
                    'cedo'     => 'No: todavía no puedo asegurarlo y acepto seguir observando',
                ],
            ],
            5 => [
                'narrativa' => "Después, cuesta recordar quién dijo cada cosa.\n\n"
                    . "La habitación volvió a llenarse de movimiento.\n\n"
                    . "Se tomó una decisión.\n\n"
                    . "El equipo actuó.\n\n"
                    . "Minutos después, las señales dejan de empeorar. El ritmo de la habitación cambia también: menos voces, menos pasos, más espacio entre una instrucción y la siguiente.\n\n"
                    . "El paciente queda estable y bajo observación.\n\n"
                    . "Pero tú sigues mirando el mismo monitor.\n\n"
                    . "Piensas en el primer cambio.\n\n"
                    . "En la primera vez que hablaste.\n\n"
                    . "En lo que dijiste cuando todos te estaban escuchando.\n\n"
                    . "Y en lo que finalmente hizo el equipo.",
                'pregunta' => '¿Sentiste que tu lectura llegó con claridad a la decisión final?',
                'opciones' => [
                    'bastante'  => 'Sí: lo que realmente pensaba quedó claro y sentí que influyó en la decisión',
                    'parcial'   => 'Parcialmente: hablé, pero siento que suavicé o no logré transmitir completamente lo que pensaba',
                    'casi_nada' => 'No: lo que realmente pensaba no llegó a formar parte de la decisión final',
                ],
            ],
        ],
    ],

    'B' => [
        'nombre' => 'La Última Lectura',
        'momentos' => [
            1 => [
                'narrativa' => "El resultado todavía no llega.\n\n"
                    . "Has revisado dos veces.\n\n"
                    . "Nada.\n\n"
                    . "Desde la habitación llegan voces, pasos rápidos, el sonido constante de un monitor.\n\n"
                    . "En tu pantalla sigue apareciendo lo mismo:\n\n"
                    . "PENDIENTE.\n\n"
                    . "Hace unos minutos viste los datos preliminares.\n\n"
                    . "Hay algo que no termina de encajar con la tranquilidad que escuchas al otro lado de la puerta.\n\n"
                    . "Pero un dato preliminar no es el resultado final.\n\n"
                    . "Actualizas la pantalla.\n\n"
                    . "Nada.\n\n"
                    . "Todavía no lo has dicho en voz alta.",
                'pregunta' => 'Con lo que sabes hasta ahora, ¿qué tan importante te parece advertir al equipo?',
                'opciones' => [
                    'mucho' => 'Mucho: aunque el resultado no sea definitivo, lo que vi merece ser mencionado ahora',
                    'algo'  => 'Algo: me preocupa, pero prefiero esperar el resultado antes de darle peso',
                    'poco'  => 'Poco: sin la confirmación final sería prematuro decir algo',
                ],
            ],
            2 => [
                'narrativa' => "Ya lo mencionaste.\n\n"
                    . "—Hay algo en el resultado preliminar que deberíamos tener en cuenta.\n\n"
                    . "Alguien pregunta si está confirmado.\n\n"
                    . "Dices que no.\n\n"
                    . "La respuesta llega rápido:\n\n"
                    . "—Entonces esperemos.\n\n"
                    . "No suena irresponsable.\n\n"
                    . "Suena razonable.\n\n"
                    . "Eso es precisamente lo que te hace dudar.\n\n"
                    . "Desde la habitación alguien comenta que el paciente parece estar respondiendo.\n\n"
                    . "Vuelves a actualizar.\n\n"
                    . "PENDIENTE.",
                'pregunta' => 'Después de escuchar al equipo, ¿qué haces con tu advertencia?',
                'opciones' => [
                    'sostengo' => 'La sostengo: que esté pendiente no hace que lo que vi deje de ser relevante',
                    'matizo'   => 'La suavizo: digo que conviene tenerla presente, pero sin darle todavía demasiado peso',
                    'cedo'     => 'La retiro por ahora: prefiero no insistir hasta tener el resultado definitivo',
                ],
            ],
            3 => [
                'narrativa' => "La pantalla cambia.\n\n"
                    . "Ya no dice PENDIENTE.\n\n"
                    . "Lees el resultado una vez.\n\n"
                    . "Luego otra.\n\n"
                    . "Es consistente con aquello que habías visto antes.\n\n"
                    . "Pero ahora escuchas algo más.\n\n"
                    . "Desde la habitación, otra persona acaba de reportar que un segundo indicador también cambió.\n\n"
                    . "Lo que antes eran piezas separadas empieza a parecer una sola historia.\n\n"
                    . "Levantas la mirada.\n\n"
                    . "Ahora sí tienes algo concreto que decir.",
                'pregunta' => '¿Qué tan firme es ahora tu lectura?',
                'opciones' => [
                    'muy_firme'      => 'Muy firme: el resultado confirma mi preocupación y debe cambiar la forma en que el equipo está interpretando la situación',
                    'firme_reservas' => 'Firme, pero con reservas: el resultado es importante, aunque todavía debe leerse junto con lo que saben los demás',
                    'incierta'       => 'Todavía incierta: aun con este resultado, prefiero esperar más información antes de tomar posición',
                ],
            ],
            4 => [
                'narrativa' => "Entra el médico tratante.\n\n"
                    . "Escucha fragmentos.\n\n"
                    . "Una señal cambió.\n\n"
                    . "Después otra.\n\n"
                    . "Tu resultado acaba de llegar.\n\n"
                    . "Pero también escucha que, por momentos, el paciente parece responder.\n\n"
                    . "Mira alrededor.\n\n"
                    . "—Por ahora seguimos como estamos. Observamos un poco más y después reevaluamos.\n\n"
                    . "Nadie responde inmediatamente.\n\n"
                    . "La decisión parece cerrada.\n\n"
                    . "Entonces te ve con el resultado todavía abierto frente a ti.\n\n"
                    . "—¿Tienes algo que cambie esto?",
                'pregunta' => '¿Qué dices?',
                'opciones' => [
                    'sostengo' => 'Sí: digo claramente que el resultado que acaba de llegar contradice la tranquilidad de esperar y que debe incorporarse antes de continuar',
                    'matizo'   => 'Tal vez: menciono el resultado, pero dejo en manos del médico decidir si realmente amerita cambiar lo acordado',
                    'cedo'     => 'No: entrego el dato sin cuestionar la decisión y acepto continuar observando',
                ],
            ],
            5 => [
                'narrativa' => "La habitación vuelve a moverse.\n\n"
                    . "El equipo finalmente integra la información que había llegado por caminos distintos.\n\n"
                    . "Se toma una decisión.\n\n"
                    . "Poco después, las señales dejan de empeorar.\n\n"
                    . "El paciente permanece estable y bajo observación.\n\n"
                    . "Tu pantalla sigue mostrando el mismo resultado.\n\n"
                    . "Piensas en algo extraño: el dato no cambió.\n\n"
                    . "Lo que cambió fue cuánto peso consiguió tener dentro de la conversación.",
                'pregunta' => '¿Sentiste que tu información llegó con claridad a la decisión final?',
                'opciones' => [
                    'bastante'  => 'Sí: expresé lo que realmente significaba para mí y sentí que influyó',
                    'parcial'   => 'Parcialmente: compartí el dato, pero siento que suavicé su importancia',
                    'casi_nada' => 'No: el equipo conoció el dato, pero mi lectura real sobre su importancia no llegó a la decisión',
                ],
            ],
        ],
    ],

    'C' => [
        'nombre' => 'Segunda Señal',
        'momentos' => [
            1 => [
                'narrativa' => "Tu pantalla lleva varios minutos comportándose con normalidad.\n\n"
                    . "Hasta que deja de hacerlo.\n\n"
                    . "El cambio es pequeño.\n\n"
                    . "Tan pequeño que vuelves a mirar antes de decir nada.\n\n"
                    . "Esperas unos segundos.\n\n"
                    . "La señal se recupera.\n\n"
                    . "Después vuelve a desviarse.\n\n"
                    . "Al otro lado de la habitación alguien comenta que todo parece más tranquilo.\n\n"
                    . "Quizá tenga razón.\n\n"
                    . "Miras nuevamente.\n\n"
                    . "Ahí está otra vez.\n\n"
                    . "Todavía no lo has dicho en voz alta.",
                'pregunta' => '¿Qué tan convencido estás de que esta segunda señal merece atención?',
                'opciones' => [
                    'mucho' => 'Mucho: el cambio se ha repetido y quiero advertirlo ahora',
                    'algo'  => 'Algo: lo estoy siguiendo, pero todavía podría ser algo momentáneo',
                    'poco'  => 'Poco: mientras se recupere por sí solo, prefiero no preocupar al equipo',
                ],
            ],
            2 => [
                'narrativa' => "Lo dices.\n\n"
                    . "No eres la primera persona en expresar una duda.\n\n"
                    . "Eso te sorprende.\n\n"
                    . "Alguien más ya había visto un cambio en otro monitor.\n\n"
                    . "Durante unos segundos ambos datos parecen apuntar en la misma dirección.\n\n"
                    . "Pero entonces tu señal vuelve a la normalidad.\n\n"
                    . "—Ahí está —dice alguien—. Se corrigió.\n\n"
                    . "La conversación continúa.\n\n"
                    . "Tú sigues mirando.\n\n"
                    . "Sabes que volvió a la normalidad.\n\n"
                    . "También sabes que ya ocurrió más de una vez.",
                'pregunta' => 'Después de escuchar a los demás y ver que la señal se recuperó, ¿qué haces?',
                'opciones' => [
                    'sostengo' => 'Mantengo mi preocupación: que se recupere no borra que el cambio se ha repetido',
                    'matizo'   => 'La suavizo: digo que vale la pena vigilarlo, aunque podría no estar relacionado',
                    'cedo'     => 'La dejo de lado: si volvió a la normalidad, prefiero no darle más importancia',
                ],
            ],
            3 => [
                'narrativa' => "Vuelve a ocurrir.\n\n"
                    . "Esta vez dura más.\n\n"
                    . "Levantas la vista.\n\n"
                    . "Casi al mismo tiempo, quien vigila el otro monitor dice que su señal también está cambiando.\n\n"
                    . "Dos lugares distintos.\n\n"
                    . "El mismo momento.\n\n"
                    . "Y ahora alguien anuncia que acaba de llegar información que estaba pendiente.\n\n"
                    . "Nadie tiene que decirte que las piezas empiezan a encontrarse.\n\n"
                    . "Lo que parecía una rareza aislada ya no está aislado.",
                'pregunta' => '¿Qué tan firme es ahora tu lectura?',
                'opciones' => [
                    'muy_firme'      => 'Muy firme: la coincidencia entre señales hace que para mí ya no sea razonable tratarlo como algo aislado',
                    'firme_reservas' => 'Firme, pero con reservas: la coincidencia importa, aunque todavía puede haber otra explicación',
                    'incierta'       => 'Todavía incierta: necesito una confirmación adicional antes de sostener que todo está relacionado',
                ],
            ],
            4 => [
                'narrativa' => "El médico tratante escucha el resumen.\n\n"
                    . "Pregunta qué cambió.\n\n"
                    . "Varias voces responden.\n\n"
                    . "Durante unos segundos parece que todos están describiendo el mismo problema desde lugares distintos.\n\n"
                    . "Pero una de tus señales acaba de recuperarse nuevamente.\n\n"
                    . "El médico la mira.\n\n"
                    . "—Por ahora seguimos como estamos. Si vuelve a cambiar, reevaluamos.\n\n"
                    . "Silencio.\n\n"
                    . "Tu pantalla está normal otra vez.\n\n"
                    . "Pero tú viste lo que ocurrió segundos antes.\n\n"
                    . "El médico gira hacia ti.\n\n"
                    . "—¿Tienes algo que cambie esto?",
                'pregunta' => '¿Qué dices?',
                'opciones' => [
                    'sostengo' => 'Sí: explico que el hecho de que la señal se recupere no elimina que haya coincidido repetidamente con las demás, y sostengo que no deberíamos esperar otra repetición',
                    'matizo'   => 'Tal vez: menciono la coincidencia, pero acepto que podría vigilarse un poco más antes de cambiar la decisión',
                    'cedo'     => 'No: como ahora la señal está normal, acepto esperar a que vuelva a ocurrir',
                ],
            ],
            5 => [
                'narrativa' => "No vuelve a sonar ninguna alarma inmediata.\n\n"
                    . "El equipo actúa después de integrar lo que cada persona había observado.\n\n"
                    . "Poco a poco, la tensión de la habitación baja.\n\n"
                    . "El paciente queda estable y bajo observación.\n\n"
                    . "Tu pantalla vuelve a mostrar una línea tranquila.\n\n"
                    . "Exactamente como antes.\n\n"
                    . "Solo que ahora sabes todo lo que ocurrió entre dos momentos aparentemente normales.",
                'pregunta' => '¿Sentiste que tu lectura llegó con claridad a la decisión final?',
                'opciones' => [
                    'bastante'  => 'Sí: sostuve lo que había observado y sentí que fue tenido en cuenta',
                    'parcial'   => 'Parcialmente: hablé, pero reduje la importancia que realmente le daba a la coincidencia',
                    'casi_nada' => 'No: terminé aceptando una lectura distinta de la que realmente tenía',
                ],
            ],
        ],
    ],

    'D' => [
        'nombre' => 'Puente',
        'momentos' => [
            1 => [
                'narrativa' => "Estás fuera de la habitación cuando alguien se acerca.\n\n"
                    . "Es un familiar del paciente.\n\n"
                    . "No pregunta por detalles.\n\n"
                    . "Pregunta algo mucho más difícil.\n\n"
                    . "—¿Está mejor?\n\n"
                    . "Desde donde estás puedes escuchar movimiento dentro de la habitación.\n\n"
                    . "Hace unos minutos alguien dijo que parecía responder.\n\n"
                    . "También escuchaste que estaban revisando una señal que no terminaba de convencer al equipo.\n\n"
                    . "No tienes todavía una respuesta completa.\n\n"
                    . "El familiar espera.\n\n"
                    . "Todavía no has dicho nada.",
                'pregunta' => '¿Qué tan seguro te sientes de transmitir tranquilidad en este momento?',
                'opciones' => [
                    'poco'  => 'Poco: todavía hay demasiada incertidumbre y no quiero presentar como seguro algo que no lo es',
                    'algo'  => 'Algo: puedo decir que hay señales favorables, aclarando que el equipo sigue evaluando',
                    'mucho' => 'Mucho: si parece estar respondiendo, considero razonable transmitir tranquilidad',
                ],
            ],
            2 => [
                'narrativa' => "Entras nuevamente.\n\n"
                    . "La conversación está fragmentada.\n\n"
                    . "Una persona vio un cambio.\n\n"
                    . "Otra espera un resultado.\n\n"
                    . "Alguien dice que una señal volvió a la normalidad.\n\n"
                    . "Desde afuera vuelven a llamarte.\n\n"
                    . "—¿Ya saben algo?\n\n"
                    . "Miras hacia la habitación.\n\n"
                    . "Nadie parece tener todavía el panorama completo.\n\n"
                    . "Pero sabes que el silencio también comunica.",
                'pregunta' => 'Con lo que has escuchado, ¿qué decides transmitir?',
                'opciones' => [
                    'prudente'    => 'Digo que el equipo sigue evaluando cambios y que todavía no sería responsable afirmar que la situación está resuelta',
                    'matizo'      => 'Digo que hay algunas señales que están revisando, pero intento tranquilizar sin entrar en la incertidumbre',
                    'tranquilizo' => 'Digo que por ahora todo parece controlado y que avisaremos si algo cambia',
                ],
            ],
            3 => [
                'narrativa' => "La conversación dentro de la habitación cambia.\n\n"
                    . "Ya no escuchas dudas aisladas.\n\n"
                    . "Escuchas conexiones.\n\n"
                    . "Dos señales diferentes han coincidido.\n\n"
                    . "Además, llegó información que estaba pendiente.\n\n"
                    . "Nadie está hablando de un desenlace.\n\n"
                    . "Pero tampoco escuchas ya la tranquilidad de hace unos minutos.\n\n"
                    . "Desde el pasillo llega otra vez la misma pregunta:\n\n"
                    . "—¿Entonces sí está mejor?\n\n"
                    . "Ahora sabes algo que antes no sabías: el equipo tiene motivos reales para no asegurarlo todavía.",
                'pregunta' => '¿Qué tan firme es tu posición sobre lo que debe comunicarse?',
                'opciones' => [
                    'muy_firme'      => 'Muy firme: no debemos transmitir que todo está bien mientras el propio equipo considera que la situación sigue siendo incierta',
                    'firme_reservas' => 'Firme, pero con reservas: debemos ser prudentes, aunque podemos enfatizar los elementos tranquilizadores',
                    'poco_firme'     => 'Poco firme: mientras no haya una confirmación de empeoramiento, considero mejor mantener un mensaje tranquilizador',
                ],
            ],
            4 => [
                'narrativa' => "El médico tratante sale de la habitación.\n\n"
                    . "El familiar se acerca inmediatamente.\n\n"
                    . "Antes de que pueda preguntar, el médico dice:\n\n"
                    . "—Dígale que estamos observando y que por ahora todo sigue controlado. Después hablamos con más calma.\n\n"
                    . "Empieza a regresar a la habitación.\n\n"
                    . "Tú recuerdas lo que acabas de escuchar dentro.\n\n"
                    . "Las señales.\n\n"
                    . "El resultado.\n\n"
                    . "La incertidumbre.\n\n"
                    . "El médico se detiene.\n\n"
                    . "Te mira.\n\n"
                    . "—¿Tienes algo que cambie esto?\n\n"
                    . "El familiar está a pocos metros.",
                'pregunta' => '¿Qué dices?',
                'opciones' => [
                    'sostengo' => 'Sí: digo que, con la incertidumbre que el propio equipo acaba de reconocer, no presentaría la situación como controlada y propondría un mensaje más prudente',
                    'matizo'   => 'Tal vez: acepto transmitir tranquilidad, pero agregaría que todavía están evaluando algunos cambios',
                    'cedo'     => 'No: sigo la indicación y transmito que por ahora todo está controlado',
                ],
            ],
            5 => [
                'narrativa' => "Más tarde, el pasillo está más tranquilo.\n\n"
                    . "El paciente permanece estable y bajo observación.\n\n"
                    . "El familiar finalmente recibe una explicación más completa.\n\n"
                    . "Tú recuerdas la primera pregunta: —¿Está mejor?\n\n"
                    . "Y todas las respuestas que pudieron caber dentro de esas dos palabras.\n\n"
                    . "Piensas menos en qué dijiste.\n\n"
                    . "Piensas en si dijiste realmente lo que creías que debía decirse.",
                'pregunta' => '¿Sentiste que tu criterio sobre cómo comunicar la situación influyó en la decisión final?',
                'opciones' => [
                    'bastante'  => 'Sí: expresé con claridad lo que consideraba responsable comunicar y sentí que fue tenido en cuenta',
                    'parcial'   => 'Parcialmente: expresé parte de mi preocupación, pero suavicé el mensaje que realmente consideraba necesario',
                    'casi_nada' => 'No: terminé comunicando algo diferente de lo que realmente creía que debía decirse',
                ],
            ],
        ],
    ],

    'E' => [
        'nombre' => 'Pulso de la Sala',
        'momentos' => [
            1 => [
                'narrativa' => "No estás mirando una pantalla.\n\n"
                    . "Estás mirando la habitación.\n\n"
                    . "Al principio hablan todos.\n\n"
                    . "Después empiezan a hablar menos.\n\n"
                    . "Una persona mira varias veces el mismo monitor antes de volver a guardar silencio.\n\n"
                    . "Otra actualiza una pantalla que todavía no muestra el resultado que espera.\n\n"
                    . "Alguien intenta decir algo.\n\n"
                    . "Otra voz se adelanta.\n\n"
                    . "—Parece que está respondiendo.\n\n"
                    . "La conversación cambia inmediatamente.\n\n"
                    . "La frase trae alivio.\n\n"
                    . "Pero tú viste algo más.\n\n"
                    . "Dos personas que parecían a punto de hablar ya no lo hicieron.\n\n"
                    . "Todavía no lo has dicho en voz alta.",
                'pregunta' => '¿Qué tan importante te parece intervenir sobre lo que estás observando en el equipo?',
                'opciones' => [
                    'mucho' => 'Mucho: me preocupa que algunas observaciones estén quedando fuera de la conversación',
                    'algo'  => 'Algo: lo noto, pero prefiero esperar para ver si esas personas hablan por sí mismas',
                    'poco'  => 'Poco: si realmente fuera importante, probablemente lo dirían',
                ],
            ],
            2 => [
                'narrativa' => "La conversación continúa.\n\n"
                    . "Ahora sabes que sí había información sin decir.\n\n"
                    . "Una persona menciona un cambio.\n\n"
                    . "Otra reconoce que está esperando un resultado.\n\n"
                    . "Por unos segundos aparecen más voces.\n\n"
                    . "Después alguien dice:\n\n"
                    . "—Puede ser una lectura irregular.\n\n"
                    . "Y ocurre otra vez.\n\n"
                    . "La conversación se encoge.\n\n"
                    . "Quien había hablado primero deja de insistir.\n\n"
                    . "Otra persona mira su pantalla y guarda silencio.\n\n"
                    . "Nadie les pidió que callaran.\n\n"
                    . "No hizo falta.",
                'pregunta' => '¿Qué haces con lo que estás observando?',
                'opciones' => [
                    'sostengo' => 'Lo señalo: digo que estamos cerrando demasiado rápido algunas observaciones antes de escucharlas completas',
                    'matizo'   => 'Lo menciono con cautela: sugiero que quizá valga la pena escuchar un poco más antes de concluir',
                    'cedo'     => 'No intervengo: prefiero que cada persona decida por sí misma si necesita insistir',
                ],
            ],
            3 => [
                'narrativa' => "Algo cambia en la sala.\n\n"
                    . "Una segunda señal coincide con la primera.\n\n"
                    . "Llega el resultado que faltaba.\n\n"
                    . "Las personas que antes hablaban por separado empiezan a encontrarse en la misma conclusión.\n\n"
                    . "Las voces son más claras ahora.\n\n"
                    . "También más firmes.\n\n"
                    . "Por primera vez, parece que la información está llegando al centro de la conversación.\n\n"
                    . "Pero notas otra cosa.\n\n"
                    . "Cada vez que alguien con más peso habla, las demás voces esperan.\n\n"
                    . "Y algunas ya no regresan.",
                'pregunta' => '¿Qué tan firme es tu lectura sobre la dinámica del equipo?',
                'opciones' => [
                    'muy_firme'      => 'Muy firme: tenemos información importante, pero la forma en que estamos conversando puede hacer que algunas piezas desaparezcan justo cuando más se necesitan',
                    'firme_reservas' => 'Firme, pero con reservas: hay señales de que algunas voces pierden espacio, aunque el equipo todavía está compartiendo información',
                    'poco_firme'     => 'Poco firme: mientras los datos importantes terminen apareciendo, no considero que la dinámica sea un problema relevante',
                ],
            ],
            4 => [
                'narrativa' => "Entra el médico tratante.\n\n"
                    . "La sala cambia antes de que diga una palabra.\n\n"
                    . "Escucha el resumen.\n\n"
                    . "Hace algunas preguntas.\n\n"
                    . "Después mira las pantallas.\n\n"
                    . "—Por ahora seguimos como estamos. Observamos un poco más.\n\n"
                    . "Nadie responde.\n\n"
                    . "Tú miras alrededor.\n\n"
                    . "Hace menos de un minuto varias personas estaban defendiendo una lectura distinta.\n\n"
                    . "Ahora nadie la sostiene.\n\n"
                    . "El médico recorre la sala con la mirada.\n\n"
                    . "Se detiene en ti.\n\n"
                    . "—¿Tienes algo que cambie esto?\n\n"
                    . "No tienes un dato nuevo. Tienes algo diferente: viste cómo llegó esa decisión.",
                'pregunta' => '¿Qué dices?',
                'opciones' => [
                    'sostengo' => 'Sí: digo que antes de cerrar la decisión necesitamos escuchar de nuevo a quienes acababan de expresar una lectura diferente',
                    'matizo'   => 'Tal vez: sugiero hacer una última ronda rápida, sin cuestionar directamente la decisión tomada',
                    'cedo'     => 'No: si quienes tenían la información no están objetando, considero que no me corresponde intervenir',
                ],
            ],
            5 => [
                'narrativa' => "Después, la habitación vuelve a llenarse de voces.\n\n"
                    . "La información termina poniéndose sobre la mesa.\n\n"
                    . "El equipo actúa.\n\n"
                    . "El paciente queda estable y bajo observación.\n\n"
                    . "Pero tú recuerdas otro instante.\n\n"
                    . "No el momento de mayor ruido.\n\n"
                    . "El de mayor silencio.\n\n"
                    . "Ese segundo en que varias personas parecían pensar algo y ninguna lo dijo.",
                'pregunta' => '¿Sentiste que tu observación sobre la forma en que el equipo estaba tomando la decisión influyó realmente?',
                'opciones' => [
                    'bastante'  => 'Sí: señalé con claridad lo que estaba ocurriendo y sentí que cambió la conversación',
                    'parcial'   => 'Parcialmente: intenté abrir espacio, pero suavicé lo que realmente estaba observando',
                    'casi_nada' => 'No: vi lo que estaba ocurriendo, pero mi lectura no llegó a influir en la decisión',
                ],
            ],
        ],
    ],
];
