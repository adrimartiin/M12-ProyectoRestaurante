<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../public/login.php");
    exit();
}

$nombre_empleado = "Nombre del Empleado"; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard El Manantial</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
</head>
<body>
<div class="navbar">
    <img src="../img/icon.png" class="icon">
    <div class="user-info">
        <div class="dropdown">
            <i class="fas fa-caret-down" style="font-size: 16px; margin-right: 10px;"></i>
            <div class="dropdown-content">
                <a href="../private/logout.php">Cerrar Sesión</a>
            </div>
        </div>
        <span><?php echo $_SESSION['nombre_usuario']; ?></span>
    </div>
</div>


    <div class="options">
        <div class="option terraza">
            <h2>Terraza</h2>
            <div class="button-container">
                <button class="select-button">Seleccionar</button>
            </div>
            <div class="extra-buttons">
                <button>Terraza 1</button>
                <button>Terraza 2</button>
                <button>Terraza 3</button>
            </div>
        </div>
        <div class="option comedor">
            <h2>Comedor</h2>
            <div class="button-container">
                <button class="select-button">Seleccionar</button>
            </div>
            <div class="extra-buttons">
                <button>Comedor 1</button>
                <button>Comedor 2</button>
                <button>Comedor 3</button>
            </div>
        </div>
        <div class="option privadas">
            <h2>Sala privada</h2>
            <div class="button-container">
                <button class="select-button">Seleccionar</button>
            </div>
            <div class="extra-buttons">
                <button>Sala 1</button>
                <button>Sala 2</button>
                <button>Sala 3</button>
            </div>
        </div>
    </div>

    <script src="../js/dashboard.js"></script>
</body>
</html>
