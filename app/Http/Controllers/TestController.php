<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{

    public function login(Request $request)
    {
        $usuario = $request->input("usuario");
        $clave = $request->input("clave");
        $tipoUsuario = $request->input("tipoUsuario");

        $validarDatos = $this->verificarUsuario($usuario,$clave,$tipoUsuario);

        dd($validarDatos);
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

    public function agruparAnalisis(Request $request)
    {
        $tipoUsuario = "paciente";
        $dataQuery = [
            "ticket" => "2310041001"
        ];
        switch ($tipoUsuario){
            case 'paciente':
                $queryAnalisis = DB::select("SET NOCOUNT ON; exec dbo.web_resultado :ticket",$dataQuery);
                break;
            case 'medico':
                $queryAnalisis = DB::select("SET NOCOUNT ON; exec dbo.web_resultado :ticket",$dataQuery);
                break;
            case 'empresa':
                $queryAnalisis = DB::select("SET NOCOUNT ON; exec dbo.web_resultado :ticket",$dataQuery);
                break;
            default:
                $queryAnalisis = [];
                break;
        }

        $analisis = collect($queryAnalisis)->groupBy(function ($item){
            return $item->grupo;
        });
        dd($analisis);
    }
}
