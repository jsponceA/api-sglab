<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Interfaces\UsuarioRepositoryInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsuarioController extends Controller
{
    protected $usuarioRepository;
    public function __construct(UsuarioRepositoryInterface $usuarioRepository)
    {
        $this->middleware("auth:sanctum");
        $this->usuarioRepository = $usuarioRepository;
    }

    public function index(Request $request)
    {
        return response()->json([
            "usuarios" => $this->usuarioRepository->todosLosUsuarios($request->all())
        ],Response::HTTP_OK);
    }

    public function store(UsuarioRequest $request)
    {
        $data = $request->except("foto");
        if ($request->hasFile("foto")){
            $data["foto"] = $this->usuarioRepository->cargarFoto("usuarios",$request->file("foto"));
        }
        $this->usuarioRepository->crearUsuario($data);

        return response()->json([
            "message" => "Usuario creado con exito."
        ],Response::HTTP_CREATED);
    }

    public function show(Request $request,$id)
    {
        return response()->json([
            "usuario" => $this->usuarioRepository->usuarioPorId($id)
        ],Response::HTTP_OK);
    }

    public function update(UsuarioRequest $request,$id)
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
            "message" => "Usuario modificado con exito."
        ],Response::HTTP_OK);
    }

    public function destroy(Request $request,$id)
    {
        $usuarioActual = $this->usuarioRepository->usuarioPorId($id);
        if (!empty($usuarioActual->foto)){
            $this->usuarioRepository->eliminarFoto("usuarios",$usuarioActual->foto);
        }
        $this->usuarioRepository->eliminarUsuario($id);
        return response()->json(null,Response::HTTP_NO_CONTENT);
    }
}
