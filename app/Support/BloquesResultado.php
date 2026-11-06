<?php

namespace App\Support;

/**
 * Agrupa las filas de un grupo de analisis en bloques por perfil para el PDF de resultados.
 *
 * Un bloque son las filas consecutivas con el mismo "perfil" (titulo + sus analisis). Los bloques de perfil
 * se marcan para no cortarse entre hojas, salvo que su altura estimada no entre en una hoja completa:
 * dompdf no puede mantener unido algo mas alto que una hoja y dejaria la hoja actual en blanco.
 */
class BloquesResultado
{
    // Alturas estimadas en puntos (pt), calibradas con el render de dompdf
    private const FILA = 13.8;
    private const FILA_TITULO = 20.0;
    private const LINEA_NOMBRE = 12.0;
    private const LINEA_REFERENCIA = 9.3;
    private const MARGEN_REFERENCIA = 12.0;
    private const FILA_GRUPO = 26.0;

    // Caracteres que caben por linea en cada columna
    private const CHARS_NOMBRE = 37;
    private const CHARS_RESULTADO = 14;
    private const CHARS_REFERENCIA = 38;
    private const CHARS_COMENTARIO = 90;

    // Proporcion de la hoja que puede ocupar un bloque para protegerlo; el resto cubre el error de la estimacion
    private const HOLGURA = 0.88;

    /**
     * @param iterable $items Filas del grupo de analisis
     * @param float $alturaUtil Alto disponible para resultados en una hoja (pt)
     * @return array<int, array{nombre: string, perfil: bool, items: array}>
     */
    public static function agrupar(iterable $items, float $alturaUtil): array
    {
        $limite = $alturaUtil * self::HOLGURA;
        $bloques = [];

        foreach (self::agruparPorPerfil($items) as $tramo) {
            // Un perfil que entra en una hoja se mantiene completo
            $extra = empty($bloques) ? self::FILA_GRUPO : 0.0;
            if ($tramo["nombre"] !== "" && self::alturaBloque($tramo["items"]) + $extra <= $limite) {
                $bloques[] = ["nombre" => $tramo["nombre"], "perfil" => true, "items" => $tramo["items"]];
                continue;
            }

            // Si no entra (o no es un perfil) se divide por subtitulos, para que cada subtitulo vaya con sus filas
            foreach (self::dividirPorSubtitulos($tramo["items"]) as $parte) {
                $extra = empty($bloques) ? self::FILA_GRUPO : 0.0;
                $protegido = $parte["titulado"] && self::alturaBloque($parte["items"]) + $extra <= $limite;
                $bloques[] = ["nombre" => $tramo["nombre"], "perfil" => $protegido, "items" => $parte["items"]];
            }
        }

        return $bloques;
    }

    /**
     * Filas consecutivas con el mismo perfil.
     */
    private static function agruparPorPerfil(iterable $items): array
    {
        $tramos = [];

        foreach ($items as $item) {
            $nombre = trim((string)$item->perfil);

            if (empty($tramos) || $tramos[count($tramos) - 1]["nombre"] !== $nombre) {
                $tramos[] = ["nombre" => $nombre, "items" => []];
            }

            $tramos[count($tramos) - 1]["items"][] = $item;
        }

        return $tramos;
    }

    /**
     * Parte un tramo en cada subtitulo (fila tipo 3 sin valores). "titulado" indica que la parte empieza
     * con el titulo del perfil o con un subtitulo.
     */
    private static function dividirPorSubtitulos(array $items): array
    {
        $partes = [];

        foreach ($items as $item) {
            if (empty($partes) || self::esSubtitulo($item)) {
                $partes[] = ["titulado" => self::esPerfil($item) || self::esSubtitulo($item), "items" => []];
            }

            $partes[count($partes) - 1]["items"][] = $item;
        }

        return $partes;
    }

    private static function esPerfil($an): bool
    {
        return $an->perfil == $an->nombreexamen && $an->tipo == 4;
    }

    private static function esSubtitulo($an): bool
    {
        return empty(trim($an->resultado)) && empty(trim($an->unidad)) && empty(trim($an->referencia)) && $an->tipo == 3;
    }

    public static function alturaBloque(array $items): float
    {
        return array_sum(array_map(fn($item) => self::alturaFila($item), $items));
    }

    private static function alturaFila($an): float
    {
        if (self::esPerfil($an) || self::esSubtitulo($an)) {
            return self::FILA_TITULO;
        }

        // Solo se imprimen las filas validadas
        if (empty($an->validadom)) {
            return 0.0;
        }

        $resultado = !empty(trim((string)$an->resutexto)) ? (string)$an->texto : (string)$an->resultado;

        $altura = max(
            self::FILA + (self::lineas($an->nombreexamen, self::CHARS_NOMBRE) - 1) * self::LINEA_NOMBRE,
            self::FILA + (self::lineas($resultado, self::CHARS_RESULTADO) - 1) * self::LINEA_NOMBRE,
            self::alturaReferencia($an)
        );

        if (!empty(trim((string)$an->comentario))) {
            $altura += self::lineas($an->comentario, self::CHARS_COMENTARIO) * 13.0 + 10.0;
        }

        return $altura;
    }

    private static function alturaReferencia($an): float
    {
        $lineas = self::lineas($an->referencia, self::CHARS_REFERENCIA, false);
        $tieneRango = !empty(trim((string)$an->val_min)) || !empty(trim((string)$an->val_max));

        if ($lineas === 0) {
            return $tieneRango ? self::FILA : 0.0;
        }

        return $lineas * self::LINEA_REFERENCIA + self::MARGEN_REFERENCIA + ($tieneRango ? 8.0 : 0.0);
    }

    /**
     * Cantidad de lineas que ocupa un texto, contando saltos de linea y el ajuste por ancho de columna.
     */
    private static function lineas($texto, int $caracteresPorLinea, bool $minimoUna = true): int
    {
        $texto = trim((string)$texto);

        if ($texto === "") {
            return $minimoUna ? 1 : 0;
        }

        $total = 0;
        foreach (preg_split('/\r\n|\r|\n/', $texto) as $linea) {
            $total += max(1, (int)ceil(mb_strlen(rtrim($linea)) / $caracteresPorLinea));
        }

        return $total;
    }
}
