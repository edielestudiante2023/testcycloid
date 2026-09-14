<?php
declare(strict_types=1);

/**
 * Contenido narrativo de "El Meridián" — un carguero ficticio que navega
 * hacia una tormenta que crece. Cada rol conoce solo una parte de la
 * situación y atraviesa varios "momentos": lee algo nuevo, responde en
 * privado, habla con su equipo, y solo entonces se destraba el siguiente.
 *
 * La cantidad de momentos por rol es libre — no hay nada en el motor que
 * asuma un número fijo. Para agregar, quitar o reordenar momentos, solo
 * se edita este archivo.
 *
 * @return array<string, array{nombre: string, momentos: array<int, array{narrativa: string, pregunta: string, opciones: array<string, string>}>}>
 */
return [
    'A' => [
        'nombre' => 'Vigía de Tormenta',
        'momentos' => [
            1 => [
                'narrativa' => "Son las 4:40 de la tarde.\n\n"
                    . "Llevas seis horas de guardia.\n\n"
                    . "El aire cambió antes de que miraras cualquier pantalla.\n\n"
                    . "El cielo, esta mañana de un azul aburrido, tiene ahora esa palidez sucia que los "
                    . "marinos viejos reconocen sin que nadie se las explique.\n\n"
                    . "Bajo tus pies, el motor sigue con su zumbido de siempre.\n\n"
                    . "Pero el vaivén del casco ya no es igual.\n\n"
                    . "En tu monitor parpadea un reporte.\n\n"
                    . "Llegó tarde. Más de una hora tarde.\n\n"
                    . "Los datos vienen incompletos, con huecos, como si el mismo cielo estuviera indeciso.\n\n"
                    . "Hay una tormenta ahí afuera.\n\n"
                    . "La están bautizando Selene.\n\n"
                    . "Nadie parece saber con certeza hacia dónde se dirige.\n\n"
                    . "Eres la única persona a bordo que ha visto este reporte.\n\n"
                    . "Todavía nadie más lo sabe.",
                'pregunta' => 'Con lo que sabes hasta ahora, ¿qué tan preocupante te parece la situación?',
                'opciones' => ['poco' => 'Poco', 'algo' => 'Algo', 'mucho' => 'Mucho'],
            ],
            2 => [
                'narrativa' => "Bajas al puente.\n\n"
                    . "El café se enfría en tu mano.\n\n"
                    . "Ni siquiera lo notas.\n\n"
                    . "Cada compañero trae su propio pedazo de la historia.\n\n"
                    . "Alguien menciona algo sobre la ruta.\n\n"
                    . "Alguien más, sobre el retraso con el que salieron.\n\n"
                    . "Nadie suelta todo lo que sabe de una sola vez.\n\n"
                    . "Lo van dejando caer en fragmentos.\n\n"
                    . "Como quien no quiere ser el primero en sonar alarmista.\n\n"
                    . "Escuchas.\n\n"
                    . "Intentas armar el rompecabezas con lo que dicen.\n\n"
                    . "Pero incluso después de hablar, sientes que faltan piezas.\n\n"
                    . "Y no sabes si son piezas que nadie tiene.\n\n"
                    . "O piezas que alguien se está guardando.",
                'pregunta' => 'Después de escucharlos, ¿tu opinión cambió?',
                'opciones' => ['si' => 'Sí, ahora lo veo distinto', 'no' => 'No, sigo pensando lo mismo'],
            ],
            3 => [
                'narrativa' => "El monitor vuelve a parpadear.\n\n"
                    . "Esta vez no hay ambigüedad.\n\n"
                    . "Selene no se alejó como se esperaba.\n\n"
                    . "Cambió de rumbo.\n\n"
                    . "Y está ganando fuerza más rápido de lo que cualquiera calculó.\n\n"
                    . "Afuera, algo cambió también.\n\n"
                    . "El vaivén ya no es el balanceo tranquilo de siempre.\n\n"
                    . "Ahora hay un tirón más brusco.\n\n"
                    . "Una pausa incómoda antes de que el casco vuelva a nivelarse.\n\n"
                    . "Alguien en cubierta grita algo.\n\n"
                    . "No alcanzas a escucharlo bien.",
                'pregunta' => '¿Esto cambia tu recomendación?',
                'opciones' => ['cambiar' => 'Sí, hay que cambiar de ruta', 'mantener' => 'No, mantener el curso'],
            ],
            4 => [
                'narrativa' => "Quedan minutos. No horas.\n\n"
                    . "Habla alguien del equipo.\n\n"
                    . "Más años a bordo que el resto.\n\n"
                    . "Más autoridad en la voz, aunque nadie se la haya dado formalmente.\n\n"
                    . "—Mantengamos el rumbo.\n\n"
                    . "Dice que han cruzado tormentas peores.\n\n"
                    . "Que desviarse cuesta un tiempo que, según él, no tienen.\n\n"
                    . "Nadie lo contradice en voz alta.\n\n"
                    . "Ves las caras de los demás.\n\n"
                    . "Hay dudas.\n\n"
                    . "Hay incomodidad.\n\n"
                    . "Pero también ese silencio pesado que se instala cuando alguien con más peso ya dijo lo "
                    . "que piensa.",
                'pregunta' => '¿Qué haces?',
                'opciones' => [
                    'insisto' => 'Insisto en mi punto, aunque incomode',
                    'cedo'    => 'Cedo — ya lo dije una vez',
                    'callo'   => 'Prefiero no repetirlo, ya quedó dicho',
                ],
            ],
            5 => [
                'narrativa' => "La decisión ya está tomada.\n\n"
                    . "En el puente, todos se movieron a lo que sigue.\n\n"
                    . "A preparar lo que haya que preparar.\n\n"
                    . "Ya no hay espacio para cambiar de opinión.\n\n"
                    . "Te quedas un momento con la pregunta que nadie hizo en voz alta.\n\n"
                    . "Si de verdad dijiste lo que pensabas.\n\n"
                    . "O si, como los demás, dejaste que se perdiera entre los comentarios de todos.",
                'pregunta' => '¿Sientes que tu opinión influyó en la decisión final?',
                'opciones' => ['bastante' => 'Sí, bastante', 'poco' => 'Un poco', 'casi_nada' => 'Casi nada'],
            ],
        ],
    ],

    'B' => [
        'nombre' => 'Piloto de Ruta',
        'momentos' => [
            1 => [
                'narrativa' => "Son las 4:40 de la tarde.\n\n"
                    . "Frente a ti hay dos líneas.\n\n"
                    . "Una cruza el mapa casi recta, como lo ha hecho tantas veces antes. Es la ruta conocida. "
                    . "La que estaba prevista desde antes de zarpar. La que permitiría recuperar parte de las "
                    . "horas que el Meridián perdió antes de salir.\n\n"
                    . "La otra se curva hacia el sur.\n\n"
                    . "Es más larga. Mucho más larga.\n\n"
                    . "La trazaste hace unos minutos casi por precaución, mientras llegaban noticias confusas "
                    . "de una tormenta llamada Selene.\n\n"
                    . "Ahora las miras a las dos.\n\n"
                    . "Una ahorra tiempo.\n\n"
                    . "La otra compra distancia.\n\n"
                    . "Y todavía no sabes cuál de esas dos cosas van a necesitar más esta noche.\n\n"
                    . "Hay algo más que no logras sacar de tu cabeza. Antes de zarpar alguien dijo, medio en "
                    . "serio, medio como advertencia:\n\n"
                    . "\"Este viaje tiene que llegar a tiempo.\"\n\n"
                    . "Sabes que la puntualidad queda registrada. Sabes que los retrasos se explican. Sabes "
                    . "que los resultados pesan.\n\n"
                    . "Y el Meridián ya salió tarde.",
                'pregunta' => 'Si tuvieras que recomendar una ruta ahora, ¿qué pesaría más en tu decisión?',
                'opciones' => [
                    'alejarnos' => 'Alejarnos de la zona incierta',
                    'recuperar' => 'Recuperar el retraso',
                    'saber_mas' => 'Necesito saber más',
                ],
            ],
            2 => [
                'narrativa' => "En el puente las voces empiezan a cruzarse.\n\n"
                    . "Un reporte incompleto.\n\n"
                    . "Un cambio en el mar.\n\n"
                    . "Comentarios de la tripulación.\n\n"
                    . "Algo en máquinas.\n\n"
                    . "Tú escuchas mientras tus ojos regresan una y otra vez al mapa.\n\n"
                    . "Hay algo que los demás todavía no parecen haber entendido: la ruta del sur no estará "
                    . "disponible de la misma manera para siempre.\n\n"
                    . "Ahora desviarse es sencillo.\n\n"
                    . "Más adelante significará retroceder, rodear más distancia y perder todavía más tiempo.\n\n"
                    . "Quieres explicarlo.\n\n"
                    . "Abres la boca.\n\n"
                    . "—Podríamos considerar la ruta del sur.\n\n"
                    . "Y apenas terminas de decirlo sabes que no dijiste lo que querías decir.\n\n"
                    . "\"Podríamos.\"\n\n"
                    . "\"Considerar.\"\n\n"
                    . "Miras nuevamente el mapa.\n\n"
                    . "Tu frase sonó como una posibilidad.\n\n"
                    . "Para ti empieza a parecer una advertencia.",
                'pregunta' => '¿Qué tan claramente expresaste lo que realmente pensabas?',
                'opciones' => [
                    'claro'      => 'Fui completamente claro',
                    'suavizado'  => 'Lo suavicé',
                    'insinuado'  => 'Apenas lo insinué',
                ],
            ],
            3 => [
                'narrativa' => "El nuevo reporte llega sin ceremonia.\n\n"
                    . "Selene cambió de rumbo.\n\n"
                    . "Y está creciendo.\n\n"
                    . "Durante unos segundos nadie dice nada.\n\n"
                    . "Tú vuelves al mapa.\n\n"
                    . "Mueves el dedo por la ruta habitual. Calculas dónde estará el Meridián dentro de unas "
                    . "horas. Después miras la nueva posición estimada de la tormenta.\n\n"
                    . "Las dos trayectorias se acercan demasiado.\n\n"
                    . "Entonces miras hacia el sur.\n\n"
                    . "La alternativa sigue ahí.\n\n"
                    . "Todavía.\n\n"
                    . "Afuera, una ola golpea el casco con suficiente fuerza para hacer vibrar la taza que "
                    . "alguien dejó junto a los instrumentos.\n\n"
                    . "La taza se mueve unos centímetros.\n\n"
                    . "Nadie la recoge.\n\n"
                    . "Piensas en las horas de retraso.\n\n"
                    . "En la evaluación.\n\n"
                    . "En las explicaciones que habrá que dar.\n\n"
                    . "Y después piensas en algo mucho más simple: todavía podemos elegir.",
                'pregunta' => '¿Cuál es ahora tu recomendación?',
                'opciones' => [
                    'cambiar_ahora' => 'Cambiar de ruta ahora',
                    'mantener'      => 'Mantener el rumbo',
                    'esperar'       => 'Esperar un poco más',
                ],
            ],
            4 => [
                'narrativa' => "Entonces habla alguien con más años a bordo.\n\n"
                    . "No grita.\n\n"
                    . "No necesita hacerlo.\n\n"
                    . "—Mantengamos el rumbo.\n\n"
                    . "Dice que desviarse ahora significa llegar todavía más tarde. Que han navegado con mal "
                    . "tiempo antes. Que no tiene sentido convertir una posibilidad en un problema seguro.\n\n"
                    . "Algunas cabezas asienten.\n\n"
                    . "Tú miras el mapa.\n\n"
                    . "La ruta del sur sigue ahí.\n\n"
                    . "Pero la ventana se está cerrando.\n\n"
                    . "Quieres decirlo.\n\n"
                    . "No \"podríamos desviarnos\".\n\n"
                    . "No \"quizá sería prudente\".\n\n"
                    . "Quieres decir: \"Creo que estamos cometiendo un error.\"\n\n"
                    . "Pero esas palabras pesan mucho más cuando tienes que pronunciarlas delante de alguien "
                    . "que parece no tener ninguna duda.\n\n"
                    . "Levantas la mirada.\n\n"
                    . "Nadie habla.\n\n"
                    . "Y durante unos segundos descubres algo extraño: es mucho más fácil enfrentarse a una "
                    . "tormenta dibujada sobre un mapa que a una persona sentada al otro lado de la mesa.",
                'pregunta' => '¿Qué haces?',
                'opciones' => [
                    'digo_claro'    => 'Digo claramente que creo que debemos cambiar',
                    'expongo_datos' => 'Expongo nuevamente los datos sin confrontar',
                    'silencio'      => 'Guardo silencio',
                ],
            ],
            5 => [
                'narrativa' => "La decisión está tomada.\n\n"
                    . "Doblas el mapa.\n\n"
                    . "La ruta habitual y la desviación quedan una encima de la otra hasta desaparecer entre "
                    . "los pliegues.\n\n"
                    . "El puente vuelve a llenarse de movimiento.\n\n"
                    . "Pero tú sigues escuchando una frase.\n\n"
                    . "No la que dijiste.\n\n"
                    . "La que no dijiste.\n\n"
                    . "\"Creo que estamos cometiendo un error.\"\n\n"
                    . "Tal vez habría cambiado la decisión.\n\n"
                    . "Tal vez no.\n\n"
                    . "Nunca lo sabrás.\n\n"
                    . "Y esa es precisamente la parte que más pesa.",
                'pregunta' => '¿El equipo supo realmente lo que pensabas?',
                'opciones' => ['si_claro' => 'Sí, lo dije con claridad', 'parcial' => 'Solo parcialmente', 'no' => 'No'],
            ],
        ],
    ],

    'C' => [
        'nombre' => 'Oficial de Guardia',
        'momentos' => [
            1 => [
                'narrativa' => "Son las 4:40 de la tarde.\n\n"
                    . "Hace unos minutos caminabas por cubierta cuando escuchaste dos voces detrás de ti.\n\n"
                    . "—Esto se está poniendo feo.\n\n"
                    . "—Arriba deberían saberlo.\n\n"
                    . "No dijiste nada. Seguiste caminando.\n\n"
                    . "Más adelante alguien te preguntó si habían recibido noticias de la tormenta.\n\n"
                    . "Otro quiso saber si cambiarían la ruta.\n\n"
                    . "Otro miró hacia el cielo y simplemente dijo:\n\n"
                    . "—No me gusta.\n\n"
                    . "Pero ocurre algo curioso.\n\n"
                    . "Cuando están solos contigo, hablan.\n\n"
                    . "Cuando aparece alguien con autoridad, las frases se transforman.\n\n"
                    . "\"Estamos pendientes.\"\n\n"
                    . "\"Todo normal.\"\n\n"
                    . "\"Seguro arriba saben.\"\n\n"
                    . "Has visto suficientes equipos para reconocerlo.\n\n"
                    . "El miedo no desapareció.\n\n"
                    . "Solo aprendió a hablar bajito.",
                'pregunta' => '¿Qué importancia le das a lo que estás escuchando?',
                'opciones' => [
                    'importante' => 'Es una señal importante',
                    'cautela'    => 'Lo tomo con cautela',
                    'comentarios' => 'Son solo comentarios',
                ],
            ],
            2 => [
                'narrativa' => "En el puente ocurre exactamente lo mismo.\n\n"
                    . "Al principio todos hablan.\n\n"
                    . "Después alguien con una voz más segura interviene y las palabras empiezan a cambiar.\n\n"
                    . "\"Deberíamos\" se convierte en \"podríamos\".\n\n"
                    . "\"Me preocupa\" se convierte en \"sería bueno revisar\".\n\n"
                    . "Y \"no estoy de acuerdo\" nunca llega a pronunciarse.\n\n"
                    . "Tú observas.\n\n"
                    . "No conoces el estado exacto de Selene.\n\n"
                    . "No sabes cuál ruta conviene.\n\n"
                    . "No sabes qué ocurre en máquinas.\n\n"
                    . "Pero sabes algo que los demás no pueden ver desde aquí: abajo hay más preocupación de "
                    . "la que está llegando a esta mesa.\n\n"
                    . "Y empiezas a sospechar que la información también puede perderse sin que nadie la "
                    . "oculte.\n\n"
                    . "Basta con decirla suficientemente bajito.",
                'pregunta' => '¿Qué haces con lo que sabes?',
                'opciones' => [
                    'digo_claro'          => 'Lo digo claramente',
                    'menciono_cautela'    => 'Lo menciono con cautela',
                    'no_hablo_por_otros'  => 'Prefiero no hablar por los demás',
                ],
            ],
            3 => [
                'narrativa' => "Llega la actualización.\n\n"
                    . "Selene cambió de rumbo.\n\n"
                    . "Está creciendo.\n\n"
                    . "El puente queda extraño durante unos segundos.\n\n"
                    . "Desde cubierta llega un golpe seco. Después otro.\n\n"
                    . "Alguien asegura algo afuera.\n\n"
                    . "Entonces recuerdas todas las conversaciones de esta tarde.\n\n"
                    . "\"Esto se está poniendo feo.\"\n\n"
                    . "\"Arriba deberían saberlo.\"\n\n"
                    . "\"No me gusta.\"\n\n"
                    . "De repente ya no parecen comentarios.\n\n"
                    . "Parecen piezas.\n\n"
                    . "Y quizá llevan horas intentando formar una imagen que nadie se ha atrevido a mostrar "
                    . "completa.\n\n"
                    . "Miras alrededor.\n\n"
                    . "Hay personas preocupadas.\n\n"
                    . "Lo sabes.\n\n"
                    . "Ellas saben que tú lo sabes.\n\n"
                    . "Pero nadie parece querer ser el primero en decir: \"Yo no estoy de acuerdo con seguir.\"",
                'pregunta' => '¿Qué haces?',
                'opciones' => [
                    'digo_observacion' => 'Digo claramente lo que estoy observando',
                    'espero_otro'      => 'Espero que alguien más hable primero',
                    'escucho'          => 'Me limito a escuchar',
                ],
            ],
            4 => [
                'narrativa' => "Una de las personas con más experiencia toma la palabra.\n\n"
                    . "—Mantengamos el rumbo.\n\n"
                    . "Habla tranquilo. Seguro. Casi reconfortante.\n\n"
                    . "Y entonces ocurre delante de tus ojos.\n\n"
                    . "Una persona que hace treinta segundos parecía convencida baja la mirada.\n\n"
                    . "Otra empieza a hablar:\n\n"
                    . "—Yo pensaba que quizá deberíamos…\n\n"
                    . "Se detiene.\n\n"
                    . "—…aunque sí, también puede ser.\n\n"
                    . "Silencio.\n\n"
                    . "Alguien acomoda un papel que no necesitaba acomodar.\n\n"
                    . "Otro mira hacia la ventana.\n\n"
                    . "Nadie ha ordenado callar.\n\n"
                    . "Nadie ha amenazado a nadie.\n\n"
                    . "Nadie ha dicho \"su opinión no importa\".\n\n"
                    . "Y sin embargo, una por una, las opiniones empiezan a desaparecer.\n\n"
                    . "Tú sabes que siguen ahí.\n\n"
                    . "Puedes verlas en las caras.",
                'pregunta' => '¿Qué haces?',
                'opciones' => [
                    'digo_desacuerdos' => 'Digo que hay desacuerdos que no estamos expresando',
                    'pregunto_quien'   => 'Pregunto directamente quién no está de acuerdo',
                    'dejo_continuar'   => 'Dejo que continúe la decisión',
                ],
            ],
            5 => [
                'narrativa' => "La decisión está tomada.\n\n"
                    . "Sales del puente.\n\n"
                    . "En el pasillo escuchas nuevamente las voces.\n\n"
                    . "Ahora hablan.\n\n"
                    . "Ahora sí.\n\n"
                    . "—Yo habría cambiado.\n\n"
                    . "—A mí tampoco me convencía.\n\n"
                    . "—Pensé que alguien iba a insistir.\n\n"
                    . "Te detienes un instante.\n\n"
                    . "Es casi absurdo.\n\n"
                    . "Hace cinco minutos estaban todos en la misma habitación.\n\n"
                    . "Ahora, cuando ya no pueden cambiar nada, aparecen las palabras perfectas.\n\n"
                    . "Quizá el equipo nunca tuvo un problema para saber qué pensaba.\n\n"
                    . "Quizá el problema era decirlo cuando todavía importaba.",
                'pregunta' => '¿Qué ocurrió realmente en el puente?',
                'opciones' => [
                    'claro'      => 'Las opiniones se dijeron claramente',
                    'suave'      => 'Se dijeron demasiado suavemente',
                    'no_dichas'  => 'Algunas nunca llegaron a decirse',
                ],
            ],
        ],
    ],

    'D' => [
        'nombre' => 'Ingeniero de Máquinas',
        'momentos' => [
            1 => [
                'narrativa' => "Son las 4:40 de la tarde.\n\n"
                    . "Abajo no se ve el cielo.\n\n"
                    . "Se escucha el barco.\n\n"
                    . "Después de suficientes horas junto a una máquina aprendes que los sonidos también "
                    . "tienen memoria.\n\n"
                    . "Y hoy el Meridián suena diferente.\n\n"
                    . "No mal.\n\n"
                    . "Diferente.\n\n"
                    . "Desde el mediodía has sentido una vibración breve cuando el sistema pasa rápidamente "
                    . "de una exigencia estable a una mayor.\n\n"
                    . "Hiciste una prueba.\n\n"
                    . "Todo respondió.\n\n"
                    . "El barco puede navegar.\n\n"
                    . "El barco puede maniobrar.\n\n"
                    . "No hay una emergencia en máquinas.\n\n"
                    . "Pero anotaste una recomendación para ti mismo: si necesitan hacer un cambio importante, "
                    . "mejor hacerlo con margen.\n\n"
                    . "Sin prisas.\n\n"
                    . "Sin obligar al barco a responder de golpe cuando el mar ya esté decidiendo por "
                    . "ustedes.\n\n"
                    . "Todavía nadie arriba sabe esto.",
                'pregunta' => '¿Qué importancia tiene esta condición?',
                'opciones' => [
                    'importante_decidir' => 'Es importante para decidir',
                    'manejable'          => 'Es manejable por ahora',
                    'no_afecta'          => 'No debería afectar la decisión',
                ],
            ],
            2 => [
                'narrativa' => "Subes al puente.\n\n"
                    . "Allí descubres cosas que abajo no sabías.\n\n"
                    . "Selene. El retraso. Una ruta alternativa.\n\n"
                    . "Escuchas.\n\n"
                    . "Después explicas lo tuyo.\n\n"
                    . "—El motor está operativo.\n\n"
                    . "Ves cómo algunas caras se relajan.\n\n"
                    . "Añades:\n\n"
                    . "—Aunque sería mejor evitar exigirlo bruscamente si las condiciones empeoran.\n\n"
                    . "Alguien responde casi inmediatamente:\n\n"
                    . "—Perfecto. Entonces por máquinas no tenemos problema.\n\n"
                    . "Hay un segundo.\n\n"
                    . "Un segundo pequeño.\n\n"
                    . "Ridículo.\n\n"
                    . "Suficiente para corregirlo.\n\n"
                    . "Pero la conversación ya está avanzando.",
                'pregunta' => '¿Qué haces?',
                'opciones' => [
                    'corrijo_ya'     => 'Lo corrijo inmediatamente',
                    'aclaro_despues' => 'Lo aclaro después',
                    'dejo_pasar'     => 'Dejo pasar la interpretación',
                ],
            ],
            3 => [
                'narrativa' => "Selene cambió de rumbo.\n\n"
                    . "El dato llega desde el otro lado del puente.\n\n"
                    . "Está creciendo.\n\n"
                    . "Una ola golpea el casco.\n\n"
                    . "Tú la sientes de una manera distinta a los demás.\n\n"
                    . "No piensas primero en la tormenta.\n\n"
                    . "Piensas en el motor.\n\n"
                    . "En la vibración.\n\n"
                    . "En aquella prueba.\n\n"
                    . "Ahora entiendes algo que antes no podías saber: el Meridián puede cambiar de ruta.\n\n"
                    . "Pero \"ahora\" y \"más tarde\" no son la misma decisión.\n\n"
                    . "Ahora pueden hacerlo con margen.\n\n"
                    . "Más tarde quizá tengan que hacerlo con prisa.\n\n"
                    . "Y las máquinas odian las decisiones que los humanos dejan para el último minuto.",
                'pregunta' => '¿Qué recomendarías desde tu posición?',
                'opciones' => [
                    'cambiar_ahora' => 'Si van a cambiar, que sea ahora',
                    'esperar'       => 'Todavía podemos esperar',
                    'menos_cambios' => 'Mantener el rumbo exige menos cambios',
                ],
            ],
            4 => [
                'narrativa' => "Entonces alguien dice:\n\n"
                    . "—Máquinas confirmó que estamos bien.\n\n"
                    . "La frase queda flotando.\n\n"
                    . "Tú la escuchas.\n\n"
                    . "Sabes exactamente de dónde salió.\n\n"
                    . "De ti.\n\n"
                    . "De tus propias palabras.\n\n"
                    . "Pero no fue eso lo que dijiste.\n\n"
                    . "Dijiste \"operativo\".\n\n"
                    . "Ellos escucharon \"seguro\".\n\n"
                    . "Dijiste \"podemos\".\n\n"
                    . "Ellos entendieron \"sin problema\".\n\n"
                    . "Y ahora esas palabras están siendo utilizadas para sostener una decisión.\n\n"
                    . "Sientes algo incómodo en el pecho.\n\n"
                    . "Para corregirlo tendrás que interrumpir.\n\n"
                    . "Tendrás que decir que no.\n\n"
                    . "Tendrás que admitir que quizá tú mismo explicaste mal algo importante.\n\n"
                    . "Miras a quien está hablando.\n\n"
                    . "Después miras a los demás.\n\n"
                    . "El silencio te ofrece una salida muy fácil: no hacer nada.",
                'pregunta' => '¿Qué haces?',
                'opciones' => [
                    'corrijo_ahora'   => 'Corrijo la interpretación ahora',
                    'espero_aclarar'  => 'Espero un momento para aclararla',
                    'no_digo_nada'    => 'No digo nada',
                ],
            ],
            5 => [
                'narrativa' => "Regresas a máquinas.\n\n"
                    . "Aquí abajo nadie discute.\n\n"
                    . "Las máquinas no suavizan sus mensajes para no incomodar.\n\n"
                    . "Una vibración es una vibración.\n\n"
                    . "Una alarma es una alarma.\n\n"
                    . "Un límite es un límite.\n\n"
                    . "Los humanos somos distintos.\n\n"
                    . "Podemos convertir \"me preocupa\" en \"sería bueno revisar\".\n\n"
                    . "Podemos convertir \"no recomiendo esperar\" en \"podríamos hacerlo ahora\".\n\n"
                    . "Podemos convertir \"está operativo\" en \"todo está bien\".\n\n"
                    . "Apoyas una mano sobre la estructura.\n\n"
                    . "La vibración sigue allí.\n\n"
                    . "Exactamente igual que antes.\n\n"
                    . "Lo único que cambió fue la forma en que ustedes hablaron de ella.",
                'pregunta' => '¿Tu equipo entendió realmente lo que quisiste comunicar?',
                'opciones' => ['si' => 'Sí', 'parcial' => 'Solo parcialmente', 'no' => 'No'],
            ],
        ],
    ],

    'E' => [
        'nombre' => 'Contramaestre',
        'momentos' => [
            1 => [
                'narrativa' => "Son las 4:40 de la tarde.\n\n"
                    . "No tienes delante ningún mapa.\n\n"
                    . "No conoces los cálculos.\n\n"
                    . "No has visto el último reporte de Selene.\n\n"
                    . "Pero llevas toda la tarde viendo caras.\n\n"
                    . "Y las caras también informan.\n\n"
                    . "—¿Arriba saben cómo está el mar?\n\n"
                    . "—¿Van a cambiar la ruta?\n\n"
                    . "—¿Tú sabes algo?\n\n"
                    . "Cuando preguntas qué les preocupa, responden.\n\n"
                    . "Cuando dices \"deberían comentarlo arriba\", ocurre algo distinto.\n\n"
                    . "—Seguro ellos ya saben.\n\n"
                    . "—No quiero exagerar.\n\n"
                    . "—Ellos sabrán qué hacer.\n\n"
                    . "Has escuchado esas frases antes.\n\n"
                    . "Son frases cómodas.\n\n"
                    . "Permiten tener miedo sin tener que hacerse responsable de decir que se tiene miedo.",
                'pregunta' => '¿Qué te preocupa más?',
                'opciones' => [
                    'situacion_afuera' => 'La situación afuera',
                    'lo_callado'       => 'Lo que la gente está callando',
                    'no_preocupado'    => 'Todavía no estoy preocupado',
                ],
            ],
            2 => [
                'narrativa' => "En el puente observas.\n\n"
                    . "Todos tienen información.\n\n"
                    . "Nadie tiene la historia completa.\n\n"
                    . "Unos hablan del clima.\n\n"
                    . "Otros de la ruta.\n\n"
                    . "Otros del barco.\n\n"
                    . "Pero hay algo más ocurriendo.\n\n"
                    . "Lo notas porque llevas años viendo grupos trabajar juntos.\n\n"
                    . "Las personas no hablan igual con todos.\n\n"
                    . "Cuando sienten que están entre iguales, las frases salen enteras.\n\n"
                    . "Cuando habla alguien con más peso, empiezan a perder palabras.\n\n"
                    . "\"Yo recomiendo…\" se convierte en: \"Tal vez podríamos…\"\n\n"
                    . "Y \"creo que estamos equivocados\" termina convertido en un silencio.\n\n"
                    . "Quizá nadie más lo esté mirando.\n\n"
                    . "Tú sí.",
                'pregunta' => '¿Qué necesita esta conversación?',
                'opciones' => [
                    'cada_uno_recomienda' => 'Que cada uno diga claramente qué recomienda',
                    'mas_info'            => 'Más información',
                    'alguien_tome_mando'  => 'Que alguien tome el mando',
                ],
            ],
            3 => [
                'narrativa' => "Llega la noticia.\n\n"
                    . "Selene cambió.\n\n"
                    . "Está creciendo.\n\n"
                    . "El barco recibe un golpe de mar.\n\n"
                    . "Después otro.\n\n"
                    . "Las conversaciones se aceleran.\n\n"
                    . "Ahora todos parecen tener algo importante que aportar.\n\n"
                    . "Y, sin embargo, notas una diferencia.\n\n"
                    . "Escuchas muchos datos.\n\n"
                    . "Muchísimas explicaciones.\n\n"
                    . "Pero casi nadie termina sus frases con: \"Por eso recomiendo…\"\n\n"
                    . "Miras las caras.\n\n"
                    . "Todos están esperando algo.\n\n"
                    . "Quizá más información.\n\n"
                    . "Quizá permiso.\n\n"
                    . "Quizá que otra persona tenga el valor de hablar primero.",
                'pregunta' => '¿Qué debería ocurrir antes de decidir?',
                'opciones' => [
                    'cada_uno_recomienda'  => 'Cada persona debería decir qué recomienda',
                    'seguir_analizando'    => 'Necesitamos seguir analizando',
                    'decide_mas_experiencia' => 'Debe decidir la persona con más experiencia',
                ],
            ],
            4 => [
                'narrativa' => "Entonces alguien habla.\n\n"
                    . "Una persona con más años a bordo.\n\n"
                    . "Con más peso en la voz.\n\n"
                    . "—Mantengamos el rumbo.\n\n"
                    . "Nada extraordinario ocurre.\n\n"
                    . "Y eso es precisamente lo extraordinario.\n\n"
                    . "Nadie ordena silencio.\n\n"
                    . "Nadie golpea la mesa.\n\n"
                    . "Nadie dice que está prohibido disentir.\n\n"
                    . "Simplemente… el aire cambia.\n\n"
                    . "Una persona baja la mirada.\n\n"
                    . "Otra cruza los brazos.\n\n"
                    . "Alguien que parecía dispuesto a hablar bebe agua.\n\n"
                    . "Uno comienza: —Yo no estoy tan seguro…\n\n"
                    . "La persona con más experiencia lo mira.\n\n"
                    . "No dice nada.\n\n"
                    . "—…pero entiendo el punto.\n\n"
                    . "Y ahí muere la frase.\n\n"
                    . "Tú lo viste.\n\n"
                    . "Una opinión acaba de desaparecer delante de todos.\n\n"
                    . "Sin que nadie la prohibiera.\n\n"
                    . "Entonces entiendes algo: a veces el silencio no significa acuerdo. A veces significa "
                    . "que alguien está calculando cuánto le costará decir la verdad.",
                'pregunta' => '¿Qué haces?',
                'opciones' => [
                    'detengo_pido_posiciones' => 'Detengo el cierre y pido escuchar las posiciones reales',
                    'pregunto_desacuerdo'     => 'Pregunto quién está en desacuerdo',
                    'silencio'                => 'Permanezco en silencio',
                ],
            ],
            5 => [
                'narrativa' => "La decisión está tomada.\n\n"
                    . "El puente vuelve a moverse.\n\n"
                    . "Las personas recuperan sus tareas.\n\n"
                    . "Algunos incluso parecen aliviados.\n\n"
                    . "Decidir produce esa clase de tranquilidad.\n\n"
                    . "Aunque la decisión sea incierta, al menos termina la incomodidad de tener que "
                    . "discutirla.\n\n"
                    . "Tú permaneces unos segundos observando.\n\n"
                    . "No sabes si debían mantener el rumbo.\n\n"
                    . "No sabes si debían cambiarlo.\n\n"
                    . "Quizá jamás lo sepas.\n\n"
                    . "Pero hay una pregunta que sí puedes responder.\n\n"
                    . "¿Todos dijeron realmente lo que pensaban antes de decidir?\n\n"
                    . "Recuerdas las miradas.\n\n"
                    . "Las frases incompletas.\n\n"
                    . "Las palabras que aparecieron después, cuando ya era demasiado tarde.\n\n"
                    . "Y comprendes que un equipo puede estar reunido alrededor de la misma mesa… y aun así "
                    . "dejar que lo más importante quede afuera.",
                'pregunta' => '¿Qué describiría mejor lo que ocurrió?',
                'opciones' => [
                    'hablo_claridad'     => 'El equipo habló con claridad',
                    'opiniones_pequenas' => 'Algunas opiniones se hicieron pequeñas',
                    'silencio_decidio'   => 'El silencio terminó decidiendo',
                ],
            ],
        ],
    ],
];
