<?php
include_once '../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica si se ha enviado la acción para ocupar o desocupar una mesa
    if (isset($_POST['id_mesa']) && isset($_POST['accion'])) {
        $id_mesa = intval($_POST['id_mesa']);
        $accion = $_POST['accion'];

        try {
            // Cambia el estado según la acción recibida
            $nuevoEstado = $accion === 'reservar' ? 'ocupada' : 'libre';
            $queryUpdate = "UPDATE tbl_mesa SET estado_mesa = ? WHERE id_mesa = ?";
            $stmtUpdate = mysqli_prepare($conn, $queryUpdate);
            mysqli_stmt_bind_param($stmtUpdate, "si", $nuevoEstado, $id_mesa);

            if (mysqli_stmt_execute($stmtUpdate)) {
                echo "La mesa ha sido actualizada a estado: " . $nuevoEstado;
            } else {
                echo "Error al actualizar el estado de la mesa.";
            }

            mysqli_stmt_close($stmtUpdate);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } elseif (isset($_POST['sala'])) {
        $sala = $_POST['sala'];

        try {
            $query = "SELECT id_sala FROM tbl_sala WHERE nombre_sala = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $sala);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                $id_sala = $row['id_sala'];

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
}
?>
