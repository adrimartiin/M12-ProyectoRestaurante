<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

$comedor = '';
$mesas = [];

include_once '../actions/gestion_salas.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar mesa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/mesas_comedor.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

<?php if ($comedor): ?>
    <div class="slider-container">
        <button id="prevArrow" class="arrow-btn">&lt;</button>
        <form method="POST" action="">
            <div class="slider" id="mesaSlider">
                <?php 
                    $imagenesSillas = [
                        2 => "../src/mesa-2.png",
                        3 => "../src/mesa-3.png",
                        4 => "../src/mesa-4.png",
                        5 => "../src/mesa-5.png",
                        6 => "../src/mesa-6.png",
                        10 => "../src/mesa-10.png"
                    ];
                ?>
                <?php foreach ($mesas as $mesa): ?>
                    <div class="option <?php echo $mesa['estado_mesa'] == 'libre' ? 'libre' : 'ocupada'; ?>">
                        <input type="radio" name="mesa" value="<?php echo $mesa['id_mesa']; ?>" id="mesa_<?php echo $mesa['id_mesa']; ?>" <?php echo $mesa['estado_mesa'] == 'ocupada' ? 'disabled' : ''; ?>>
                        <label for="mesa_<?php echo $mesa['id_mesa']; ?>">
                            <h2>Mesa <?php echo $mesa['id_mesa']; ?></h2>
                            <p>Sillas: <?php echo $mesa['num_sillas_mesa']; ?></p>

                            <?php
                                $numSillas = $mesa['num_sillas_mesa'];
                                $imgSrc = isset($imagenesSillas[$numSillas]) ? $imagenesSillas[$numSillas] : ""; 
                            ?>
                            <?php if ($imgSrc): ?>
                                <img src="<?php echo $imgSrc; ?>" alt="Imagen de la mesa" class="mesa-img">
                            <?php else: ?>
                                <img src="../mesas/mesa-default.png" alt="Imagen por defecto" class="mesa-img">
                            <?php endif; ?>
                        </label>
                        <?php if ($mesa['estado_mesa'] == 'ocupada'): ?>
                            <button type="submit" class="select-button" name="desocupar" value="<?php echo $mesa['id_mesa']; ?>">Desocupar</button>
                        <?php else: ?>
                            <button type="button" class="select-button" onclick="openPopup(<?php echo $mesa['id_mesa']; ?>, <?php echo $mesa['num_sillas_mesa']; ?>)">Reservar</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </form>
        <button id="nextArrow" class="arrow-btn">&gt;</button>
    </div>
<?php endif; ?>

<!-- Popup de reserva -->
<div id="popup" class="popup">
    <div class="popup-content">
        <span class="popup-close" onclick="closePopup()">&times;</span>
        <h2>Reserva de Mesa</h2>
        <form method="POST" action="">
            <input type="hidden" name="mesa_id" id="mesa_id">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            <label for="personas">Número de personas:</label>
            <input type="number" name="personas" id="personas" required>
            <button type="submit" name="reserva">Reservar</button>
        </form>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php elseif (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
    </div>
</div>

<script src="../js/slider.js"></script>
<script src="../js/form_modal.js"></script>
</body>
</html>
