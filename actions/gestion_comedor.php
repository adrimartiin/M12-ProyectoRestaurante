<?php
include_once '../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = '';

    if (isset($_POST['comedor_interior'])) {
        $comedor = 'comedor_interior';
        $query = "SELECT * FROM tbl_mesa WHERE id_sala = 4"; 
    } elseif (isset($_POST['comedor_exterior'])) {
        $comedor = 'comedor_exterior';
        $query = "SELECT * FROM tbl_mesa WHERE id_sala = 5"; 
    }

    if (!empty($query)) {
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $mesas[] = $row;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reserva'])) {
    $usuario_id = $_SESSION['usuario'];
    $nombre = $_POST['nombre'];
    $personas = $_POST['personas'];
    $mesa_id = $_POST['mesa_id'];

    $query = "SELECT num_sillas_mesa FROM tbl_mesa WHERE id_mesa = $mesa_id";
    $result = mysqli_query($conn, $query);
    $mesa = mysqli_fetch_assoc($result);

    if ($personas > $mesa['num_sillas_mesa']) {
        $error = "El número de personas no puede ser mayor al número de sillas disponibles en la mesa.";
    } else {
        $query = "INSERT INTO tbl_ocupacion (id_cliente, id_mesa, fecha_hora_ocupacion) 
                VALUES ('$usuario_id', $mesa_id, NOW())";
        if (mysqli_query($conn, $query)) {
            $updateQuery = "UPDATE tbl_mesa SET estado_mesa = 'ocupada' WHERE id_mesa = $mesa_id";
            mysqli_query($conn, $updateQuery);

            $success = "Reserva realizada con éxito.";
        } else {
            $error = "Hubo un error al hacer la reserva.";
        }
    }

    header("Location: ../public/mesas_comedor.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['desocupar'])) {
    $mesa_id = $_POST['desocupar'];

    $updateQuery = "UPDATE tbl_mesa SET estado_mesa = 'libre' WHERE id_mesa = $mesa_id";
    if (mysqli_query($conn, $updateQuery)) {
        $success = "Mesa desocupada con éxito.";
    } else {
        $error = "Hubo un error al desocupar la mesa.";
    }

    header("Location: ../public/mesas_comedor.php");
    exit();
}
?>
