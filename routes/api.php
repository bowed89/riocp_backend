<?php

use App\Http\Controllers\AcreedorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CronogramaDesembolsoProgramadoController;
use App\Http\Controllers\CronogramaServicioDeudaController;
use App\Http\Controllers\DocumentoAdjuntoController;
use App\Http\Controllers\EntidadesController;
use App\Http\Controllers\FirmadigitalController;
use App\Http\Controllers\FormularioCorrespondenciaController;
use App\Http\Controllers\InformacionDeudaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MonedaController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\SolicitudRiocpController;
use App\Http\Controllers\TipoDocumentoAdjuntoController;

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


Route::post('auth/register', [AuthController::class, 'create']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Admistrador
    Route::middleware('rol:2')->group(function () {
        Route::resource('roles', RolController::class);
        Route::put('roles/delete/{id}', [RolController::class, 'deleteRol']);
        Route::get('roles/show/{id}', [RolController::class, 'showById']);
        Route::resource('menu', MenuController::class);
        Route::resource('usuarios', AuthController::class);
    });

    // Admistrador y solicitante
    Route::middleware('rol:1.2')->group(function () {
        Route::resource('formulario-correspondencia', FormularioCorrespondenciaController::class);
        Route::post('/formulario-correspondencia/formulario', [FormularioCorrespondenciaController::class, 'storeSolicitudFormulario']);
        Route::resource('solicitud', SolicitudController::class);

        // Firma Digital
        Route::post('/validar-firma', [FirmadigitalController::class, 'validarFirmaDigital']);


        // Formulario 1
        Route::resource('solicitud-riocp', SolicitudRiocpController::class);
        Route::post('/solicitud-riocp/formulario', [SolicitudRiocpController::class, 'storeSolicitudFormularioRiocp']);

        // Formulario 2
        Route::resource('informacion-deuda', InformacionDeudaController::class);
        Route::post('/informacion-deuda/formulario', [InformacionDeudaController::class, 'storeSolicitudInformacionDeuda']);

        // Formulario 3
        Route::post('/cronograma-deuda/formulario', [CronogramaServicioDeudaController::class, 'storeSolicitudCronogramaServicioDeuda']);

        // Formulario 4
        Route::post('/cronograma-desembolso-deuda/formulario', [CronogramaDesembolsoProgramadoController::class, 'storeCronogramaDesembolso']);

        // Documentos Adjuntos
        Route::post('/documento-adjunto/formulario', [DocumentoAdjuntoController::class, 'storeDocumentoAdjunto']);
    });

    Route::get('menu/rol/user', [MenuController::class, 'selectMenuByRol']);
    Route::get('usuarios', [AuthController::class, 'allUsers']);
    Route::get('auth/logout', [AuthController::class, 'logout']);

    Route::resource('entidades', EntidadesController::class);
    Route::resource('acreedores', AcreedorController::class);
    Route::resource('monedas', MonedaController::class);
    Route::resource('periodos', PeriodoController::class);
    Route::resource('tipos-documento', TipoDocumentoAdjuntoController::class);



    Route::get('entidades/usuario/rol', [EntidadesController::class, 'getEntidadByUser']);
});
