<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerfilRequest;
use App\Interfaces\UsuarioRepositoryInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PerfilController extends Controller
{
    protected $usuarioRepository;
    public function __construct(UsuarioRepositoryInterface $usuarioRepository)
    {
        $this->middleware("auth:sanctum");
        $this->usuarioRepository = $usuarioRepository;
    }

    public function show(Request $request,$id)
    {
        return response()->json([
            "usuario" => $this->usuarioRepository->usuarioPorId($id)
        ],Response::HTTP_OK);
    }

    public function update(PerfilRequest $request,$id)
    {
        $data = $request->except("foto");
        $usuarioActual = $this->usuarioRepository->usuarioPorId($id);

        if ($request->hasFile("foto")){
            if (!empty($usuarioActual->foto)){
                $this->usuarioRepository->eliminarFoto("usuarios",$usuarioActual->foto);
            }
            $data["foto"] = $this->usuarioRepository->cargarFoto("usuarios",$request->file("foto"));
        }

        if ($request->input("clave")){
            $data["clave"] = bcrypt($data["clave"]);
        }else{
            unset($data["clave"]);
        }

        $this->usuarioRepository->modificarUsuario($id,$data);

        return response()->json([
            "message" => "Usuario modificado con exito.",
            "usuario" => $this->usuarioRepository->usuarioPorId($id)
        ],Response::HTTP_OK);
    }
}
