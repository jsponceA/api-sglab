<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        $pagina = $request->input("pagina");
        $registrosPorPagina = $request->input("registrosPorPagina");
        $apenom = $request->input("apenom");

        $empresas = Empresa::query()
            ->when(!empty($apenom),function ($q) use ($apenom){
                $q
                    ->where("nombres","LIKE","%{$apenom}%")
                    ->orWhere("codigo","LIKE","%{$apenom}%");
            })
            ->where("estado",1)
            ->orderBy("nombres","ASC")
            ->paginate($registrosPorPagina,"*","page",$pagina);

        return response()->json([
            "empresas" => $empresas
        ],Response::HTTP_OK);
    }

    public function show($id)
    {
        $empresa = Empresa::query()->findOrFail($id);

        return response()->json([
            "empresa" => $empresa
        ],Response::HTTP_OK);
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            "imagen_cabecera" => "nullable|image|mimes:jpg,jpeg,png",
            "imagen_pie_pagina" => "nullable|image|mimes:jpg,jpeg,png"
        ]);

        $empresa = Empresa::query()->findOrFail($id);
        if ($request->hasFile("imagen_cabecera")){
            Storage::delete("empresas/{$empresa->imagen_cabecera}");
            $nombreImagenCabecera = Storage::putFile("empresas",$request->file("imagen_cabecera"));
            $empresa->imagen_cabecera = basename($nombreImagenCabecera);
        }
        if ($request->hasFile("imagen_pie_pagina")){
            Storage::delete("empresas/{$empresa->imagen_pie_pagina}");
            $nombreImagenPiePagina = Storage::putFile("empresas",$request->file("imagen_pie_pagina"));
            $empresa->imagen_pie_pagina = basename($nombreImagenPiePagina);
        }
        $empresa->update();

        return response()->json([
            "message" => "Datos actualizados con exito."
        ],Response::HTTP_OK);
    }

    public function previsualizarPdf(Request $request)
    {
        $codigoEmpresa = $request->input("codigoEmpresa");

        $empresa = Empresa::query()->where("codigo",$codigoEmpresa)->first();

        $pdf = Pdf::loadView("reportes.analisis.previsualizar_empresa", compact("empresa"));
        return $pdf->stream('previsualizar.pdf');
    }

    public function sincronizarNuevasEmpresas()
    {
        $actualesEmpresas = Empresa::query()->pluck("codigo")->toArray();
        $todasLasEmpresas = DB::select("SET NOCOUNT ON; EXEC web_listaempresas");
        $nuevasEmpresas = collect($todasLasEmpresas)->whereNotIn("codigo",$actualesEmpresas);

        foreach ($nuevasEmpresas as $nuevaEmpresa) {
            Empresa::query()->create([
                "codigo" => $nuevaEmpresa->codigo,
                "nombres" => trim($nuevaEmpresa->nombre),
                "estado" => 1
            ]);
        }

        return response()->json([
            "message" => "Registros nuevos ingresados: {$nuevasEmpresas->count()}"
        ],Response::HTTP_OK);
    }

    public function eliminarImagen(Request $request,$id)
    {

        $nombreCampo = $request->input("nombreCampo");
        $empresa = Empresa::query()->findOrFail($id);

        if ($nombreCampo == "imagen_cabecera"){
            Storage::delete("empresas/{$empresa->imagen_cabecera}");
            $empresa->imagen_cabecera = null;
        }

        if ($nombreCampo == "imagen_pie_pagina"){
            Storage::delete("empresas/{$empresa->imagen_pie_pagina}");
            $empresa->imagen_pie_pagina = null;
        }

        $empresa->update();

        return response()->json([
            "message" => "Se actualizaron los datos correctamente"
        ],Response::HTTP_OK);
    }

    public function actualizarReferencia(Request $request,$id)
    {
        $empresa = Empresa::query()->findOrFail($id);
        $empresa->mostrar_referencia = !$empresa->mostrar_referencia;
        $empresa->update();
        return response()->json([
            "message" => "Se actualizaron los datos correctamente"
        ],Response::HTTP_OK);
    }
}
