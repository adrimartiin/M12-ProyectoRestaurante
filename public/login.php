<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Manantial - Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="login-container">
        <h1 class="restaurant-name">El Manantial</h1>
        <form class="login-form" action="login.php" method="POST">
            <label for="username">Usuario</label>
            <input type="text" id="username" name="username" placeholder="Nombre de usuario" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Contraseña" required>

            <button type="submit" class="login-button">Entrar</button>
        </form>
    </div>
</body>
</html>
