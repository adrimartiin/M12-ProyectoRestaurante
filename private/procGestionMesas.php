<?php
include_once '../db/conexion.php';
    if ($_SERVER['REQUEST_METHOD'] == 'POST'&& isset($_POST['ocupar'])){ 
        $mesa_id = $_POST['ocupar'];
        try {
            mysqli_autocommit($conn, false);
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
            $queryReserva = "INSERT INTO tbl_ocupacion (id_mesa, fecha_hora_ocupacion) VALUES (?, NOW())";
            $stmtReserva = mysqli_prepare($conn, $queryReserva);
            mysqli_stmt_bind_param($stmtReserva, "i",$mesa_id);
                if (mysqli_stmt_execute($stmtReserva)) {
                    $updateQuery = "UPDATE tbl_mesa SET estado_mesa = 'ocupada' WHERE id_mesa = ?";
                    $stmtUpdate = mysqli_prepare($conn, $updateQuery);
                    mysqli_stmt_bind_param($stmtUpdate, "i", $mesa_id);
                    mysqli_stmt_execute($stmtUpdate);
                    $success = "Reserva realizada con éxito.";
                } else {
                    $error = "Hubo un error al hacer la reserva.";
                }
                mysqli_commit($conn);
                mysqli_stmt_close($stmtReserva);
                mysqli_stmt_close($stmtUpdate);
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = $e->getMessage();
            echo "ERROR: " . $error;
        }
        header("Location: ../public/gestion_mesas.php");
        
        }
        
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['desocupar'])) {
        $mesa_id = $_POST['desocupar'];
        $updateQuery = "UPDATE tbl_mesa SET estado_mesa = 'libre' WHERE id_mesa = ?";
        $stmtUpdate = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmtUpdate, "i", $mesa_id);
        if (mysqli_stmt_execute($stmtUpdate)) {
            $success = "Mesa desocupada con éxito.";
        } else {
            $error = "Hubo un error al desocupar la mesa.";
        }
        mysqli_stmt_close($stmtUpdate);
        header("Location: ../public/gestion_mesas.php");
        
    }