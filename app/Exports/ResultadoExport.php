<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpParser\Node\Expr\Cast\Object_;

class ResultadoExport implements FromView,ShouldAutoSize
{
    private $params;
    public function __construct($params)
    {
        $this->params = (Object) $params;
    }

    public function view(): View
    {
        $apenom = $this->params?->apenom;
        $fechaInicio = $this->params?->fechaInicio;
        $fechaFin = $this->params?->fechaFin;
        $tipoUsuario = $this->params?->tipoUsuario;
        $codigo = $this->params?->codigo;

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



        return view("reportes.resultados.listado_excel")->with(compact("resultados"));
    }
}
