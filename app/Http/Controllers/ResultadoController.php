<?php

namespace App\Http\Controllers;

use App\Exports\ResultadoExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class ResultadoController extends Controller
{
    public function index(Request $request)
    {
        $pagina = $request->input("pagina");
        $registrosPorPagina = $request->input("registrosPorPagina");
        $apenom = $request->input("apenom");
        $fechaInicio = $request->input("fechaInicio");
        $fechaFin = $request->input("fechaFin");
        $tipoUsuario = $request->input("tipoUsuario");
        $codigo = $request->input("codigo");

        switch ($tipoUsuario) {
            case 'paciente':
                $resultados = DB::select("SET NOCOUNT ON; exec dbo.web_ordenesxcodigopaciente :codigo", [
                    "codigo" => $codigo
                ]);
                break;
            case 'medico':
                if (!empty($apenom) && (empty($fechaInicio) && empty($fechaFin))) {
                    $resultados = DB::select("SET NOCOUNT ON; exec dbo.web_ordenesxcodigomedicoxapenom :codigo,:apenom ", [
                        "codigo" => $codigo,
                        "apenom" => '%' . $apenom . '%'
                    ]);
                } elseif (!empty($fechaInicio) || !empty($fechaFin)) {
                    $resultados = DB::select("SET NOCOUNT ON; exec dbo.web_ordenesxcodigomedicoxfecha :codigo,:fechaInicio,:fechaFin,:apenom ", [
                        "codigo" => $codigo,
                        "fechaInicio" => $fechaInicio,
                        "fechaFin" => $fechaFin,
                        "apenom" => !empty($apenom) ? ('%' . $apenom . '%') : '%'
                    ]);
                } else {
                    $resultados = DB::select("SET NOCOUNT ON; exec dbo.web_ordenesxcodigomedicoxapenom :codigo,'%'", [
                        "codigo" => $codigo
                    ]);
                }
                break;
            case 'empresa':
                if (!empty($apenom) && (empty($fechaInicio) && empty($fechaFin))) {
                    $resultados = DB::select("SET NOCOUNT ON; exec dbo.web_ordenesxcodigociaxapenom :codigo,:apenom ", [
                        "codigo" => $codigo,
                        "apenom" => '%' . $apenom . '%'
                    ]);
                } elseif (!empty($fechaInicio) || !empty($fechaFin)) {
                    $resultados = DB::select("SET NOCOUNT ON; exec dbo.web_ordenesxcodigociaxfecha :codigo,:fechaInicio,:fechaFin,:apenom ", [
                        "codigo" => $codigo,
                        "fechaInicio" => $fechaInicio,
                        "fechaFin" => $fechaFin,
                        "apenom" => !empty($apenom) ? ('%' . $apenom . '%') : '%'
                    ]);
                } else {
                    $resultados = DB::select("SET NOCOUNT ON; exec dbo.web_ordenesxcodigocia :codigo", [
                        "codigo" => $codigo
                    ]);
                }
                break;
            default:
                $resultados = [];
                break;
        }

        return response()->json([
            "resultados" => $resultados
        ], Response::HTTP_OK);
    }

    public function generarExcelResultados(Request $request)
    {
        return Excel::download(new ResultadoExport($request->all()), "resultados.xlsx");
    }

    public function generarPdfResultadoAnalisis(Request $request)
    {
        $tipoUsuario = $request->input("tipoUsuario");
        $ticket = $request->input("ticket");
        $resultado = (object)$request->input("resultado");
        $usuario = $request->input("usuario");
        $cabecera = $request->input("cabecera");

        $dataQuery = [
            "ticket" => $ticket
        ];
        switch ($tipoUsuario) {
            case 'paciente':
                $queryAnalisis = DB::select("SET NOCOUNT ON; exec dbo.web_resultado :ticket", $dataQuery);
                break;
            case 'medico':
                $queryAnalisis = DB::select("SET NOCOUNT ON; exec dbo.web_resultado :ticket", $dataQuery);
                break;
            case 'empresa':
                $queryAnalisis = DB::select("SET NOCOUNT ON; exec dbo.web_resultado :ticket", $dataQuery);
                break;
            default:
                $queryAnalisis = [];
                break;
        }

        $analisis = collect($queryAnalisis)->groupBy(function ($item) {
            return $item->grupo;
        });
        $pdf = Pdf::loadView('reportes.analisis.listado', compact("resultado", "analisis", "ticket","usuario","tipoUsuario","cabecera"));
        return $pdf->download('reporte_analisis.pdf');
    }
}
