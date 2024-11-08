<?php
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sala'])) {
    $sala = $_POST['sala'];

    try {
        $query = "SELECT id_sala FROM tbl_sala WHERE nombre_sala = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $sala);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $id_sala = $row['id_sala'];

            $queryMesas = "SELECT * FROM tbl_mesa WHERE id_sala = ?";
            $stmtMesas = mysqli_prepare($conn, $queryMesas);
            mysqli_stmt_bind_param($stmtMesas, "i", $id_sala);
            mysqli_stmt_execute($stmtMesas);
            $resultMesas = mysqli_stmt_get_result($stmtMesas);
            $mesas = [];

            while ($mesa = mysqli_fetch_assoc($resultMesas)) {
                $mesas[] = $mesa;
            }

            mysqli_stmt_close($stmtMesas);
        } else {
            echo "No se ha encontrado ninguna sala con el nombre especificado.";
        }
        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Salas</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <?php if (!empty($mesas)): ?>
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
                <input type="text" name="nombre" required>
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
