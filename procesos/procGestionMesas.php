<?php
include_once '../db/conexion.php';
// ====== PROCESO DE MANEJO PARA LA OCUPACIÓN DE LAS MESAS SEGÚN LA RESERVA ========
    if ($_SERVER['REQUEST_METHOD'] == 'POST'&& isset($_POST['ocupar'])){ 
        $mesa_id = $_POST['ocupar'];
        try {
            // Iniciar el autocommit a false
            mysqli_autocommit($conn, false);
            // Iniciar la transacción 
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
            // Inserta la reserva y actualiza el estado de la mesa
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
                // Se hace el commit y por lo tanto se confirman las dos consultas
                mysqli_commit($conn);
                // Se cierra la conexión
                mysqli_stmt_close($stmtReserva);
                mysqli_stmt_close($stmtUpdate);
        } catch (Exception $e) {
            // rollback con circunstancias de la transaccion
            mysqli_rollback($conn);
            $error = $e->getMessage();
            echo "ERROR: " . $error;
        }
        header("Location: ../public/gestion_mesas.php");
        
        }
        
    
    // ===== QUERY PARA EL MANEJO DE DESOCUPACIÓN DE UNA MESA ===== 
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['desocupar'])) {
        $mesa_id = $_POST['desocupar'];
        // Una vez se desocupa la mesa y actualiza el estado de la mesa a libre
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