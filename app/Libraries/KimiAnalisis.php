<?php

namespace App\Libraries;

/**
 * Analisis de patrones de comunicacion para el debrief de El Meridian, usando
 * Kimi (Moonshot AI). Es un texto de apoyo para el facilitador, NO un
 * diagnostico psicologico clinico de nadie.
 *
 * Si la API falla, tarda demasiado, o falta la config, devuelve null y quien
 * llama debe usar el resumen basado en reglas como respaldo — nunca debe
 * bloquear el cierre de un ejercicio en vivo.
 */
class KimiAnalisis
{
    private const SYSTEM_PROMPT = 'Eres un facilitador experto en dinamicas de liderazgo y comunicacion '
        . 'organizacional. Analizas los datos de un ejercicio de simulacion (una tripulacion ficticia que '
        . 'debe decidir si desviarse de una tormenta) para ayudar al facilitador humano en el debrief real '
        . 'con su equipo. Enfocate en PATRONES DE COMUNICACION observados durante el ejercicio: quien fue '
        . 'directo, quien suavizo su mensaje, como cambio la conviccion privada al pasar por la presion del '
        . 'grupo, y que le sugieres al facilitador para abrir el debrief. Se especifico, cita los momentos '
        . 'concretos y los nombres reales que te den. NO hagas diagnostico psicologico clinico ni etiquetes '
        . 'a nadie con rasgos de personalidad — describe comportamientos observados en este ejercicio '
        . 'puntual, no quien es la persona. Responde en TEXTO PLANO, sin Markdown: nada de "##", "**", "-", '
        . 'listas ni encabezados. Usa parrafos separados por un salto de linea en blanco, como si '
        . 'escribieras un correo. IMPORTANTE: nunca uses abreviaturas tipo "M1", "M2", "M3" para referirte '
        . 'a las etapas — eso es jerga interna que nadie mas entiende. En vez de eso describe la etapa con '
        . 'palabras, usando el nombre que te dieron para cada una (ej: "al principio", "cuando le preguntaron '
        . 'directamente al cerrar"). Maximo 220 palabras en tu respuesta final. Responde en espanol, '
        . 'tono profesional pero cercano.';

    private const NOMBRES_ETAPA = [
        1 => 'Al principio, con la primera información',
        2 => 'Después de hablar con el equipo por primera vez',
        3 => 'Cuando la situación empeoró',
        4 => 'Bajo presión, cerca del cierre',
        5 => 'Al final, en retrospectiva',
    ];

    /**
     * @param array{team: string, personas: array<int, array{nombre: string, rol: string, momentos: array<int, string>}>} $equipo
     */
    public function analizarEquipo(array $equipo): ?string
    {
        $cfgFile = APPPATH . 'Config/Kimi.' . ENVIRONMENT . '.php';
        if (!is_file($cfgFile)) {
            return null;
        }
        $cfg = require $cfgFile;

        $prompt = $this->construirPrompt($equipo);

        $payload = json_encode([
            'model' => $cfg['model'] ?? 'kimi-k3',
            'messages' => [
                ['role' => 'system', 'content' => self::SYSTEM_PROMPT],
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => 1200,
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
            CURLOPT_TIMEOUT    => 45,
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

    private function construirPrompt(array $equipo): string
    {
        $lineas = ['Equipo "' . $equipo['team'] . '" (' . count($equipo['personas']) . ' personas). '
            . 'Esto respondio cada quien en cada momento del ejercicio:', ''];

        foreach ($equipo['personas'] as $persona) {
            $lineas[] = $persona['nombre'] . ' (' . $persona['rol'] . '):';
            foreach ($persona['momentos'] as $numMomento => $etiqueta) {
                $nombreEtapa = self::NOMBRES_ETAPA[$numMomento] ?? ('Etapa ' . $numMomento);
                $lineas[] = '- ' . $nombreEtapa . ': ' . $etiqueta;
            }
            $lineas[] = '';
        }

        $lineas[] = 'Dame el análisis para el facilitador.';

        return implode("\n", $lineas);
    }
}
