<?php
session_start();
include '../db/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo_empleado = trim($_POST['codigo_empleado']);
    $pwd = trim($_POST['pwd']);

    if (empty($codigo_empleado) || empty($pwd)) {
        $_SESSION['error'] = "Ambos campos son obligatorios.";
        header("Location: ../public/login.php");
        exit();
    }

    $codigo_empleado = mysqli_real_escape_string($conn, $codigo_empleado);
    $sql = "SELECT * FROM tbl_camarero WHERE codigo_camarero = '$codigo_empleado'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $usuario = mysqli_fetch_assoc($result);
            // Usamos password_verify para verificar la contraseña
            if (password_verify($pwd, $usuario['password_camarero'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['usuario_id'] = $usuario['id_camarero'];
                $_SESSION['nombre_usuario'] = $usuario['nombre_camarero'];
                header("Location: ../public/dashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "Los datos introducidos son incorrectos.";
                header("Location: ../public/login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Los datos introducidos son incorrectos.";
            header("Location: ../public/login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Error en la consulta: " . mysqli_error($conn);
        header("Location: ../public/login.php");
        exit();
    }
} else {
    header("Location: ../public/login.php");
    exit();
}
?>

