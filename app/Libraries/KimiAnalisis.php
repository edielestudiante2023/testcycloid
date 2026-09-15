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
        $cfgFile = APPPATH . 'Config/Kimi.' . ENVIRONMENT . '.php';
        if (!is_file($cfgFile)) {
            return null;
        }
        $cfg = require $cfgFile;

        $prompt = $this->construirPrompt($equipo, $radiografia);

        $payload = json_encode([
            'model' => $cfg['model'] ?? 'kimi-k3',
            'messages' => [
                ['role' => 'system', 'content' => self::SYSTEM_PROMPT],
                ['role' => 'user', 'content' => $prompt],
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
