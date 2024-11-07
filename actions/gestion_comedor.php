<?php
include_once '../db/conexion.php';

$mesasDisponibles = [];
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['comedor_interior']) || isset($_POST['comedor_exterior']))) {
    // Guardamos el comedor seleccionado en la sesión
    $comedorSeleccionado = isset($_POST['comedor_interior']) ? 'comedor_interior' : 'comedor_exterior';
    $_SESSION['comedorSeleccionado'] = $comedorSeleccionado;
    $idSala = $comedorSeleccionado === 'comedor_interior' ? 4 : 5;

    $query = "
    SELECT m.id_mesa, m.num_sillas_mesa, m.estado_mesa, c.nombre AS cliente_nombre, c.num_personas
    FROM tbl_mesa m
    LEFT JOIN tbl_ocupacion o ON m.id_mesa = o.id_mesa AND m.estado_mesa = 'ocupada'
    LEFT JOIN tbl_cliente c ON o.id_cliente = c.id_cliente
    WHERE m.id_sala = $idSala
    ";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $mesasDisponibles[] = $row;
        }
    }
}

if (empty($comedorSeleccionado) && isset($_SESSION['comedorSeleccionado'])) {
    $comedorSeleccionado = $_SESSION['comedorSeleccionado'];
    $idSala = $comedorSeleccionado === 'comedor_interior' ? 4 : 5;

    $query = "
    SELECT m.id_mesa, m.num_sillas_mesa, m.estado_mesa, c.nombre AS cliente_nombre, c.num_personas
    FROM tbl_mesa m
    LEFT JOIN tbl_ocupacion o ON m.id_mesa = o.id_mesa AND m.estado_mesa = 'ocupada'
    LEFT JOIN tbl_cliente c ON o.id_cliente = c.id_cliente
    WHERE m.id_sala = $idSala
    ";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $mesasDisponibles[] = $row;
        }
    }
}

// Manejo de ocupación/desocupación de mesas
if (isset($_POST['accion']) && isset($_POST['id_mesa'])) {
    $idMesa = (int)$_POST['id_mesa'];

    if ($_POST['accion'] === 'ocupar' && isset($_POST['nombre_cliente']) && isset($_POST['num_personas'])) {
        $nombreCliente = mysqli_real_escape_string($conn, $_POST['nombre_cliente']);
        $numPersonas = (int)$_POST['num_personas'];

        // Verificamos si el número de personas es mayor que el número de sillas
        $queryMesa = "SELECT num_sillas_mesa FROM tbl_mesa WHERE id_mesa = $idMesa";
        $resultMesa = mysqli_query($conn, $queryMesa);
        $mesa = mysqli_fetch_assoc($resultMesa);

        if ($mesa['num_sillas_mesa'] < $numPersonas) {
            $error = "No hay suficientes sillas para esta cantidad de personas.";
        } else {
            // Insertar cliente
            $insertClienteQuery = "INSERT INTO tbl_cliente (nombre, num_personas) VALUES ('$nombreCliente', $numPersonas)";
            mysqli_query($conn, $insertClienteQuery);
            $idCliente = mysqli_insert_id($conn);

            // Actualizar estado de la mesa y registrar ocupación
            $updateMesaQuery = "UPDATE tbl_mesa SET estado_mesa = 'ocupada' WHERE id_mesa = $idMesa";
            mysqli_query($conn, $updateMesaQuery);

            $insertOcupacionQuery = "INSERT INTO tbl_ocupacion (id_mesa, id_cliente) VALUES ($idMesa, $idCliente)";
            mysqli_query($conn, $insertOcupacionQuery);

            header("Location: ../public/mesas_comedor.php");
            exit();
        }
    } elseif ($_POST['accion'] === 'desocupar') {
        $updateMesaQuery = "UPDATE tbl_mesa SET estado_mesa = 'libre' WHERE id_mesa = $idMesa";
        mysqli_query($conn, $updateMesaQuery);

        $deleteOcupacionQuery = "DELETE FROM tbl_ocupacion WHERE id_mesa = $idMesa";
        mysqli_query($conn, $deleteOcupacionQuery);

        header("Location: ../public/mesas_comedor.php");
        exit();
    }
}
