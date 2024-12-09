<?php

use App\Http\Controllers\Utils\AcreedorController;
use App\Http\Controllers\JefeUnidad\SeguimientoJefeUnidadController;
use App\Http\Controllers\Excel\BalanceGeneralExcelController;
use App\Http\Controllers\Excel\DeudaPublicaExternaController;
use App\Http\Controllers\Excel\FndrExcelController;
use App\Http\Controllers\Excel\PromedioIcrEtaController;
use App\Http\Controllers\Operador\CertificadoRiocpController;
use App\Http\Controllers\Operador\NotaRiocpController;
use App\Http\Controllers\Operador\ObservacionTecnicoController;
use App\Http\Controllers\Operador\SeguimientoOperadorController;
use App\Http\Controllers\Revisor\ObservacionRevisorController;
use App\Http\Controllers\Revisor\SeguimientoRevisorController;
use App\Http\Controllers\Revisor\SubirHistorialExcelController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Usuario\AuthController;
use App\Http\Controllers\Solicitante\CronogramaDesembolsoProgramadoController;
use App\Http\Controllers\Solicitante\CronogramaServicioDeudaController;
use App\Http\Controllers\Solicitante\DocumentoAdjuntoController;
use App\Http\Controllers\Solicitante\FormularioCorrespondenciaController;
use App\Http\Controllers\Utils\EntidadesController;
use App\Http\Controllers\Utils\FirmadigitalController;
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
use App\Http\Controllers\Utils\AbrirDocumentoController;
use App\Http\Controllers\Utils\CorreoController;
use App\Http\Controllers\Utils\NotificacionesController;
use App\Http\Controllers\Utils\PdfController;

Route::post('auth/register', [AuthController::class, 'create']);
Route::post('auth/login', [AuthController::class, 'login']);

// Emails
Route::post('email/send', [CorreoController::class, 'sendEmail']);


Route::middleware(['auth:sanctum'])->group(function () {

    // Administrador 
    Route::middleware('rol:7')->group(function () {
        Route::resource('roles', RolController::class);
        Route::put('roles/delete/{id}', [RolController::class, 'deleteRol']);
        Route::get('roles/show/{id}', [RolController::class, 'showById']);
        Route::resource('menu', MenuController::class);
        Route::resource('usuarios', AuthController::class);
    });
    // jefe unidad
    Route::middleware('rol:2')->group(function () {
        Route::get('usuario/tecnico', [AuthController::class, 'getTecnicos']);
        Route::get('usuario/revisor', [AuthController::class, 'getRevisores']);
        Route::get('usuario/dgaft', [AuthController::class, 'getDGAFT']);

        // Seguimiento Jefe Unidad
        Route::post('/seguimiento/administrador/store', [SeguimientoJefeUnidadController::class, 'asignarTecnicoRevisor']);
        Route::get('/seguimiento/administrador/count-asignado', [SeguimientoJefeUnidadController::class, 'contadorAsignado']);
        Route::resource('seguimiento', SeguimientoJefeUnidadController::class);
    });

    // Jefe Unidad y solicitante
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

    // Operador (Tecnico) y Jefe Unidad
    Route::middleware('rol:3.2.4.5')->group(function () {
        Route::resource('seguimiento/operador/main', SeguimientoOperadorController::class);
        Route::resource('operador/tipo-observacion', ObservacionTecnicoController::class);
        Route::post('/seguimiento/operador/store', [SeguimientoOperadorController::class, 'asignardeOperadoraRevisor']);
        Route::get('usuario/revisor', [AuthController::class, 'getRevisores']);
        Route::get('/solicitud-riocp/id/{id}', [SolicitudRiocpController::class, 'getAllSolicitudesById']);
        Route::get('/informacion-deuda/id/{id}', [InformacionDeudaController::class, 'getInformacionById']);
        Route::get('/abrir-documento/{id}/{idTipo}', [AbrirDocumentoController::class, 'abrirAllDocumentos']);
        Route::get('/abrir-documento-riocp/{id}', [AbrirDocumentoController::class, 'abrirDocumentoRiocp']);
        Route::get('/abrir-formulario-correspondencia/{id}', [AbrirDocumentoController::class, 'abrirFormularioCorrespondencia']);
        Route::get('/cronograma-deuda/formulario/{id}', [CronogramaServicioDeudaController::class, 'getCronogramaById']);
        Route::get('/cronograma-desembolso-deuda/formulario/{id}', [CronogramaDesembolsoProgramadoController::class, 'getCronogramaDesembolso']);
        Route::get('/certificado-riocp/{idSolicitud}', [CertificadoRiocpController::class, 'obtenerDatosSolicitudes']);
        Route::post('/certificado-riocp/store', [CertificadoRiocpController::class, 'almacenarCertificadoAprobado']);

        /* Notas para certificado riocp */
        Route::get('/nota-aprobado-certificado-riocp/{solicitudId}', [NotaRiocpController::class, 'obtenerDatosNotaAprobacion']);
        Route::get('/nota-observacion-certificado-riocp/{solicitudId}', [NotaRiocpController::class, 'obtenerDatosNotaObervacion']);
        Route::get('/nota-rechazo-certificado-riocp/{solicitudId}/{sd}/{vpd}', [NotaRiocpController::class, 'obtenerDatosNotaRechazo']);
    
    });

    // Revisor y Jefe Unidad
    Route::middleware('rol:4.2')->group(function () {
        Route::post('/seguimiento/revisor/store', [SeguimientoRevisorController::class, 'asignardeRevisoraJefeUnidad']);
        Route::resource('seguimiento/revisor/main', SeguimientoRevisorController::class);
        Route::get('usuario/jefe-unidad', [AuthController::class, 'getJefeUnidad']);
        Route::get('usuario/revisor/{solicitudId}', [ObservacionRevisorController::class, 'verObservacionIdSolicitud']);

        Route::post('/subir-historial/revisor', [SubirHistorialExcelController::class, 'subirDocumento']);

        // Excel
        Route::post('import/deuda-externa', [DeudaPublicaExternaController::class, 'deudaPublicaExterna']);
        Route::post('import/fndr-excel', [FndrExcelController::class, 'fndrExcel']);
        Route::post('import/balance-general', [BalanceGeneralExcelController::class, 'balanceGeneralExcel']);
        Route::post('import/icr-eta', [PromedioIcrEtaController::class, 'icrEtaExcel']);
    });

    // DGAFT y Administrador
    Route::middleware('rol:5.2')->group(function () {});

    // todos roles
    Route::get('menu/rol/user', [MenuController::class, 'selectMenuByRol']);
    Route::get('usuarios', [AuthController::class, 'allUsers']);
    Route::get('auth/logout', [AuthController::class, 'logout']);

    Route::resource('entidades', EntidadesController::class);
    Route::resource('acreedores', AcreedorController::class);

    Route::resource('periodos', PeriodoController::class);
    Route::resource('tipos-documento', TipoDocumentoAdjuntoController::class);
    Route::get('entidades/usuario/rol', [EntidadesController::class, 'getEntidadByUser']);


    Route::resource('monedas', MonedaController::class);
    Route::put('monedas/delete/{id}', [MonedaController::class, 'deleteMoneda']);
    Route::get('monedas/show/{id}', [MonedaController::class, 'showById']);

    Route::post('/generar-pdf', [PdfController::class, 'generarPDF'])->name('generar.pdf');

    // Notificaciones
    Route::resource('notificaciones', NotificacionesController::class);
});
