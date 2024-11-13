<?php
session_start();

// Verifica que el usuario esté logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../public/login.php");
    exit();
}

// Asume que el nombre del empleado está en la sesión
$nombre_empleado = "Nombre del Empleado"; // Aquí deberías obtener el nombre real del empleado de la sesión o base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard El Manantial</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOM7e5oF5bY5jQwEip46kY6J7wFybEF4D8G+54Y" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/dashboard.css"> <!-- Aquí enlazas tu archivo CSS -->
</head>
<body>
    <div class="navbar">
        <h1>El Manantial</h1>
        <div class="user-info">
            <span><?php echo $_SESSION["nombre_usuario"]; ?></span>
            <div class="dropdown">
                <i class="fas fa-user-circle" style="font-size: 24px;"></i>
                <div class="dropdown-content">
                    <a href="logout.php">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>

    <div class="options">
        <div class="option terraza">
            <h2>Terraza</h2>
            <button>Seleccionar</button>
        </div>
        <div class="option comedor">
            <h2>Comedor</h2>
            <button>Seleccionar</button>
        </div>
        <div class="option privadas">
            <h2>Privadas</h2>
            <button>Seleccionar</button>
        </div>
    </div>

</body>
</html>