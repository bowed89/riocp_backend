<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario PDF</title>

</head>
<!DOCTYPE html>

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: Arial, sans-serif;
        font-size: 14px;
        background-color: #f4f4f4;
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
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    header h2,
    header h3,
    header h4 {
        font-size: 16px;
        margin-bottom: 5px;
    }

    .fecha {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }

    .section {
        border: 1px solid #ddd;
        padding: 15px;
        margin-bottom: 20px;
    }

    .section h3 {
        font-size: 16px;
        margin-bottom: 10px;
        background-color: #e4e4e4;
        padding: 5px;
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
        font-size: 13px;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="email"],
    textarea,
    select {
        width: 100%;
        padding: 8px;
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

    input[type="date"] {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario 1</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>FORMULARIO 1</h1>
            <h2>Viceministerio del Tesoro y Crédito Público</h2>
            <h3>Dirección General de Administración y Finanzas Territoriales</h3>
            <h4>Solicitud de Registro de Inicio de Operaciones de Crédito Público</h4>
        </header>

        <section class="fecha">
            <label>Fecha:</label>
            <input type="date" value="2024-10-25">
        </section>

        <section class="section">
            <h3>SECCIÓN 1: ENTIDAD SOLICITANTE</h3>
            <input type="text" placeholder="Gobierno Autónomo Municipal de Tarvita (Villa Orías)">
        </section>

        <section class="section">
            <h3>SECCIÓN 2: INFORMACIÓN GENERAL</h3>

            <div class="field">
                <h4>2.1 Identificación de la Operación de Crédito Público (Marcar con una X)</h4>
                <label><input type="checkbox"> Crédito Público Interno</label>
                <label><input type="checkbox"> Crédito Público Externo</label>
            </div>

            <div class="field">
                <h4>2.2 Acreedor</h4>
                <select>
                    <option value="">Seleccione un Acreedor</option>
                </select>
            </div>

            <div class="field">
                <h4>2.3 Características de la Operación de Crédito Público</h4>
                <label>Monto a ser contratado:</label>
                <input type="text" placeholder="0,000.00">

                <label>Moneda de Origen:</label>
                <select>
                    <option value="">Seleccione una Moneda</option>
                </select>

                <label>Plazo (Expresado en años):</label>
                <input type="text" placeholder="Ingrese el Plazo">

                <label>Tasa de interés (Anual):</label>
                <input type="text" placeholder="Ingrese la tasa de interés">

                <label>Comisiones:</label>
                <input type="text" placeholder="Concepto">
                <input type="text" placeholder="0,00">

                <label>Periodicidad de Pago:</label>
                <select>
                    <option value="">Seleccione un Periodo</option>
                </select>

                <label>Período de Gracia (Expresado en años):</label>
                <input type="text" placeholder="Periodo de Gracia">
            </div>

            <div class="field">
                <h4>2.4 Objeto de la Operación de Crédito Público</h4>
                <textarea placeholder="Describa el objeto de la operación"></textarea>
            </div>

            <div class="field">
                <h4>2.5 Datos de Contacto del Responsable de Subsanar las Observaciones al Trámite</h4>
                <label>Nombre Completo:</label>
                <input type="text" placeholder="Ingrese el nombre completo">

                <label>Cargo:</label>
                <input type="text" placeholder="Ingrese el cargo">

                <label>Correo Electrónico:</label>
                <input type="email" placeholder="Ingrese su correo electrónico">

                <label>Número Teléfono/Celular:</label>
                <input type="text" placeholder="Ingrese el número teléfono/celular">
            </div>
        </section>
    </div>
</body>

</html>
