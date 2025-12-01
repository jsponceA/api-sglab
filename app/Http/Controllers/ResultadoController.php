<?php

namespace App\Http\Controllers;

use App\Exports\ResultadoExport;
use App\Mail\Resultado;
use App\Models\Empresa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
                $resultados = DB::select("SET NOCOUNT ON; exec dbo.web_ordenesxcodigopaciente :codigo,:page,:perPage", [
                    "codigo" => $codigo,
                    "page" => $pagina,
                    "perPage" => $registrosPorPagina
                ]);
                //filtro para pacientes
                $resultados = collect($resultados)->filter(fn($item) => empty($item->cia))->values()->all();
                break;

            case 'medico':
                if (!empty($apenom) && (empty($fechaInicio) && empty($fechaFin))) {
                    $resultados = DB::select("SET NOCOUNT ON; exec dbo.web_ordenesxcodigomedicoxapenom :codigo,:apenom,:page,:perPage ", [
                        "codigo" => $codigo,
                        "apenom" => '%' . $apenom . '%',
                        "page" => $pagina,
                        "perPage" => $registrosPorPagina
                    ]);
                } elseif (!empty($fechaInicio) || !empty($fechaFin)) {
                    $resultados = DB::select("SET NOCOUNT ON; exec dbo.web_ordenesxcodigomedicoxfecha :codigo,:fechaInicio,:fechaFin,:apenom,:page,:perPage", [
                        "codigo" => $codigo,
                        "fechaInicio" => $fechaInicio,
                        "fechaFin" => $fechaFin,
                        "apenom" => !empty($apenom) ? ('%' . $apenom . '%') : '%',
                        "page" => $pagina,
                        "perPage" => $registrosPorPagina
                    ]);
                } else {
                    $resultados = DB::select("SET NOCOUNT ON; exec dbo.web_ordenesxcodigomedicoxapenom :codigo,'%',:page,:perPage", [
                        "codigo" => $codigo,
                        "page" => $pagina,
                        "perPage" => $registrosPorPagina
                    ]);
                }
                break;
            case 'empresa':
                if (!empty($apenom) && (empty($fechaInicio) && empty($fechaFin))) {
                    $resultados = DB::select("SET NOCOUNT ON; exec dbo.web_ordenesxcodigociaxapenom :codigo,:apenom,:page,:perPage", [
                        "codigo" => $codigo,
                        "apenom" => '%' . $apenom . '%',
                        "page" => $pagina,
                        "perPage" => $registrosPorPagina
                    ]);
                } elseif (!empty($fechaInicio) || !empty($fechaFin)) {
                    $resultados = DB::select("SET NOCOUNT ON; exec dbo.web_ordenesxcodigociaxfecha :codigo,:fechaInicio,:fechaFin,:apenom,:page,:perPage ", [
                        "codigo" => $codigo,
                        "fechaInicio" => $fechaInicio,
                        "fechaFin" => $fechaFin,
                        "apenom" => !empty($apenom) ? ('%' . $apenom . '%') : '%',
                        "page" => $pagina,
                        "perPage" => $registrosPorPagina
                    ]);
                } else {
                    $resultados = DB::select("SET NOCOUNT ON; exec dbo.web_ordenesxcodigocia :codigo,:page,:perPage", [
                        "codigo" => $codigo,
                        "page" => $pagina,
                        "perPage" => $registrosPorPagina
                    ]);
                }
                break;
            default:
                $resultados = [];
                break;
        }

        // El total viene en la columna 'total' de cada fila
        $total = !empty($resultados) && !empty($resultados[0]->total) ? $resultados[0]->total : 0;
        $totalPaginas = ceil($total / $registrosPorPagina);

        // Limpiar el campo 'total' de los datos
        $resultados = array_map(function($item) {
            $data = (array) $item;
            unset($data['total']);
            return (object) $data;
        }, $resultados);

        $response = [
            'resultados' => $resultados,
            'current_page' => $pagina,
            'per_page' => $registrosPorPagina,
            'total' => $total,
            'total_pages' => $totalPaginas
        ];


        return response()->json($response, Response::HTTP_OK);
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
        $codigoEmpresa = $request->input("codigoEmpresa");
        $tipoImagen = $request->input("tipoImagen");

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

        if ($tipoUsuario == "empresa"){
            $vista = "reportes.analisis.listado_empresa";
        }else{
            $vista = "reportes.analisis.listado";
        }

        $empresa = Empresa::query()->where("codigo",$codigoEmpresa)->first();

        $pdf = Pdf::loadView($vista, compact("resultado", "analisis", "ticket","usuario","tipoUsuario","cabecera","empresa","tipoImagen"));
        return $pdf->download('reporte_analisis.pdf');
    }

    public function enviarCorreoResultado(Request $request)
    {
        $request->validate([
            "correo" => "required|email",
        ]);

        $tipoUsuario = $request->input("tipoUsuario");
        $ticket = $request->input("ticket");
        $resultado = (object)$request->input("resultado");
        $usuario = $request->input("usuario");
        $cabecera = $request->input("cabecera");
        $codigoEmpresa = $request->input("codigoEmpresa");
        $tipoImagen = $request->input("tipoImagen");
        $correo = $request->input("correo");

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

        if ($tipoUsuario == "empresa"){
            $vista = "reportes.analisis.listado_empresa";
        }else{
            $vista = "reportes.analisis.listado";
        }

        $empresa = Empresa::query()->where("codigo",$codigoEmpresa)->first();

        $pdf = Pdf::loadView($vista, compact("resultado", "analisis", "ticket","usuario","tipoUsuario","cabecera","empresa","tipoImagen"));


        try {
            Mail::send(new Resultado($correo, $resultado, $pdf->output()));

            return response()->json([
                "message" => "Se envió el resultado por correo exitosamente."
            ], Response::HTTP_OK);

        } catch (\Throwable $e) {
            return response()->json([
                "message" => "No se pudo enviar el resultado por correo. Intente nuevamente más tarde.",
                "error" => $e->getMessage(), // Opcional: quitar en producción
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
