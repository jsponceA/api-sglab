<?php

namespace App\Interfaces;

use Illuminate\Http\UploadedFile;

interface CargaArchivoRepositoryInterface
{
    public function todosLosArchivo(array $params);
    public function archivoPorId(int $id);
    public function crearArchivo(array $data);
    public function eliminarArchivo(int $id);
    public function cargarExcel(string $path,UploadedFile $file);
    public function eliminarExcel(string $path,string $nameFile);
}
