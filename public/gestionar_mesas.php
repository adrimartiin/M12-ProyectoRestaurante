<?php
session_start();
include '../db/conexion.php';
include '../assets/mesas.php';



// Comprobar si el valor de 'sala' está en $_GET o $_POST (dando prioridad a $_GET después de enviar el formulario)
if (isset($_POST['sala'])) {
    $_SESSION['sala'] = $_POST['sala'];
}


$sala=$_SESSION['sala'];

echo $_SESSION['sala'];
// Función para obtener mesas según la sala
function obtenerMesasPorSala($conn, $id_sala) {
    $sql = "SELECT * FROM tbl_mesa WHERE id_sala = ?"; // Consulta de mesas por sala
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_sala);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Verificar qué sala se ha seleccionado
$mesas = 0; // Definir $mesas de forma predeterminada
if ($sala) {
    switch ($sala) {
        case 'terraza1':
            $mesas = obtenerMesasPorSala($conn, 1); // ID de la terraza 1
            break;
        case 'terraza2':
            $mesas = obtenerMesasPorSala($conn, 2); // ID de la terraza 2
            break;
        case 'terraza3':
            $mesas = obtenerMesasPorSala($conn, 3); // ID de la terraza 3
            break;
        case 'comedor1':
            $mesas = obtenerMesasPorSala($conn, 4); // ID del comedor 1
            break;
        case 'comedor2':
            $mesas = obtenerMesasPorSala($conn, 5); // ID del comedor 2
            break;
        case 'privada1':
            $mesas = obtenerMesasPorSala($conn, 6); // ID de la sala privada 1
            break;
        case 'privada2':
            $mesas = obtenerMesasPorSala($conn, 7); // ID de la sala privada 2
            break;
        case 'privada3':
            $mesas = obtenerMesasPorSala($conn, 8); // ID de la sala privada 3
            break;
        case 'privada4':
            $mesas = obtenerMesasPorSala($conn, 9); // ID de la sala privada 4
            break;
        default:
            $mesas = []; // Si no se selecciona ninguna sala
            break;
    }
}

// Si no se encuentran mesas, mostrar un mensaje de advertencia
if (empty($mesas)) {
    echo "<p>No se encontraron mesas para la sala seleccionada.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Mesas</title>
    <link rel="stylesheet" href="../css/gestionar_mesas.css">
    <script src="../js/gestionar_mesas.js"></script>
</head>
<body>
    <h1>Gestión de Mesas</h1>
    <div class="mesas-container">
        <?php 
        // Si hay mesas, mostrarlas
        if (!empty($mesas)): 
            foreach ($mesas as $mesa): ?>
                <div class="mesa" style="background-color: <?= $mesa['estado_mesa'] == 'libre' ? 'green' : 'red' ?>;">
                    <h2>Mesa <?= $mesa['id_mesa'] ?></h2>
                    <p>Sillas: <?= $mesa['num_sillas_mesa'] ?></p>
                    <?php if ($mesa['estado_mesa'] == 'libre'): ?>
                        <button onclick="abrirFormulario(<?= $mesa['id_mesa'] ?>, <?= $mesa['num_sillas_mesa'] ?>, '<?= $sala ?>')">Ocupar</button>
                    <?php else: ?>
                        <button onclick="desocuparMesa(<?= $mesa['id_mesa'] ?>)">Desocupar</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; 
        else: 
            echo "<p>No se encontraron mesas disponibles.</p>";
        endif; 
        ?>
    </div>

    <div id="form-popup" class="form-popup">
        <form id="form-ocupacion" action="gestionar_mesas.php" method="POST">
            <h2>Ocupar Mesa</h2>
            <input type="hidden" name="id_mesa" id="id_mesa">
            <input type="hidden" name="sala" value="<?= htmlspecialchars($sala) ?>"> <!-- Campo oculto para la sala seleccionada -->
            <label for="nombre_cliente">Nombre:</label>
            <input type="text" name="nombre_cliente" required>
            <label for="num_personas">Número de Personas:</label>
            <input type="number" name="num_personas" id="num_personas" required>
            <button type="submit">Ocupar Mesa</button>
            <button type="button" onclick="cerrarFormulario()">Cerrar</button>
        </form>
    </div>

    <script>
        function abrirFormulario(idMesa, numSillas, sala) {
            document.getElementById('id_mesa').value = idMesa;
            document.getElementById('num_personas').value = numSillas;
            document.getElementById('form-popup').style.display = 'block';
        }

        function cerrarFormulario() {
            document.getElementById('form-popup').style.display = 'none';
        }
    </script>
</body>
</html>
