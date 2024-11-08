<?php

include_once '../db/conexion.php';
session_start();
$mesasDisponibles = [];
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['comedor_interior']) || isset($_POST['comedor_exterior']))) {
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

if (isset($_POST['accion']) && isset($_POST['id_mesa'])) {
    $idMesa = (int)$_POST['id_mesa'];

    if ($_POST['accion'] === 'ocupar' && isset($_POST['nombre_cliente']) && isset($_POST['num_personas'])) {
        $nombreCliente = mysqli_real_escape_string($conn, $_POST['nombre_cliente']);
        $numPersonas = (int)$_POST['num_personas'];

        $queryMesa = "SELECT num_sillas_mesa FROM tbl_mesa WHERE id_mesa = $idMesa";
        $resultMesa = mysqli_query($conn, $queryMesa);
        $mesa = mysqli_fetch_assoc($resultMesa);

        if ($mesa['num_sillas_mesa'] < $numPersonas) {
            $error = "No hay suficientes sillas para esta cantidad de personas.";
        } else {
            $insertClienteQuery = "INSERT INTO tbl_cliente (nombre, num_personas) VALUES ('$nombreCliente', $numPersonas)";
            mysqli_query($conn, $insertClienteQuery);
            $idCliente = mysqli_insert_id($conn);

            $updateMesaQuery = "UPDATE tbl_mesa SET estado_mesa = 'ocupada' WHERE id_mesa = $idMesa";
            mysqli_query($conn, $updateMesaQuery);

            $fechaHoraOcupacion = date('Y-m-d H:i:s');

            // Verificar si `id_camarero` existe en la sesión
            if (isset($_SESSION['usuario_id'])) {
                $idCamarero = $_SESSION['usuario_id'];

                $insertOcupacionQuery = "INSERT INTO tbl_ocupacion (id_mesa, id_cliente, id_camarero, fecha_hora_ocupacion) VALUES ($idMesa, $idCliente, $idCamarero, '$fechaHoraOcupacion')";
                mysqli_query($conn, $insertOcupacionQuery);

                header("Location: ../public/mesas_comedor.php");
                exit();
            } else {
                $error = "Error: el ID del camarero no está definido. Por favor, inicie sesión.";
            }
        }
    } elseif ($_POST['accion'] === 'desocupar') {
        $fechaHoraDesocupacion = date('Y-m-d H:i:s');
        $updateMesaQuery = "UPDATE tbl_mesa SET estado_mesa = 'libre' WHERE id_mesa = $idMesa";
        mysqli_query($conn, $updateMesaQuery);

        $updateOcupacionQuery = "UPDATE tbl_ocupacion SET fecha_hora_desocupacion = '$fechaHoraDesocupacion' WHERE id_mesa = $idMesa AND fecha_hora_desocupacion IS NULL";
        mysqli_query($conn, $updateOcupacionQuery);

        header("Location: ../public/mesas_comedor.php");
        exit();
    }
}
?>
