<?php
    include_once '../db/conexion.php';
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: ../index.php");
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sala'])) {
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