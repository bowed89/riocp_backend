<?php

use App\Http\Controllers\Utils\AcreedorController;
use App\Http\Controllers\Administrador\SeguimientoController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Usuario\AuthController;
use App\Http\Controllers\Solicitante\CronogramaDesembolsoProgramadoController;
use App\Http\Controllers\Solicitante\CronogramaServicioDeudaController;
use App\Http\Controllers\Solicitante\DocumentoAdjuntoController;
use App\Http\Controllers\Utils\EntidadesController;
use App\Http\Controllers\Utils\FirmadigitalController;
use App\Http\Controllers\Solicitante\FormularioCorrespondenciaController;
use App\Http\Controllers\Solicitante\InformacionDeudaController;
use App\Http\Controllers\Utils\MenuController;
use App\Http\Controllers\Utils\MenuPestaniasSolicitanteController;
use App\Http\Controllers\Utils\MonedaController;
use App\Http\Controllers\Utils\PeriodoController;
use App\Http\Controllers\Usuario\RolController;
use App\Http\Controllers\Solicitante\SolicitudController;
use App\Http\Controllers\Solicitante\SolicitudRiocpController;
use App\Http\Controllers\Solicitante\TipoDocumentoAdjuntoController;
use App\Http\Controllers\Solicitante\TramitesController;

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
        Route::get('usuario/tecnico', [AuthController::class, 'getTecnicos']);

        // Seguimiento
        Route::post('/seguimiento/administrador/store', [SeguimientoController::class, 'storeAdministrador']);

    });

    // Admistrador y solicitante
    Route::middleware('rol:1.2')->group(function () {
        
        Route::resource('formulario-correspondencia', FormularioCorrespondenciaController::class);
        Route::post('/formulario-correspondencia/formulario', [FormularioCorrespondenciaController::class, 'storeSolicitudFormulario']);
        Route::resource('solicitud', SolicitudController::class);

        // Menu Pestañas Solicitante
        Route::resource('menu-pestanias', MenuPestaniasSolicitanteController::class);

        // Firma Digital
        Route::post('/validar-firma', [FirmadigitalController::class, 'validarFirmaDigital']);


        // Formulario 1
        Route::resource('solicitud-riocp', SolicitudRiocpController::class);
        Route::post('/solicitud-riocp/formulario', [SolicitudRiocpController::class, 'storeSolicitudFormularioRiocp']);

        // Formulario 2
        Route::resource('informacion-deuda', InformacionDeudaController::class);
        Route::post('/informacion-deuda/formulario', [InformacionDeudaController::class, 'storeSolicitudInformacionDeuda']);

        // Formulario 3
        Route::post('/cronograma-deuda/formulario', [CronogramaServicioDeudaController::class, 'storeCronogramaServicioDeuda']);

        // Formulario 4
        Route::post('/cronograma-desembolso-deuda/formulario', [CronogramaDesembolsoProgramadoController::class, 'storeCronogramaDesembolso']);

        // Documentos Adjuntos
        Route::post('/documento-adjunto-1-2/formulario', [DocumentoAdjuntoController::class, 'storeDocumentosFormulario1']);
        Route::post('/documento-adjunto-2/formulario', [DocumentoAdjuntoController::class, 'storeDocumentoForm2']);
        Route::post('/documento-adjunto-3/formulario', [DocumentoAdjuntoController::class, 'storeDocumentoForm3']);
   
        // Tramites Solicitante
        Route::resource('tramite-solicitante', TramitesController::class);

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

    Route::resource('seguimiento', SeguimientoController::class);
});
