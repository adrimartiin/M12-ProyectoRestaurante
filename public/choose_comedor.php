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
    <link rel="stylesheet" href="../css/choose_comedor.css">
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
        <span><?php echo $nombre_empleado; ?></span>
    </div>
</div>

<div class="options">
    <div class="option comedor1">
        <h2>Comedor 1</h2>
        <div class="button-container">
            <a href="Comedor2.php" class="select-button">Seleccionar</a>
        </div>
    </div>
    <div class="option comedor2">
        <h2>Comedor 1</h2>
        <div class="button-container">
            <a href="Comedor3.php" class="select-button">Seleccionar</a>
        </div>
    </div>
</div>

<script src="../js/dashboard.js"></script>
</body>
</html>
