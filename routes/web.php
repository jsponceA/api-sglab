<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/",function (){
    return response()->json([
        "message" => "Api privada"
    ],Response::HTTP_OK);
});

Route::get("/login",function (){
    return response()->json([
        "message" => "Error de autenticacion"
    ],Response::HTTP_BAD_REQUEST);
})->name("login");
