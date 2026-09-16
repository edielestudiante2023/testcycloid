<?php

namespace App\Models;

/**
 * Extiende RespuestaMomentoModel para reutilizar lo genérico (guardar,
 * respuestaDe, respondidosEnMomento, momentoActualDelEquipo, forzarAvance —
 * ninguno de esos referencia El Meridián) y sobreescribe únicamente los
 * métodos que sí dependían de el_meridian_momentos()/ElMeridianDimensiones,
 * apuntando en su lugar a volver_a_casa_momentos()/VolverACasaDimensiones.
 */
class VolverACasaAnalisisModel extends RespuestaMomentoModel
{
    /**
     * Qué opciones del Momento 5 reflejan que la persona sintió que su
     * opinión llegó con claridad a la decisión final. En "Volver a Casa"
     * los 5 roles comparten el mismo vocabulario de cierre.
     */
    private const POLARIDAD_MOMENTO_FINAL = [
        'bastante' => true,
        'poco' => false,
        'casi_nada' => false,
    ];

    /**
     * @return array<int, array{team: string, totalEquipo: int, momentoActual: int, respondidos: int, totalMomentos: int, terminado: bool}>
     */
    public function progresoPorEquipo(int $sesionId): array
    {
        helper('volver_a_casa');

        $participantModel = new ParticipantModel();
        $miembros = $participantModel->where('sesion_id', $sesionId)->where('team IS NOT NULL')->findAll();

        $porEquipo = [];
        foreach ($miembros as $miembro) {
            $porEquipo[$miembro['team']][] = $miembro;
        }

        $momentosDefinidos = volver_a_casa_momentos();
        $progreso = [];
        foreach ($porEquipo as $team => $integrantes) {
            $totalMomentos = 0;
            foreach ($integrantes as $integrante) {
                $totalMomentos = max($totalMomentos, count($momentosDefinidos[$integrante['role']]['momentos'] ?? []));
            }
            $totalEquipo = count($integrantes);
            $momentoActual = $this->momentoActualDelEquipo($sesionId, $team, $totalEquipo, $totalMomentos);
            $terminado = $momentoActual > $totalMomentos;

            $progreso[] = [
                'team'          => $team,
                'totalEquipo'   => $totalEquipo,
                'momentoActual' => $terminado ? $totalMomentos : $momentoActual,
                'respondidos'   => $terminado ? $totalEquipo : $this->respondidosEnMomento($sesionId, $team, $momentoActual),
                'totalMomentos' => $totalMomentos,
                'terminado'     => $terminado,
            ];
        }

        usort($progreso, static fn ($a, $b) => strnatcmp($a['team'], $b['team']));
        return $progreso;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function resultadosPorEquipo(int $sesionId): array
    {
        helper('volver_a_casa');
        $momentosDefinidos = volver_a_casa_momentos();

        $participantModel = new ParticipantModel();
        $miembros = $participantModel->where('sesion_id', $sesionId)->where('team IS NOT NULL')->findAll();

        $porEquipo = [];
        foreach ($miembros as $miembro) {
            $porEquipo[$miembro['team']][] = $miembro;
        }

        $resultado = [];
        foreach ($porEquipo as $team => $integrantes) {
            $totalMomentos = 0;
            foreach ($integrantes as $integrante) {
                $totalMomentos = max($totalMomentos, count($momentosDefinidos[$integrante['role']]['momentos'] ?? []));
            }

            $momentosData = [];
            for ($momento = 1; $momento <= $totalMomentos; $momento++) {
                $porRespuesta = [];
                foreach ($integrantes as $integrante) {
                    $respuesta = $this->respuestaDe((int) $integrante['id'], $momento);
                    if (!$respuesta || $respuesta['respuesta'] === 'sin_respuesta') {
                        continue;
                    }
                    $definicion = $momentosDefinidos[$integrante['role']]['momentos'][$momento] ?? null;
                    $etiqueta = $definicion['opciones'][$respuesta['respuesta']] ?? $respuesta['respuesta'];
                    $clave = $integrante['role'] . ':' . $respuesta['respuesta'];
                    if (!isset($porRespuesta[$clave])) {
                        $porRespuesta[$clave] = ['etiqueta' => $etiqueta, 'rol' => $integrante['role'], 'count' => 0];
                    }
                    $porRespuesta[$clave]['count']++;
                }
                $momentosData[$momento] = array_values($porRespuesta);
            }

            $positivos = 0;
            $totalConDato = 0;
            foreach ($integrantes as $integrante) {
                $respuesta = $this->respuestaDe((int) $integrante['id'], $totalMomentos);
                if (!$respuesta || $respuesta['respuesta'] === 'sin_respuesta') {
                    continue;
                }
                if (!array_key_exists($respuesta['respuesta'], self::POLARIDAD_MOMENTO_FINAL)) {
                    continue;
                }
                $totalConDato++;
                if (self::POLARIDAD_MOMENTO_FINAL[$respuesta['respuesta']]) {
                    $positivos++;
                }
            }

            $resultado[] = [
                'team'          => $team,
                'totalEquipo'   => count($integrantes),
                'totalMomentos' => $totalMomentos,
                'momentos'      => $momentosData,
                'positivos'     => $positivos,
                'totalConDato'  => $totalConDato,
                'resumenTexto'  => $this->resumenTexto($positivos, $totalConDato),
            ];
        }

        usort($resultado, static fn ($a, $b) => strnatcmp($a['team'], $b['team']));
        return $resultado;
    }

    /**
     * @return array<int, array{team: string, personas: array<int, array{nombre: string, rol: string, momentos: array<int, string>}>}>
     */
    public function respuestasPorPersona(int $sesionId): array
    {
        helper('volver_a_casa');
        $momentosDefinidos = volver_a_casa_momentos();

        $participantModel = new ParticipantModel();
        $miembros = $participantModel->where('sesion_id', $sesionId)->where('team IS NOT NULL')->findAll();

        $porEquipo = [];
        foreach ($miembros as $miembro) {
            $porEquipo[$miembro['team']][] = $miembro;
        }

        $resultado = [];
        foreach ($porEquipo as $team => $integrantes) {
            $personas = [];
            foreach ($integrantes as $integrante) {
                $totalMomentos = count($momentosDefinidos[$integrante['role']]['momentos'] ?? []);
                $momentos = [];
                for ($momento = 1; $momento <= $totalMomentos; $momento++) {
                    $respuesta = $this->respuestaDe((int) $integrante['id'], $momento);
                    if (!$respuesta || $respuesta['respuesta'] === 'sin_respuesta') {
                        continue;
                    }
                    $definicion = $momentosDefinidos[$integrante['role']]['momentos'][$momento] ?? null;
                    $momentos[$momento] = $definicion['opciones'][$respuesta['respuesta']] ?? $respuesta['respuesta'];
                }

                if (!empty($momentos)) {
                    $personas[] = [
                        'nombre'   => $integrante['nombre'],
                        'rol'      => $momentosDefinidos[$integrante['role']]['nombre'] ?? $integrante['role'],
                        'momentos' => $momentos,
                    ];
                }
            }

            if (!empty($personas)) {
                $resultado[] = ['team' => $team, 'personas' => $personas];
            }
        }

        usort($resultado, static fn ($a, $b) => strnatcmp($a['team'], $b['team']));
        return $resultado;
    }

    /**
     * @return array<int, array{team: string, dimensiones: array<string, string>, escuchados: string}>
     */
    public function radiografiaPorEquipo(int $sesionId): array
    {
        $porPersona = $this->respuestasPorPersonaConValores($sesionId);

        $resultado = [];
        foreach ($porPersona as $team => $integrantes) {
            $resultado[] = ['team' => $team] + $this->calcularDimensiones($integrantes);
        }

        usort($resultado, static fn ($a, $b) => strnatcmp($a['team'], $b['team']));
        return $resultado;
    }

    /**
     * @return array{dimensiones: array<string, string>, escuchados: string}
     */
    public function radiografiaGlobal(int $sesionId): array
    {
        $porPersona = $this->respuestasPorPersonaConValores($sesionId);

        $todos = [];
        foreach ($porPersona as $integrantes) {
            $todos = array_merge($todos, $integrantes);
        }

        if (empty($todos)) {
            return [];
        }

        return $this->calcularDimensiones($todos);
    }

    /**
     * @param array<int, array{nombre: string, role: string, valores: array<int, string>}> $integrantes
     * @return array{dimensiones: array<string, string>, escuchados: string}
     */
    private function calcularDimensiones(array $integrantes): array
    {
        $promedios = [1 => [], 2 => [], 3 => [], 4 => []];
        foreach ($integrantes as $integrante) {
            foreach ($integrante['valores'] as $momento => $valor) {
                if ($momento > 4) {
                    continue;
                }
                $puntaje = VolverACasaDimensiones::puntaje($momento, $integrante['role'], $valor);
                if ($puntaje !== null) {
                    $promedios[$momento][] = $puntaje;
                }
            }
        }

        $promedio = static fn (array $p) => empty($p) ? null : array_sum($p) / count($p);
        $p1 = $promedio($promedios[1]);
        $p2 = $promedio($promedios[2]);
        $p3 = $promedio($promedios[3]);
        $p4 = $promedio($promedios[4]);

        $dimensiones = [
            VolverACasaDimensiones::NOMBRES_DIMENSION[1] => $p1 === null ? '—' : VolverACasaDimensiones::nivel($p1),
            VolverACasaDimensiones::NOMBRES_DIMENSION[2] => $p2 === null ? '—' : VolverACasaDimensiones::nivel($p2),
            VolverACasaDimensiones::NOMBRES_DIMENSION[3] => $p3 === null ? '—' : VolverACasaDimensiones::nivel($p3),
            VolverACasaDimensiones::NOMBRES_DIMENSION[4] => $p4 === null ? '—' : VolverACasaDimensiones::nivel($p4),
            'Persistencia ante presión' => ($p3 === null || $p4 === null)
                ? '—'
                : VolverACasaDimensiones::nivel($p4 - $p3 + 1),
        ];

        $positivos = 0;
        $totalConDato = 0;
        foreach ($integrantes as $integrante) {
            $valorFinal = $integrante['valores'][5] ?? null;
            if ($valorFinal === null || !array_key_exists($valorFinal, self::POLARIDAD_MOMENTO_FINAL)) {
                continue;
            }
            $totalConDato++;
            if (self::POLARIDAD_MOMENTO_FINAL[$valorFinal]) {
                $positivos++;
            }
        }

        return [
            'dimensiones' => $dimensiones,
            'escuchados'  => $totalConDato === 0 ? '—' : "{$positivos}/{$totalConDato}",
        ];
    }

    /**
     * @return array<string, array<int, array{nombre: string, role: string, valores: array<int, string>}>>
     */
    private function respuestasPorPersonaConValores(int $sesionId): array
    {
        $participantModel = new ParticipantModel();
        $miembros = $participantModel->where('sesion_id', $sesionId)->where('team IS NOT NULL')->findAll();

        $porEquipo = [];
        foreach ($miembros as $miembro) {
            $valores = [];
            for ($momento = 1; $momento <= 5; $momento++) {
                $respuesta = $this->respuestaDe((int) $miembro['id'], $momento);
                if ($respuesta && $respuesta['respuesta'] !== 'sin_respuesta') {
                    $valores[$momento] = $respuesta['respuesta'];
                }
            }
            if (!empty($valores)) {
                $porEquipo[$miembro['team']][] = [
                    'nombre' => $miembro['nombre'],
                    'role'   => $miembro['role'],
                    'valores' => $valores,
                ];
            }
        }

        return $porEquipo;
    }

    /**
     * Los clientes (empresas) que tienen 2 o más sesiones CERRADAS de esta
     * dinámica — son los que ya tienen algo que consolidar entre sesiones.
     *
     * @return array<int, string>
     */
    public function clientesConsolidables(int $dinamicaId): array
    {
        $rows = $this->db->table('sesiones')
            ->select('cliente')
            ->where('dinamica_id', $dinamicaId)
            ->where('estado', 'cerrada')
            ->groupBy('cliente')
            ->having('COUNT(*) >=', 2, false)
            ->get()
            ->getResultArray();

        return array_column($rows, 'cliente');
    }

    /**
     * Todas las sesiones CERRADAS de esta dinámica que comparten el mismo
     * texto en el campo "cliente", en orden cronológico.
     *
     * @return array<int, array<string, mixed>>
     */
    public function sesionesCerradasPorCliente(int $dinamicaId, string $cliente): array
    {
        return $this->db->table('sesiones')
            ->where('dinamica_id', $dinamicaId)
            ->where('cliente', $cliente)
            ->where('estado', 'cerrada')
            ->orderBy('cerrada_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Radiografía consolidada: junta a TODOS los participantes de TODAS las
     * sesiones dadas — sin distinguir sesión ni equipo. Es la organización
     * completa, a través del tiempo, en un solo cálculo.
     *
     * @param array<int, int> $sesionIds
     * @return array{dimensiones: array<string, string>, escuchados: string}
     */
    public function radiografiaConsolidadaPorCliente(array $sesionIds): array
    {
        $todos = [];
        foreach ($sesionIds as $sesionId) {
            foreach ($this->respuestasPorPersonaConValores((int) $sesionId) as $integrantes) {
                $todos = array_merge($todos, $integrantes);
            }
        }

        if (empty($todos)) {
            return [];
        }

        return $this->calcularDimensiones($todos);
    }

    private function resumenTexto(int $positivos, int $total): string
    {
        if ($total === 0) {
            return 'Todavía no hay suficientes respuestas para sacar una conclusión.';
        }

        $ratio = $positivos / $total;
        if ($ratio >= 0.75) {
            return 'La mayoría sintió que su opinión sí se reflejó en la decisión final del equipo.';
        }

        if ($ratio >= 0.4) {
            return 'A medias: algunas opiniones llegaron a la decisión final, otras se quedaron en el camino.';
        }

        return 'La mayoría sintió que su opinión NO llegó a influir en la decisión final — señal de que hablar no es lo mismo que ser escuchado.';
    }
}
