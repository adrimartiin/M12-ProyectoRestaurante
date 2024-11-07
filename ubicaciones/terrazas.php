<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['nombre_sala'])) {
    echo "<h3>No se ha seleccionado ninguna sala</h3>";
    exit();
}

// Recuperar el nombre de la sala de la sesión y obtener el id de esa sala
$nombre_sala = $_SESSION['nombre_sala'];
$id_sala = '';

$sql = "SELECT id_sala FROM tbl_sala WHERE nombre_sala = ?";
$stmt1 = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt1, "s", $nombre_sala);
mysqli_stmt_execute($stmt1);
$result = mysqli_stmt_get_result($stmt1);
$sala = mysqli_fetch_assoc($result);
$id_sala = $sala['id_sala'];
mysqli_stmt_close($stmt1);

// Procesar ocupación o desocupación de la mesa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_mesa = $_POST['id_mesa'];

    if (isset($_POST['ocupar'])) { // Ocupar mesa
        $nombre_cliente = $_POST['nombre_cliente'];
        $num_personas = $_POST['num_personas'];

        // Insertar el cliente y cambiar el estado de la mesa a ocupada
        $sql_insert_cliente = "INSERT INTO tbl_cliente (nombre, num_personas) VALUES (?, ?)";
        $stmt_cliente = mysqli_prepare($conn, $sql_insert_cliente);
        mysqli_stmt_bind_param($stmt_cliente, "si", $nombre_cliente, $num_personas);
        mysqli_stmt_execute($stmt_cliente);
        $id_cliente = mysqli_insert_id($conn); // Obtener el ID del cliente recién creado
        mysqli_stmt_close($stmt_cliente);

        // Actualizar el estado de la mesa a ocupada
        $sql_update_mesa = "UPDATE tbl_mesa SET estado_mesa = 'ocupada' WHERE id_mesa = ?";
        $stmt_mesa = mysqli_prepare($conn, $sql_update_mesa);
        mysqli_stmt_bind_param($stmt_mesa, "i", $id_mesa);
        mysqli_stmt_execute($stmt_mesa);
        mysqli_stmt_close($stmt_mesa);
        
    } elseif (isset($_POST['desocupar'])) { // Desocupar mesa
        // Cambiar el estado de la mesa a libre
        $sql_update_mesa = "UPDATE tbl_mesa SET estado_mesa = 'libre' WHERE id_mesa = ?";
        $stmt_mesa = mysqli_prepare($conn, $sql_update_mesa);
        mysqli_stmt_bind_param($stmt_mesa, "i", $id_mesa);
        mysqli_stmt_execute($stmt_mesa);
        mysqli_stmt_close($stmt_mesa);
    }
}
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Mesas</title>
    <link rel="stylesheet" href="../css/terrazas.css">
    <script>
        function openPopup(idMesa) {
            document.getElementById('popup-form').style.display = 'flex';
            document.getElementById('id_mesa_input').value = idMesa;
        }
        function closePopup() {
            document.getElementById('popup-form').style.display = 'none';
        }
    </script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

<div class="container">
    <div class="container-dentro">
        <h1 class="titulo"><?php echo htmlspecialchars($_SESSION['nombre_sala']); ?></h1>
        <div class="card-mesa">
            <?php
            $queryMesas = "SELECT id_mesa, num_sillas_mesa, estado_mesa FROM tbl_mesa WHERE id_sala = ?";
            $stmt2 = mysqli_prepare($conn, $queryMesas);
            mysqli_stmt_bind_param($stmt2, "i", $id_sala);
            mysqli_stmt_execute($stmt2);
            $result = mysqli_stmt_get_result($stmt2);

            while ($mesa = mysqli_fetch_assoc($result)) {
                $estado = $mesa['estado_mesa'] == 'ocupada' ? 'ocupada' : '';
                $botonTexto = $mesa['estado_mesa'] == 'ocupada' ? 'Desocupar' : 'Ocupar';
                $botonAccion = $mesa['estado_mesa'] == 'ocupada' ? 'desocupar' : 'ocupar';

                echo "<div class='mesa $estado'>";
                echo "<h2>Mesa: " . htmlspecialchars($mesa['id_mesa']) . "</h2>";
                echo "<p>Sillas: " . htmlspecialchars($mesa['num_sillas_mesa']) . "</p>";
                
                // Formulario de acción Ocupar/Desocupar
                echo "<form method='POST' action=''>";
                echo "<input type='hidden' name='id_mesa' value='" . htmlspecialchars($mesa['id_mesa']) . "'>";
                
                if ($mesa['estado_mesa'] == 'ocupada') {
                    echo "<button type='submit' name='desocupar' style='width: 100%;'>Desocupar</button>";
                } else {
                    echo "<button type='button' onclick='openPopup(" . $mesa['id_mesa'] . ")' style='width: 100%;'>Ocupar</button>";
                }
                echo "</form>";
                echo "</div>";
            }
            mysqli_stmt_close($stmt2);
            ?>
        </div>
    </div>
</div>

<!-- Formulario emergente para ocupar mesa -->
<div id="popup-form" class="popup-form">
    <div class="popup-content">
        <h2>Registrar Cliente</h2>
        <form method="POST" action="">
            <img src="../img/usuario-form.png" alt="" style='width: 100px;'>
            <label for="nombre_cliente">Nombre del Cliente:</label>
            <input type="text" name="nombre_cliente" id="nombre_cliente" required>
            <label for="num_personas">Número de Personas:</label>
            <input type="number" name="num_personas" id="num_personas" min="1" required>
            <input type="hidden" name="id_mesa" id="id_mesa_input">
            <button type="submit" name="ocupar">Registrar</button>
            <button type="button" class="close-btn" onclick="closePopup()">Cerrar</button>
        </form>
    </div>
</div>

</body>
</html>
