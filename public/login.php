<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login El Manantial</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="login-container">
        <h1 class="restaurant-name">El Manantial</h1>
        <form class="login-form" action="login.php" method="POST">
            <label for="username">Codigo de empleado</label>
            <input type="text" id="codigo_empleado" name="codigo_empleado" placeholder="Introduce el codigo de empleado">

            <label for="password">Contraseña</label>
            <input type="password" id="pwd" name="pwd" placeholder="Introduce la contraseña">

            <button type="submit" class="login-button">Entrar</button>
        </form>
    </div>
</body>
</html>
