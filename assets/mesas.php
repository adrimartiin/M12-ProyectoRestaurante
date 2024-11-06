<?php
include_once '../db/conexion.php';

// Función para ocupar mesa
function ocuparMesa($conn, $id_mesa, $nombre_cliente, $num_personas) {
    // Obtener el id_cliente basado en el nombre (esto supone que ya existe un cliente con este nombre)
    $sql_cliente = "SELECT id_cliente FROM tbl_cliente WHERE nombre = ?";
    $stmt_cliente = mysqli_prepare($conn, $sql_cliente);
    mysqli_stmt_bind_param($stmt_cliente, "s", $nombre_cliente);
    mysqli_stmt_execute($stmt_cliente);
    $result_cliente = mysqli_stmt_get_result($stmt_cliente);
    $cliente = mysqli_fetch_assoc($result_cliente);

    if ($cliente) {
        $id_cliente = $cliente['id_cliente'];
    } else {
        // Si el cliente no existe, crear un nuevo cliente
        $sql_insert_cliente = "INSERT INTO tbl_cliente (nombre, num_personas) VALUES (?, ?)";
        $stmt_insert_cliente = mysqli_prepare($conn, $sql_insert_cliente);
        mysqli_stmt_bind_param($stmt_insert_cliente, "si", $nombre_cliente, $num_personas);
        mysqli_stmt_execute($stmt_insert_cliente);
        $id_cliente = mysqli_insert_id($conn);
    }
    
    // Insertar la ocupación de la mesa
    $sql = "INSERT INTO tbl_ocupacion (id_mesa, id_cliente, fecha_hora_ocupacion) VALUES (?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_mesa, $id_cliente);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirigir después de ocupar la mesa
    header("Location: ../public/gestionar_mesas.php");
    exit();
}

// Función para desocupar mesa
function desocuparMesa($conn, $id_ocupacion) {
    $sql = "UPDATE tbl_ocupacion SET fecha_hora_desocupacion = NOW() WHERE id_ocupacion = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_ocupacion);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirigir después de desocupar la mesa
    header("Location: ../public/gestionar_mesas.php");
    exit();
}

// Comprobar si se está enviando el formulario para ocupar mesa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_mesa'], $_POST['nombre_cliente'], $_POST['num_personas'])) {
    $id_mesa = $_POST['id_mesa'];
    $nombre_cliente = $_POST['nombre_cliente'];
    $num_personas = $_POST['num_personas'];

    // Llamar a la función para ocupar la mesa
    ocuparMesa($conn, $id_mesa, $nombre_cliente, $num_personas);
}

// Comprobar si se está enviando el formulario para desocupar mesa
if (isset($_POST['desocupar_mesa'])) {
    $id_ocupacion = $_POST['id_ocupacion'];

    // Llamar a la función para desocupar la mesa
    desocuparMesa($conn, $id_ocupacion);
}
?>
