<?php

use App\Http\Controllers\LambdaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Ruta para procesar texto
Route::post('/lambda/procesar', [LambdaController::class, 'procesarTexto']);

// Ruta para subir y procesar imágenes REALES
Route::post('/lambda/imagen', [LambdaController::class, 'procesarImagen']);
Route::get('/lambda/imagen/{nombre}', [LambdaController::class, 'obtenerImagenProcesada']);

// Ruta de salud
Route::get('/status', function () {
    return response()->json(['status' => 'API active con S3 + Lambda']);
});
