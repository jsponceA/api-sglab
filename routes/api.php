<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ResultadoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get("/",function (){
    return response()->json([
        "message" => "Api privada"
    ],Response::HTTP_OK);
});

Route::get("/test/crearEmpresa",[\App\Http\Controllers\TestController::class,"crearEmpresa"]);
Route::get("/test/comandos",function (){
    \Illuminate\Support\Facades\Artisan::call("storage:link");
});

/* RUTAS PARA AUTH */
Route::post("auth/login",[AuthController::class,"login"]);
Route::post("auth/logout",[AuthController::class,"logout"]);
/* RUTAS PARA AUTH */


/* RUTAS PARA PERFIL */
Route::apiResource("perfil",PerfilController::class);
/* FIN DE RUTAS PARA PERFIL */

/* RUTAS PARA USUARIOS */
Route::apiResource("usuarios",UsuarioController::class);
/* FIN DE RUTAS PARA USUARIOS */

/* RUTAS PARA RESULTADOS */
Route::post("resultados/generarExcelResultados",[ResultadoController::class,'generarExcelResultados']);
Route::post("resultados/generarPdfResultadoAnalisis",[ResultadoController::class,'generarPdfResultadoAnalisis']);
Route::apiResource("resultados",ResultadoController::class);
/* FIN DE RUTAS PARA RESULTADOS */

/* RUTAS PARA EMPRESAS */
Route::post("empresas/sincronizarNuevasEmpresas",[EmpresaController::class,'sincronizarNuevasEmpresas']);
Route::post("empresas/previsualizarPdf",[EmpresaController::class,'previsualizarPdf']);
Route::apiResource("empresas",EmpresaController::class);
/* FIN DE RUTAS PARA EMPRESAS */



Route::fallback(function (){
    return response()->json([
        "message" => "Recurso no encontrado"
    ],Response::HTTP_NOT_FOUND);
});
