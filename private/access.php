<?php
session_start();
include '../db/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoge los datos ingresados y elimina espacios en blanco al inicio y final
    $codigo_empleado = trim($_POST['codigo_empleado']);
    $pwd = trim($_POST['pwd']);

    // Guarda los valores ingresados en variables de sesión para mostrarlos en caso de error
    $_SESSION['codigo_empleado'] = $codigo_empleado;
    $_SESSION['pwd'] = $pwd;

    // Verificación de campos vacíos
    if (empty($codigo_empleado) || empty($pwd)) {
        $_SESSION['error'] = "Ambos campos son obligatorios.";
        header("Location: ../index.php");
        exit();
    }

    // Preparar la consulta para evitar inyecciones SQL
    $sql = "SELECT * FROM tbl_camarero WHERE codigo_camarero = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        // Enlazar el parámetro y ejecutarlo
        mysqli_stmt_bind_param($stmt, "s", $codigo_empleado);
        mysqli_stmt_execute($stmt);

        // Obtener el resultado de la consulta
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $usuario = mysqli_fetch_assoc($result);

            // Verificación de la contraseña con password_verify
            if (password_verify($pwd, $usuario['password_camarero'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['usuario_id'] = $usuario['id_camarero'];
                $_SESSION['nombre_usuario'] = $usuario['nombre_camarero'];

                // Limpia las variables de sesión de los datos ingresados al iniciar sesión correctamente
                unset($_SESSION['codigo_empleado']);
                unset($_SESSION['pwd']);
                unset($_SESSION['error']);

                header("Location: ../public/dashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "Los datos introducidos son incorrectos.";
                header("Location: ../public/login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Los datos introducidos son incorrectos.";
            header("Location: ../index.php");
            exit();
        }

        // Cerrar la declaración
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "Error en la consulta: " . mysqli_error($conn);
        header("Location: ../index.php");
        exit();
    }
} else {
    header("Location: ../public/login.php");
    exit();
}
?>
