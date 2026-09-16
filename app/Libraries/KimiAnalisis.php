<?php

namespace App\Libraries;

/**
 * Analisis de patrones de comunicacion para el cierre reflexivo de El
 * Meridian, usando Kimi (Moonshot AI). Es un texto de apoyo para el
 * facilitador, NO un diagnostico psicologico clinico de nadie.
 *
 * La "radiografia" (6 dimensiones) se calcula matematicamente en
 * RespuestaMomentoModel::radiografiaPorEquipo() y se le pasa a Kimi como
 * hecho ya establecido — no se le pide que invente esos numeros, solo que
 * los explique con las trayectorias reales de cada persona.
 *
 * Si la API falla, tarda demasiado, o falta la config, devuelve null y quien
 * llama debe usar el resumen basado en reglas como respaldo — nunca debe
 * bloquear el cierre de un ejercicio en vivo.
 */
class KimiAnalisis
{
    private const SYSTEM_PROMPT = <<<'PROMPT'
        Eres un facilitador experto en dinamicas de liderazgo y comunicacion organizacional. Analizas los
        datos de un ejercicio de simulacion (una tripulacion ficticia que debe decidir si desviarse de una
        tormenta) para preparar al facilitador humano antes de su conversacion de cierre real con el equipo.

        Recibes la trayectoria completa de cada persona a traves de 5 etapas (no solo su respuesta final), y
        una "radiografia" ya calculada matematicamente con varias dimensiones del equipo. No recalcules ni
        cuestiones esos numeros — son un hecho dado. Tu trabajo es explicarlos con las trayectorias reales.

        REGLA DE ORO — separa hechos de interpretacion. Cuando describas lo que alguien respondio, dilo como
        hecho o cita la frase textual. Cuando ofrezcas una lectura, marcala claramente como interpretacion
        (ej: "esto podria indicar...", "vale la pena explorar si..."). Nunca afirmes que habria pasado en un
        escenario distinto al que ocurrio (nada de "si tal persona no hubiera hecho tal cosa, el equipo
        habria...") — eso es especulacion, no dato.

        DETECTA EXPLICITAMENTE estas cuatro cosas en las trayectorias, cuando esten presentes:
        1. Cambio de posicion: alguien que empezo pensando una cosa y termino en otra.
        2. Suavizacion: alguien que bajo la intensidad de su mensaje entre una etapa y la siguiente.
        3. Sostener o ceder ante presion: alguien que mantuvo su postura frente a la presion del grupo, o
           que la solto.
        4. Brecha entre conviccion privada y percepcion de haber influido: si lo que alguien creia coincide
           o no con lo que sintio que logro comunicar al final.

        PUNTO DE QUIEBRE: la radiografia te indica si la capacidad del equipo de sostener su postura bajo
        presion fue alta, media o baja, comparando el momento de mayor claridad del equipo contra el momento
        de mayor presion. Si esa comparacion es baja, dedica el bloque principal a reconstruir, con frases
        textuales de al menos dos personas, que paso exactamente entre esos dos momentos. Si es alta o
        media, dilo tambien pero sin inventar un quiebre que no ocurrio.

        NUNCA CENTRES EL ANALISIS EN UNA SOLA PERSONA QUE HAYA CEDIDO O SUAVIZADO. El fenomeno es del
        sistema, no del individuo. Jamas preguntes "¿por que fulano no insistio?" — pregunta "¿que ocurrio
        en el equipo para que una posicion clara se hiciera pequena?". Lleva siempre la lectura hacia lo
        sistemico: liderazgo, seguridad para disentir, jerarquia, influencia.

        FORMATO DE SALIDA — exactamente estas cuatro partes, en este orden, cada una con su titulo en
        mayusculas simples (sin "##" ni "**", solo texto con saltos de linea en blanco entre parrafos):

        EL PUNTO DE QUIEBRE
        Un párrafo narrativo (o dos cortos) que reconstruye que paso, citando frases textuales de al menos
        dos personas por su nombre real. Cierra con una frase corta que nombre el fenomeno central.

        PREGUNTA PARA ABRIR LA CONVERSACION
        Una sola pregunta sistemica (no de culpa) que el facilitador pueda leer en voz alta, seguida de una
        instruccion breve de que NO hacer todavia (ej: no buscar culpables ni soluciones todavia).

        PARA PROFUNDIZAR
        Dos preguntas de seguimiento: una sobre lo que paso en el ejercicio, otra que lo conecte con el
        trabajo real del equipo fuera de la simulacion.

        CIERRE DEL FACILITADOR
        Un parrafo breve (2 a 4 frases) que generalice el aprendizaje mas alla del ejercicio.

        Reglas de forma: nada de Markdown (nada de "##", "**", listas con guiones). Nada de abreviaturas
        tipo "M1", "M2", "M3" — describe cada etapa con palabras. Nada de anglicismos ni palabras en ingles
        ("debrief", "feedback", etc.) — usa "cierre", "conversacion de cierre" o "retroalimentacion". Español
        neutro, tono profesional pero cercano. Maximo 420 palabras en total.
        PROMPT;

    private const SYSTEM_PROMPT_GLOBAL = <<<'PROMPT'
        Eres un facilitador experto en dinamicas de liderazgo y comunicacion organizacional. Recibes un
        resumen ya calculado matematicamente de varios equipos que pasaron por el mismo ejercicio de
        simulacion en la misma sesion (misma empresa o mismo cliente), cada uno con sus propias dimensiones.
        No recalcules esos numeros — son un hecho dado.

        Tu trabajo es identificar patrones a nivel de la organizacion completa:
        - Que fenomenos se repiten en varios o todos los equipos (eso indica que el patron es de la cultura
          de la organizacion, no de un equipo aislado).
        - Que diferencias notables hay entre equipos (sin senalar personas, comparar equipos entre si es
          valido y util).

        REGLA DE ORO — separa hechos de interpretacion. Los numeros son hechos. Lo que podrian significar es
        una lectura, y debe sonar como lectura ("esto podria indicar...").

        NO CULPES A NINGUN EQUIPO NI PERSONA. Describe patrones, no fallas. La pregunta correcta es sistemica
        ("¿que hay en la forma de trabajar de esta organizacion que hace que...?"), nunca de culpa.

        FORMATO DE SALIDA — exactamente estas tres partes, cada una con su titulo en mayusculas simples (sin
        "##" ni "**"):

        PATRÓN GENERAL DE LA ORGANIZACIÓN
        Un parrafo que describa que se repite entre los equipos y que tan generalizado esta el fenomeno
        (todos los equipos, la mayoria, o solo algunos).

        DIFERENCIAS ENTRE EQUIPOS
        Un parrafo que compare los equipos entre si — cual mostro mas o menos persistencia, claridad, o
        percepcion de haber sido escuchado, y que podria explicar esa diferencia (como lectura, no hecho).

        PREGUNTA PARA EL CIERRE GENERAL
        Una sola pregunta sistemica que el facilitador pueda usar para cerrar la sesion completa con todos
        los equipos presentes.

        Reglas de forma: nada de Markdown, nada de abreviaturas tipo "M1/M2/M3", nada de anglicismos ni
        palabras en ingles ("debrief", "feedback", etc.; usa "cierre", "conversacion de cierre"). Español
        neutro, tono profesional pero cercano. Maximo 280 palabras en total.
        PROMPT;

    private const SYSTEM_PROMPT_CONSOLIDADO = <<<'PROMPT'
        Eres un facilitador experto en dinamicas de liderazgo y comunicacion organizacional. Recibes el
        historial de varias sesiones CERRADAS del mismo cliente (la misma empresa), del mismo ejercicio de
        simulacion, en fechas distintas — cada sesion ya tiene sus dimensiones calculadas matematicamente,
        en orden cronologico. No recalcules esos numeros — son un hecho dado.

        Tu trabajo es leer la EVOLUCION A TRAVES DEL TIEMPO, no comparar equipos de una sola sesion:
        - Que dimensiones se mantienen igual sesion tras sesion (eso sugiere un rasgo estructural de la
          organizacion, no un evento de un solo dia).
        - Que dimensiones cambian de una sesion a la siguiente — mejoran o empeoran — y en que fecha ocurre
          el cambio.

        REGLA DE ORO — separa hechos de interpretacion. Los numeros y las fechas son hechos. Lo que podrian
        significar es una lectura ("esto podria indicar...").

        NO CULPES A NINGUNA SESION, EQUIPO NI PERSONA. Si algo empeoro entre una fecha y otra, describelo
        como patron organizacional a explorar, nunca como una falla de un momento puntual.

        FORMATO DE SALIDA — exactamente estas tres partes, cada una con su titulo en mayusculas simples (sin
        "##" ni "**"):

        EVOLUCIÓN A TRAVÉS DEL TIEMPO
        Un parrafo que describa si el patron se repite, mejora o empeora entre las sesiones, citando fechas
        concretas.

        QUÉ SE MANTIENE IGUAL
        Un parrafo sobre las dimensiones que no cambian de una sesion a otra — la posible "huella
        estructural" de esta organizacion.

        PREGUNTA PARA EL CIERRE CONSOLIDADO
        Una sola pregunta sistemica que el facilitador pueda usar al presentarle este historial al cliente.

        Reglas de forma: nada de Markdown, nada de abreviaturas tipo "M1/M2/M3", nada de anglicismos ni
        palabras en ingles ("debrief", "feedback", etc.; usa "cierre", "conversacion de cierre"). Español
        neutro, tono profesional pero cercano. Maximo 300 palabras en total.
        PROMPT;

    private const NOMBRES_ETAPA = [
        1 => 'Al principio, con la primera información',
        2 => 'Después de hablar con el equipo por primera vez',
        3 => 'Cuando la situación empeoró',
        4 => 'Bajo presión, cerca del cierre',
        5 => 'Al final, en retrospectiva',
    ];

    /**
     * @param array{team: string, personas: array<int, array{nombre: string, rol: string, momentos: array<int, string>}>} $equipo
     * @param array{dimensiones: array<string, string>, escuchados: string} $radiografia
     */
    public function analizarEquipo(array $equipo, array $radiografia = []): ?string
    {
        return $this->llamar(self::SYSTEM_PROMPT, $this->construirPrompt($equipo, $radiografia));
    }

    /**
     * Análisis a nivel de toda la sesión (todos los equipos juntos) — patrones
     * que se repiten entre equipos vs. diferencias notables entre ellos.
     *
     * @param array<int, array{team: string, dimensiones: array<string, string>, escuchados: string}> $equiposRadiografia
     * @param array{dimensiones: array<string, string>, escuchados: string} $radiografiaGlobal
     */
    public function analizarGlobal(array $equiposRadiografia, array $radiografiaGlobal): ?string
    {
        if (count($equiposRadiografia) < 2) {
            return null;
        }

        return $this->llamar(self::SYSTEM_PROMPT_GLOBAL, $this->construirPromptGlobal($equiposRadiografia, $radiografiaGlobal));
    }

    /**
     * Análisis entre varias sesiones cerradas del mismo cliente, a través
     * del tiempo (no entre equipos de una sola sesión — ver analizarGlobal).
     *
     * @param array<int, array{fecha: string, dimensiones: array<string, string>, escuchados: string}> $porSesion
     * @param array{dimensiones: array<string, string>, escuchados: string} $radiografiaConsolidada
     */
    public function analizarConsolidadoCliente(array $porSesion, array $radiografiaConsolidada): ?string
    {
        if (count($porSesion) < 2) {
            return null;
        }

        return $this->llamar(self::SYSTEM_PROMPT_CONSOLIDADO, $this->construirPromptConsolidado($porSesion, $radiografiaConsolidada));
    }

    private function llamar(string $systemPrompt, string $userPrompt): ?string
    {
        $cfgFile = APPPATH . 'Config/Kimi.' . ENVIRONMENT . '.php';
        if (!is_file($cfgFile)) {
            return null;
        }
        $cfg = require $cfgFile;

        $payload = json_encode([
            'model' => $cfg['model'] ?? 'kimi-k3',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt],
            ],
            'max_tokens' => 1800,
            'reasoning_effort' => 'low',
        ]);

        $ch = curl_init('https://api.moonshot.ai/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $cfg['api_key'],
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_TIMEOUT    => 60,
        ]);
        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($body === false || $status < 200 || $status >= 300) {
            log_message('error', 'KimiAnalisis: fallo la llamada (status ' . $status . ')');
            return null;
        }

        $data = json_decode($body, true);
        $texto = $data['choices'][0]['message']['content'] ?? null;

        return $texto !== null && trim($texto) !== '' ? trim($texto) : null;
    }

    private function construirPromptConsolidado(array $porSesion, array $radiografiaConsolidada): string
    {
        $lineas = ['Historial de ' . count($porSesion) . ' sesiones cerradas del mismo cliente, del mismo '
            . 'ejercicio, en orden cronológico:', ''];

        foreach ($porSesion as $s) {
            $lineas[] = 'Sesión del ' . $s['fecha'] . ':';
            foreach ($s['dimensiones'] as $dimension => $nivel) {
                $lineas[] = '- ' . $dimension . ': ' . $nivel;
            }
            $lineas[] = '- Percepción de haber sido escuchado: ' . $s['escuchados'];
            $lineas[] = '';
        }

        if (!empty($radiografiaConsolidada['dimensiones'])) {
            $lineas[] = 'Promedio consolidado de todas las sesiones juntas:';
            foreach ($radiografiaConsolidada['dimensiones'] as $dimension => $nivel) {
                $lineas[] = '- ' . $dimension . ': ' . $nivel;
            }
            $lineas[] = '- Percepción de haber sido escuchado: ' . ($radiografiaConsolidada['escuchados'] ?? '—');
        }

        $lineas[] = '';
        $lineas[] = 'Dame el análisis para el facilitador, siguiendo exactamente el formato de salida indicado.';

        return implode("\n", $lineas);
    }

    private function construirPromptGlobal(array $equiposRadiografia, array $radiografiaGlobal): string
    {
        $lineas = ['Resumen de ' . count($equiposRadiografia) . ' equipos que pasaron por el mismo '
            . 'ejercicio en la misma sesión:', ''];

        foreach ($equiposRadiografia as $eq) {
            $lineas[] = $eq['team'] . ':';
            foreach ($eq['dimensiones'] as $dimension => $nivel) {
                $lineas[] = '- ' . $dimension . ': ' . $nivel;
            }
            $lineas[] = '- Percepción de haber sido escuchado: ' . $eq['escuchados'];
            $lineas[] = '';
        }

        if (!empty($radiografiaGlobal['dimensiones'])) {
            $lineas[] = 'Promedio de toda la sesión (todos los equipos juntos):';
            foreach ($radiografiaGlobal['dimensiones'] as $dimension => $nivel) {
                $lineas[] = '- ' . $dimension . ': ' . $nivel;
            }
            $lineas[] = '- Percepción de haber sido escuchado: ' . ($radiografiaGlobal['escuchados'] ?? '—');
        }

        $lineas[] = '';
        $lineas[] = 'Dame el análisis para el facilitador, siguiendo exactamente el formato de salida indicado.';

        return implode("\n", $lineas);
    }

    private function construirPrompt(array $equipo, array $radiografia): string
    {
        $lineas = ['Equipo "' . $equipo['team'] . '" (' . count($equipo['personas']) . ' personas). '
            . 'La trayectoria de cada quien, etapa por etapa:', ''];

        foreach ($equipo['personas'] as $persona) {
            $pasos = [];
            foreach ($persona['momentos'] as $numMomento => $etiqueta) {
                $pasos[] = $etiqueta;
            }
            $lineas[] = $persona['nombre'] . ' (' . $persona['rol'] . '): ' . implode(' → ', $pasos);
        }

        if (!empty($radiografia['dimensiones'])) {
            $lineas[] = '';
            $lineas[] = 'Radiografía del equipo, ya calculada matemáticamente a partir de las opciones '
                . 'que eligió cada quien (no la recalcules, es un hecho):';
            foreach ($radiografia['dimensiones'] as $dimension => $nivel) {
                $lineas[] = '- ' . $dimension . ': ' . $nivel;
            }
            $lineas[] = '- Percepción de haber sido escuchado (al final): ' . ($radiografia['escuchados'] ?? '—');

            $persistencia = $radiografia['dimensiones']['Persistencia ante presión'] ?? null;
            if ($persistencia === 'Baja') {
                $lineas[] = '';
                $lineas[] = 'La "Persistencia ante presión" salió BAJA: el equipo llegó a su momento de '
                    . 'mayor claridad (cuando la situación empeoró) y luego perdió fuerza (cerca del '
                    . 'cierre, bajo presión). Ese es el punto de quiebre real de este equipo — reconstrúyelo '
                    . 'con las trayectorias de arriba.';
            }
        }

        $lineas[] = '';
        $lineas[] = 'Dame el análisis para el facilitador, siguiendo exactamente el formato de salida indicado.';

        return implode("\n", $lineas);
    }
}
