<?php
    include_once '../db/conexion.php';
    // pilla por sesion el nombre del usuario logueado 
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: ../index.php");
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sala'])) {
        // Recoge la sala seleccionada por POST 
        $sala = $_POST['sala'];
        try {
            // ====== QUERY PARA OBTENER EL ID DE LA SALA ========
            $query = "SELECT id_sala FROM tbl_sala WHERE nombre_sala = ?"; //se obtiene el id a partir del nombre de la sala (este lo enviamos desde el choose pertinente)
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $sala);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $id_sala = $row['id_sala'];
                
                // ====== QUERY PARA OBTENER MESAS DE LA SALA SELECCIONADA ========
                $queryMesas = "SELECT * FROM tbl_mesa WHERE id_sala = ?";
                $stmtMesas = mysqli_prepare($conn, $queryMesas);
                mysqli_stmt_bind_param($stmtMesas, "i", $id_sala);
                mysqli_stmt_execute($stmtMesas);
                $resultMesas = mysqli_stmt_get_result($stmtMesas);
                $mesas = [];
                while ($mesa = mysqli_fetch_assoc($resultMesas)) {
                    $mesas[] = $mesa;
                }
                mysqli_stmt_close($stmtMesas);
            } else {
                echo "No se ha encontrado ninguna sala con el nombre especificado.";
            }
            mysqli_stmt_close($stmt);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    // // ====== PROCESO DE MANEJO PARA LA OCUPACIÓN DE LAS MESAS SEGÚN LA RESERVA ========
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reserva'])) {
        $usuario_id = $_SESSION['usuario'];
        $nombre = $_POST['nombre'];
        $personas = $_POST['personas'];
        $mesa_id = $_POST['mesa_id'];
        try {
            // Iniciar el autocommit a false
            mysqli_autocommit($conn, false);
            // Iniciar la transacción 
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
            // Obtén el número de sillas de la mesa asociada
            $query = "SELECT num_sillas_mesa FROM tbl_mesa WHERE id_mesa = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $mesa_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $mesa = mysqli_fetch_assoc($result);
            // Si hy más personas que sillas se le avisa al usuario con mensaje de error
            if ($personas > $mesa['num_sillas_mesa']) {
                $error = "El número de personas no puede ser mayor al número de sillas disponibles en la mesa.";
            } else {
                // Inserta la reserva y actualiza el estado de la mesa
                // ===== INICIAR TRANSACCIÓN + AUTOCOMMITS A FALSE ????? =======
                $queryReserva = "INSERT INTO tbl_ocupacion (id_cliente, id_mesa, fecha_hora_ocupacion) VALUES (?, ?, NOW())";
                $stmtReserva = mysqli_prepare($conn, $queryReserva);
                mysqli_stmt_bind_param($stmtReserva, "ii", $usuario_id, $mesa_id);
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
                mysqli_commit($conexion);
                // Se cierra la conexión
                mysqli_stmt_close($stmtReserva);
                mysqli_stmt_close($stmtUpdate);
            }
        } catch (Exception $e) {
            // rollback con circunstancias de la transaccion
            mysqli_rollback($conn);
            $error = $e->getMessage();
            echo "ERROR: " . $error;
        }
        header("Location: ../public/gestion_mesas.php");
        exit();
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
        exit();
    }