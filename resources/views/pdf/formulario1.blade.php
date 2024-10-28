<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario PDF</title>
    <!-- Enlace al archivo CSS -->
    <link href="css/formulario1.css" rel="stylesheet" />

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        @media print {

            body,
            html {
                margin: 0;
                /* Elimina márgenes del body y html */
                padding: 0;
                /* Elimina padding del body y html */
                width: 100%;
                /* Asegúrate de que ocupe todo el ancho */
                height: auto;
                /* Asegúrate de que ocupe toda la altura */
            }

            .section {
                margin: 0;
                /* Ajusta los márgenes internos de la sección si es necesario */
                padding: 0;
                /* Evita padding si no lo deseas */
            }
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            background-color: #fff;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        header h1 {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        header h2,
        header h3,
        header h4 {
            font-size: 9px;
            margin-bottom: 2px;
        }

        .fecha {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 7px;

        }

        .seccion-uno {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;

        }

        .section {
            padding: 10px;
        }

        .section h3 {
            font-size: 9px;
            background-color: #cfcfcf;
            padding: 5px;
        }

        .subsanar {
            display: flex;
            justify-content: space-around;
            margin-left: 15px;
            margin-bottom: 3px;
            gap: 12rem;
        }

        .field {
            margin-bottom: 15px;
        }

        .field h4 {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .field label {
            display: block;
            font-size: 10px;
            margin-bottom: 5px;
        }

        .date {
            padding: 6px;
            width: 13%;
            font-size: 9px;
            margin-bottom: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="text"],
        input[type="email"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            font-size: 9px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        textarea {
            height: 80px;
        }

        input[type="checkbox"] {
            margin-right: 10px;
        }



        /* ESTILOS DE RADIO BUTTONS */
        .radio-group {
            display: flex;
            justify-content: center;
            margin-top: 20PX;
            gap: 8rem;
        }

        /* Oculta el radio button por defecto */
        .radio-group input[type="radio"] {
            display: none;
        }

        /* Estilo para el label del radio */
        .radio-label {
            display: inline-block;
            padding-left: 35px;
            position: relative;
            cursor: pointer;
            font-size: 9px;
            user-select: none;
        }

        /* Estilo del cuadrado */
        .radio-label::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 17px;
            height: 17px;
            border: 2px solid black;
            background-color: white;
            box-sizing: border-box;
        }

        /* Estilo para la X cuando está seleccionada */
        .radio-group input[type="radio"]:checked+.radio-label::after {
            content: 'X';
            position: absolute;
            left: 5px;
            top: 0;
            font-size: 14px;
            font-weight: 600;
            color: black;
        }

        /* TABLA
        2.3 CARACTERÍSTICAS DE LA OPERACIÓN DE CRÉDITO PÚBLICO
        */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;

        }

        th,
        td {
            padding: 8px;
            /* Agrega espacio dentro de las celdas */
            text-align: left;
            font-size: 9px;
            /* Alinea el texto a la izquierda */
        }

        th {
            /* Color de fondo para los encabezados */
        }

        /* Opcional: Bordes específicos para los lados de la tabla */
        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        .declaracion-jurada {
            border: 1px solid rgb(176, 170, 170);
            padding: 5px 5px 5px 5px;
            line-height: 1.5;
            font-size: 0.5rem;
            text-transform: uppercase;
            text-align: justify;
        }
    </style>
</head>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario 1</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <header>
            <h2>Viceministerio del Tesoro y Crédito Público</h2>
            <h2>Dirección General de Administración y Finanzas Territoriales</h2>
            <h1>SOLICITUD DE REGISTRO DE INICIO DE OPERACIONES</h1>
            <h1> DE CRÉDITO PÚBLICO</h1>
        </header>

        <div class="fecha">
            <input class="date" value="FECHA {{ $datos['fecha_actual'] ?? '' }}">
        </div>

        <div class="section">
            <h3>SECCIÓN 1: ENTIDAD SOLICITANTE</h3>
            <div class="seccion-uno">
                <input type="text" value="{{ $datos['entidad'] ?? '' }}">
                <input type="text" value="{{ $datos['nombre_entidad'] ?? '' }}">
            </div>
        </div>

        <section class="section">
            <h3 style="margin-bottom: 5px">SECCIÓN 2: INFORMACIÓN GENERAL</h3>

            <div class="field">
                <h3>2.1 INDENTIFICACIÓN DE LA OPERACIÓN DE CRÉDITO PÚBLICO (Marcar con una X)</h3>
                <div class="radio-group">
                    <input type="radio" id="credito-interno" value="1" name="credito"
                        {{ $datos['identificador_id'] == '1' ? 'checked' : '' }}>
                    <label class="radio-label" for="credito-interno">Crédito Público Interno</label>

                    <input type="radio" id="credito-externo" value="2" name="credito"
                        {{ $datos['identificador_id'] == '2' ? 'checked' : '' }}>
                    <label class="radio-label" for="credito-externo">Crédito Público Externo</label>
                </div>


            </div>

            <div class="field">
                <h3>2.2 ACREEDOR</h3>
                <input type="text" value="{{ $datos['nombre_acreedor'] ?? '' }}">
            </div>

            <div class="field">
                <h3>2.3 CARACTERÍSTICAS DE LA OPERACIÓN DE CRÉDITO PÚBLICO</h3>
                <table>

                    <tbody>
                        <tr>
                            <td>2.3.1 Monto a ser contratado</td>
                            <td>{{ isset($datos['monto_total']) ? number_format($datos['monto_total'], 2, ',', '.') : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>2.3.2 Moneda de Origen:</td>
                            <td>{{ $datos['moneda_origen'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>2.3.3 Plazo (Expresado en años):</td>
                            <td>{{ $datos['plazo'] ?? '' }} años</td>
                        </tr>
                        <tr>
                            <td>2.3.4 Tasa de interés (Anual):</td>
                            <td>{{ isset($datos['interes_anual']) ? number_format($datos['interes_anual'], 2, ',', '.') : '' }}
                                %
                            </td>
                        </tr>
                        <tr>
                            <td>2.3.5 Comisiones:</td>
                            <td>
                                Concepto: {{ $datos['comision_concepto'] ?? '__________' }} Tasa:
                                {{ $datos['comision_tasa'] ? number_format($datos['comision_tasa'], 2, ',', '.') . '%' : '__________' }}
                            </td>
                        </tr>
                        <tr>
                            <td>2.3.6 Periodicidad de Pago:</td>
                            <td>{{ $datos['nombre_periodo'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>2.3.7 Periodo de Gracia (Expresado en años):</td>
                            <td>{{ $datos['periodo_gracia'] ?? '' }} años</td>
                        </tr>
                    </tbody>
                </table>


            </div>

            <div class="field">
                <h3>
                    2.4 OBJETO DE LA OPERACIÒN DE CRÈDITO PÙBLICO
                </h3>
                {{-- <input type="text" value="{{ $datos['objeto_operacion_credito'] ?? '' }}"> --}}

                <textarea>{{ $datos['objeto_operacion_credito'] ?? '' }}</textarea>
                <label style="font-size: 9px; text-align: center;">
                    (Cuando el financiamiento esté destinado a varios proyectos de inversión, se deberá adjuntar los
                    nombres, montos y aclarar si los recursos cubrirán contrapartes locales)
                </label>
            </div>

            <div class="field">
                <h3>2.5 DATOS DE CONTACTO DEL RESPONSABLE DE SUBSANAR LAS OBSERVACIONES AL TRÀMITE</h3>
                <div style="border: 1px solid rgb(176, 170, 170); padding-top: 10px;">

                    <div class="subsanar">
                        <label style="width: 40%; font-size: 9px;"> 1. Nombre Completo:</label>
                        <label style="width: 60%; font-size: 9px;"> {{ $datos['nombre_completo'] ?? '' }}</label>
                    </div>
                    <div class="subsanar">
                        <label style="width: 40%; font-size: 9px;"> 2. Cargo:</label>
                        <label style="width: 60%; font-size: 9px;">{{ $datos['cargo'] ?? '' }}</label>
                    </div>
                    <div class="subsanar">
                        <label style="width: 40%; font-size: 9px;"> 3. Correo Electrónico:</label>
                        <label style="width: 60%; font-size: 9px;"> {{ $datos['correo_electronico'] ?? '' }}</label>
                    </div>
                    <div class="subsanar">
                        <label style="width: 40%; font-size: 9px;"> 4. Número de Teléfono / Celular:</label>
                        <label style="width: 60%; font-size: 9px;"> {{ $datos['telefono'] ?? '' }}</label>
                    </div>
                    <label
                        style="margin-top: 10px; text-align: justify; font-size: 8px; margin-left: 10px; margin-right: 10px;">
                        Nota: De no tener los datos del contacto y en caso de presentarse observaciones
                        a la información presentada por la entidad, se procederá a la devolución del trámite.
                    </label>
                </div>
            </div>

            <div class="field">
                <h3>SECCIÓN 3 DELCARACIÓN JURADA Y COMPROMISO DE USO DE RECURSOS</h3>
                <p class="declaracion-jurada">
                    YO  {{ $datos['declaracion_jurada'] ?? '' }}
                    EN MI CALIDAD DE MÁXIMA AUTORIDAD EJECUTIVA DE {{ $datos['nombre_entidad'] ?? '' }}
                    DECLARO HABER EVALUADO DIFERENTES FUENTES DE FINANCIAMIENTO, ELIGIENDO EL CRÉDITO
                    CON LAS CONDICIONES FINANCIERAS MAS VENTAJOSAS DISPONIBLES EN EL MERCADO Y ME COMPROMETO
                    A UTILIZAR LOS RECURSOS PROVENIENTES DE LA OPERACIÓN DE CRÉDITO PÚBLICO SOLICITADA PARA
                    LOS FINES DECLARADOS EN EL PRESENTE FORMULARIO(RUBRO 2.4 OBJETO DE LA OPERACIÓN DE
                    CRÉDITO PÚBLICO).
                </p>


            </div>

        </section>
    </div>
</body>

</html>
