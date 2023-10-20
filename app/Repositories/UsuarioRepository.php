<?php

namespace App\Repositories;

use App\Interfaces\UsuarioRepositoryInterface;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UsuarioRepository implements UsuarioRepositoryInterface
{
    public function todosLosUsuarios(array $params)
    {
        $buscar = $params["buscar"] ?? null;
        $cantidadRegistros = $params["cantidadRegistros"] ?? null;
        $pagina = $params["pagina"] ?? null;

        $usuarios = User::query()
            ->when(!empty($buscar),function ($query) use ($buscar){
                $query
                    ->where("usuario","ILIKE","%".$buscar."%")
                    ->orWhere("apellidos","ILIKE","%".$buscar."%")
                    ->orWhere("nombres","ILIKE","%".$buscar."%");
            })
            ->orderBy("id","DESC")
            ->paginate($cantidadRegistros,"*","page",$pagina);

        return $usuarios;
    }

    public function usuarioPorId(int $id)
    {
        $usuario = User::query()->findOrFail($id);
        return $usuario;
    }

    public function crearUsuario(array $data)
    {
        $usuario = User::query()->create($data);
        return $usuario;
    }

    public function modificarUsuario(int $id, array $newData)
    {
        $usuario = User::query()->findOrFail($id)->update($newData);
        return $usuario;
    }

    public function eliminarUsuario(int $id)
    {
        $usuario = User::query()->findOrFail($id)->delete();
        return $usuario;
    }

    public function cargarFoto(string $path,UploadedFile $file)
    {
        if ($file->isFile()){
            $foto = Storage::putFile($path,$file);
            return basename($foto);
        }
        return null;
    }

    public function eliminarFoto(string $path,string $nameFile)
    {
        return Storage::delete($path.'/'.$nameFile);
    }

}
