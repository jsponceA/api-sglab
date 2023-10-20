<?php

namespace App\Repositories;

use App\Interfaces\CargaArchivoRepositoryInterface;
use App\Models\Archivo;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CargaArchivoRepository implements CargaArchivoRepositoryInterface
{
    public function todosLosArchivo(array $params)
    {
        $buscar = $params["buscar"] ?? null;
        $cantidadRegistros = $params["cantidadRegistros"] ?? null;
        $pagina = $params["pagina"] ?? null;

        $archivos = Archivo::query()
            ->with(["usuario"])
            ->orderBy("id","DESC")
            ->paginate($cantidadRegistros,"*","page",$pagina);

        return $archivos;
    }

    public function archivoPorId(int $id)
    {
        $archivo = Archivo::query()->findOrFail($id);
        return $archivo;
    }

    public function crearArchivo(array $data)
    {
        $archivo = Archivo::query()->create($data);
        return $archivo;
    }
    public function eliminarArchivo(int $id)
    {
        $archivo = Archivo::query()->findOrFail($id)->delete();
        return $archivo;
    }

    public function cargarExcel(string $path,UploadedFile $file)
    {
        if ($file->isFile()){
            $foto = Storage::putFile($path,$file);
            return basename($foto);
        }
        return null;
    }

    public function eliminarExcel(string $path,string $nameFile)
    {
        return Storage::delete($path.'/'.$nameFile);
    }

}
