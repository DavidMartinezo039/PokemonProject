<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Set Creado</title>
</head>
<body>
<h1>¡Hola {{ $userSet->user->name }}!</h1>
<p>Has creado un nuevo set llamado <strong>{{ $userSet->name }}</strong>.</p>
<p>Puedes verlo aquí: <a href="{{ url('/user-sets/' . $userSet->id) }}">Ver Set</a></p>
<p>Gracias por usar nuestra aplicación.</p>
</body>
</html>
