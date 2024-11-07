<?php
session_start();
include '../db/conexion.php';
include '../assets/mesas.php';

// Comprobar si el valor de 'sala' está en $_GET o $_POST
if (isset($_POST['sala'])) {
    $_SESSION['sala'] = $_POST['sala'];
}

$sala = $_SESSION['sala'];

// Función para obtener mesas según la sala
function obtenerMesasPorSala($conn, $id_sala) {
    $sql = "SELECT * FROM tbl_mesa WHERE id_sala = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_sala);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Obtener mesas de la sala seleccionada
$mesas = [];
if ($sala) {
    switch ($sala) {
        case 'terraza1':
            $mesas = obtenerMesasPorSala($conn, 1);
            break;
        case 'terraza2':
            $mesas = obtenerMesasPorSala($conn, 2);
            break;
        case 'terraza3':
            $mesas = obtenerMesasPorSala($conn, 3);
            break;
        case 'comedor1':
            $mesas = obtenerMesasPorSala($conn, 4);
            break;
        case 'comedor2':
            $mesas = obtenerMesasPorSala($conn, 5);
            break;
        case 'privada1':
            $mesas = obtenerMesasPorSala($conn, 6);
            break;
        case 'privada2':
            $mesas = obtenerMesasPorSala($conn, 7);
            break;
        case 'privada3':
            $mesas = obtenerMesasPorSala($conn, 8);
            break;
        case 'privada4':
            $mesas = obtenerMesasPorSala($conn, 9);
            break;
        default:
            $mesas = [];
            break;
    }
}

// Mostrar el formulario si se envía el ID de una mesa para ocupar
if (isset($_POST['id_mesa'])) {
    $id_mesa = $_POST['id_mesa'];
    $num_sillas = $_POST['num_sillas'];
    $mostrarFormulario = true;
} else {
    $mostrarFormulario = false;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Mesas</title>
    <link rel="stylesheet" href="../css/gestionar_mesas.css">
</head>
<body>
    <h1>Gestión de Mesas</h1>
    <div class="mesas-container">
        <?php 
        // Si hay mesas, mostrarlas
        if (!empty($mesas)): 
            foreach ($mesas as $mesa): ?>
                <form action="gestionar_mesas.php" method="POST" style="display: inline;">
                    <input type="hidden" name="id_mesa" value="<?= $mesa['id_mesa'] ?>">
                    <input type="hidden" name="num_sillas" value="<?= $mesa['num_sillas_mesa'] ?>">
                    <input type="hidden" name="sala" value="<?= htmlspecialchars($sala) ?>">
                    <div class="mesa" style="background-color: <?= $mesa['estado_mesa'] == 'libre' ? 'green' : 'red' ?>;">
                        <h2>Mesa <?= $mesa['id_mesa'] ?></h2>
                        <p>Sillas: <?= $mesa['num_sillas_mesa'] ?></p>
                        <?php if ($mesa['estado_mesa'] == 'libre'): ?>
                            <button type="submit">Ocupar</button>
                        <?php else: ?>
                            <button type="submit">Desocupar</button>
                        <?php endif; ?>
                    </div>
                </form>
            <?php endforeach; 
        else: 
            echo "<p>No se encontraron mesas disponibles.</p>";
        endif; 
        ?>
    </div>

    <?php if ($mostrarFormulario): ?>
        <div id="form-popup" class="form-popup">
            <form action="guardar_reserva.php" method="POST">
                <h2>Ocupar Mesa</h2>
                <input type="hidden" name="id_mesa" value="<?= htmlspecialchars($id_mesa) ?>">
                <input type="hidden" name="sala" value="<?= htmlspecialchars($sala) ?>">
                <label for="nombre_cliente">Nombre:</label>
                <input type="text" name="nombre_cliente" required>
                <label for="num_personas">Número de Personas:</label>
                <input type="number" name="num_personas" value="<?= htmlspecialchars($num_sillas) ?>" required>
                <button type="submit">Ocupar Mesa</button>
            </form>
        </div>
    <?php endif; ?>
</body>
</html>
