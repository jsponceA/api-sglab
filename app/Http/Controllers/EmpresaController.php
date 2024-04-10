<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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
            ->where("nombres","LIKE","%{$apenom}%")
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
}
