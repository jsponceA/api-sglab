<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct()
    {
       // $this->middleware("auth:sanctum")->only("logout");
    }

    public function login(LoginRequest $request)
    {
        $usuario = $request->input("usuario");
        $clave = $request->input("clave");
        $tipoUsuario = $request->input("tipoUsuario");

       $validarDatos = $this->verificarUsuario($usuario,$clave,$tipoUsuario);

        if (!empty($validarDatos->codigo)){

            $usuarioData = [
                "usuario" => $usuario,
                "apenom" => !empty($validarDatos->apenom) ? $validarDatos->apenom : $validarDatos->nombre,
                "tipoUsuario" => $tipoUsuario,
                "codigo" => $validarDatos->codigo,
                "cabecera" => $validarDatos->cabecera ?? 2
            ];

            return response()->json([
                "message" => "Usuario autenticado con exito",
                "usuario" => $usuarioData,
                "token" => $validarDatos->codigo
            ],Response::HTTP_OK);

        }else{
            return response()->json([
                "message" => "Error credenciales no validas"
            ],Response::HTTP_NOT_FOUND);
        }
    }

    private function verificarUsuario($usuario,$clave,$tipoUsuario)
    {
        $dataLogin = [
            "usuario" => $usuario,
            "clave" => $clave
        ];
        switch ($tipoUsuario){
            case 'paciente':
                $query = DB::selectOne("exec dbo.web_pacientexdnixclave :usuario, :clave",$dataLogin);
                break;
            case 'medico':
                $query = DB::selectOne("exec dbo.web_buscamedicoxusuario :usuario, :clave",$dataLogin);
                break;
            case 'empresa':
                $query = DB::selectOne("exec dbo.web_buscaempresaxcodigo :usuario, :clave",$dataLogin);
                break;
            default:
                $query = null;
                break;
        }


        return $query;
    }

    public function logout(Request $request)
    {
        //$request->user()->currentAccessToken()->delete();
        return response()->json(null,Response::HTTP_NO_CONTENT);
    }
}
