<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Registro</title>
</head>
<body>
    <h1>Nuevo Registro en el Sistema</h1>
    <p>Se ha creado un nuevo registro con los siguientes detalles:</p>
    <ul>
        <li>ID: {{ $request->id }}</li>
        <li>Nombre: {{ $request->nombre }}</li>
        <li>Apellido: {{ $request->apellido }}</li>
    </ul>
</body>
</html>
