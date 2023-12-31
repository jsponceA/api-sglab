<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CargaArchivoController;
use App\Http\Controllers\GraficoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ResultadoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
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

Route::get("/test/agruparAnalisis",[\App\Http\Controllers\TestController::class,"agruparAnalisis"]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
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

/* RUTAS PARA USUARIOS */
Route::post("resultados/generarExcelResultados",[ResultadoController::class,'generarExcelResultados']);
Route::post("resultados/generarPdfResultadoAnalisis",[ResultadoController::class,'generarPdfResultadoAnalisis']);
Route::apiResource("resultados",ResultadoController::class);
/* FIN DE RUTAS PARA USUARIOS */


Route::fallback(function (){
    return response()->json([
        "message" => "Recurso no encontrado"
    ],Response::HTTP_NOT_FOUND);
});
