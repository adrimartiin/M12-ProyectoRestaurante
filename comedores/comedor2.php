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
    <link rel="stylesheet" href="../css/comedor2.css">
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

<!-- Botones en filas -->
<div class="button-container">
    <div class="button-row">
        <button class="btn" onclick="openPopup('Mesa 1')">Mesa 1</button>
        <button class="btn" onclick="openPopup('Mesa 2')">Mesa 2</button>
        <button class="btn" onclick="openPopup('Mesa 3')">Mesa 3</button>
    </div>
    <div class="button-row">
        <button class="btn" onclick="openPopup('Mesa 4')">Mesa 4</button>
    </div>
    <div class="button-row">
        <button class="btn-bottom" onclick="openPopup('Mesa 5')">Mesa 5</button>
        <button class="btn-bottom" onclick="openPopup('Mesa 6')">Mesa 6</button>
    </div>
</div>

<!-- Popup Formulario -->
<div id="popup" class="popup">
    <div class="popup-content">
        <span class="popup-close" onclick="closePopup()">&times;</span>
        <h2>Formulario de Reservación</h2>
        <p>Reserva para <span id="mesa-name"></span></p>
        <form action="" method="POST">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required>
            <label for="personas">Personas</label>
            <input type="number" id="persona" name="persona" required>
            <button type="submit">Reservar</button>
        </form>
    </div>
</div>

<script src="../js/dashboard.js"></script>
<script>
// Función para abrir el popup y mostrar el nombre de la mesa
function openPopup(mesaName) {
    document.getElementById("mesa-name").textContent = mesaName; // Asigna el nombre de la mesa
    document.getElementById("popup").style.display = "block"; // Muestra el popup
}

// Función para cerrar el popup
function closePopup() {
    document.getElementById("popup").style.display = "none"; // Oculta el popup
}
</script>

</body>
</html>
