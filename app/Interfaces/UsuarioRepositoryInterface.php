<?php

namespace App\Interfaces;

use Illuminate\Http\UploadedFile;

interface UsuarioRepositoryInterface
{
    public function todosLosUsuarios(array $params);
    public function usuarioPorId(int $id);
    public function crearUsuario(array $data);
    public function modificarUsuario(int $id, array $newData);
    public function eliminarUsuario(int $id);
    public function cargarFoto(string $path,UploadedFile $file);
    public function eliminarFoto(string $path,string $nameFile);
}
