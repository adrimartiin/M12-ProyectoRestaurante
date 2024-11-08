<?php
// session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['nombre_sala'])) {
    echo "<h3>No se ha seleccionado ninguna sala</h3>";
    exit();
}

$nombre_sala = $_SESSION['nombre_sala'];
$id_sala = '';

// Recuperar el id de la sala seleccionada
$sql = "SELECT id_sala FROM tbl_sala WHERE nombre_sala = ?";
$stmt1 = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt1, "s", $nombre_sala);
mysqli_stmt_execute($stmt1);
$result = mysqli_stmt_get_result($stmt1);
$sala = mysqli_fetch_assoc($result);
$id_sala = $sala['id_sala'];
mysqli_stmt_close($stmt1);

// Si se ha enviado el formulario para ocupar una mesa
if (isset($_POST['ocupar_mesa'])) {
    $id_mesa = $_POST['id_mesa'];
    $nombre_cliente = $_POST['nombre_cliente'];
    $num_personas = $_POST['num_personas'];
    // Insertar cliente en tbl_cliente
    $sqlCliente = "INSERT INTO tbl_cliente (nombre, num_personas) VALUES (?, ?)";
    $stmtCliente = mysqli_prepare($conn, $sqlCliente);
    mysqli_stmt_bind_param($stmtCliente, "si", $nombre_cliente, $num_personas);
    mysqli_stmt_execute($stmtCliente);
    $id_cliente = mysqli_insert_id($conn);
    mysqli_stmt_close($stmtCliente);

    // Actualizar estado de la mesa y registrar ocupación
    $updateMesa = "UPDATE tbl_mesa SET estado_mesa = 'ocupada' WHERE id_mesa = ?";
    $stmtUpdate = mysqli_prepare($conn, $updateMesa);
    mysqli_stmt_bind_param($stmtUpdate, "i", $id_mesa);
    mysqli_stmt_execute($stmtUpdate);
    mysqli_stmt_close($stmtUpdate);

    $sqlOcupacion = "INSERT INTO tbl_ocupacion (id_mesa, id_cliente, fecha_hora_ocupacion) VALUES (?, ?, NOW())";
    $stmtOcupacion = mysqli_prepare($conn, $sqlOcupacion);
    mysqli_stmt_bind_param($stmtOcupacion, "ii", $id_mesa, $id_cliente);
    mysqli_stmt_execute($stmtOcupacion);
    mysqli_stmt_close($stmtOcupacion);
}

// Si se ha enviado el formulario para desocupar una mesa
if (isset($_POST['desocupar'])) {
    $id_mesa = $_POST['id_mesa'];

    // Obtener el id del cliente asociado a la mesa
    $sqlGetCliente = "SELECT id_cliente FROM tbl_ocupacion WHERE id_mesa = ? ORDER BY fecha_hora_ocupacion DESC LIMIT 1";
    $stmtGetCliente = mysqli_prepare($conn, $sqlGetCliente);
    mysqli_stmt_bind_param($stmtGetCliente, "i", $id_mesa);
    mysqli_stmt_execute($stmtGetCliente);
    $resultCliente = mysqli_stmt_get_result($stmtGetCliente);
    $cliente = mysqli_fetch_assoc($resultCliente);
    $id_cliente = $cliente['id_cliente'];
    mysqli_stmt_close($stmtGetCliente);

    // Eliminar el registro de ocupación y del cliente
    $sqlDeleteOcupacion = "DELETE FROM tbl_ocupacion WHERE id_mesa = ? AND id_cliente = ?";
    $stmtDeleteOcupacion = mysqli_prepare($conn, $sqlDeleteOcupacion);
    mysqli_stmt_bind_param($stmtDeleteOcupacion, "ii", $id_mesa, $id_cliente);
    mysqli_stmt_execute($stmtDeleteOcupacion);
    mysqli_stmt_close($stmtDeleteOcupacion);

    $sqlDeleteCliente = "DELETE FROM tbl_cliente WHERE id_cliente = ?";
    $stmtDeleteCliente = mysqli_prepare($conn, $sqlDeleteCliente);
    mysqli_stmt_bind_param($stmtDeleteCliente, "i", $id_cliente);
    mysqli_stmt_execute($stmtDeleteCliente);
    mysqli_stmt_close($stmtDeleteCliente);

    // Actualizar estado de la mesa a "libre"
    $updateMesa = "UPDATE tbl_mesa SET estado_mesa = 'libre' WHERE id_mesa = ?";
    $stmtUpdate = mysqli_prepare($conn, $updateMesa);
    mysqli_stmt_bind_param($stmtUpdate, "i", $id_mesa);
    mysqli_stmt_execute($stmtUpdate);
    mysqli_stmt_close($stmtUpdate);
}

// Obtener las mesas y su estado en la sala seleccionada
$queryMesas = "SELECT id_mesa, num_sillas_mesa, estado_mesa FROM tbl_mesa WHERE id_sala = ?";
$stmt2 = mysqli_prepare($conn, $queryMesas);
mysqli_stmt_bind_param($stmt2, "i", $id_sala);
mysqli_stmt_execute($stmt2);
$mesas = mysqli_stmt_get_result($stmt2);
mysqli_stmt_close($stmt2);

// Obtener el nombre del cliente y número de personas para las mesas ocupadas
$queryOcupacion = "SELECT c.nombre, c.num_personas FROM tbl_ocupacion o
                   JOIN tbl_cliente c ON o.id_cliente = c.id_cliente
                   WHERE o.id_mesa = ? ORDER BY o.fecha_hora_ocupacion DESC LIMIT 1";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lora:wght@400;500&display=swap" rel="stylesheet">
    <title>Gestión de Mesas</title>
    <style>
        /* Estilos CSS */
        * {
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
        }

        .container-dentro {
            width: 90%;
            height: 90%;
            background-color: #ccc;
            padding: 20px;
            overflow-y: auto;
            border-radius: 8px;
        }

        .titulo {
            font-family: 'Playfair Display', serif;
            text-align: center;
            margin-bottom: 20px;
        }

        .card-mesa {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .mesa {
            background-color: rgb(3, 252, 40);
            margin: 10px;
            padding: 15px;
            border-radius: 5px;
            width: calc(33% - 20px); 
            box-sizing: border-box;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .mesa input[type="submit"] {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%; 
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .mesa input[type="submit"]:hover {
            background-color: #555;
        }

        .mesa-ocupada {
            background-color: red;
        }

        .form-popup {
            display: <?= isset($_POST['ocupar']) ? 'block' : 'none' ?>;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 8px;
            width: 300px;
        }

        .info-ocupada {
            font-size: 14px;
            margin-top: 10px;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="container-dentro">
            <h1 class="titulo">Gestionar Mesa de la Sala: <?php echo htmlspecialchars($nombre_sala); ?></h1>
            <div class="card-mesa">
                <?php while ($mesa = mysqli_fetch_assoc($mesas)): ?>
                    <div class="mesa <?= $mesa['estado_mesa'] == 'ocupada' ? 'mesa-ocupada' : '' ?>" style="background-color: <?= $mesa['estado_mesa'] == 'libre' ? 'green' : 'red' ?>;">
                        <h2>Mesa: <?= htmlspecialchars($mesa['id_mesa']) ?></h2>
                        <p>Número de sillas: <?= htmlspecialchars($mesa['num_sillas_mesa']) ?></p>

                        <?php if ($mesa['estado_mesa'] == 'ocupada'): ?>
                            <?php 
                                $stmtOcupacion = mysqli_prepare($conn, $queryOcupacion);
                                mysqli_stmt_bind_param($stmtOcupacion, "i", $mesa['id_mesa']);
                                mysqli_stmt_execute($stmtOcupacion);
                                $resultOcupacion = mysqli_stmt_get_result($stmtOcupacion);
                                $ocupacion = mysqli_fetch_assoc($resultOcupacion);
                                mysqli_stmt_close($stmtOcupacion);
                            ?>
                            <div class="info-ocupada">
                                <strong>Cliente:</strong> <?= htmlspecialchars($ocupacion['nombre']) ?><br>
                                <strong>Número de personas:</strong> <?= htmlspecialchars($ocupacion['num_personas']) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <input type="hidden" name="id_mesa" value="<?= $mesa['id_mesa'] ?>">
                            <?php if ($mesa['estado_mesa'] == 'libre'): ?>
                                <input type="submit" name="ocupar" value="Ocupar">
                            <?php else: ?>
                                <input type="submit" name="desocupar" value="Desocupar">
                            <?php endif; ?>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php if (isset($_POST['ocupar'])): ?>
                <div class="form-popup">
                    <h2>Ocupar Mesa</h2>
                    <form method="POST">
                        <input type="hidden" name="id_mesa" value="<?= htmlspecialchars($_POST['id_mesa']) ?>">
                        <label for="nombre_cliente">Nombre:</label>
                        <input type="text" name="nombre_cliente" required>
                        <label for="num_personas">Número de Personas:</label>
                        <input type="number" name="num_personas" required>
                        <button type="submit" name="ocupar_mesa">Confirmar</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>


