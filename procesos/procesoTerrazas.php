<?php 
session_start(); 

if (isset($_POST['terrazas'])) {
    $terrazaSeleccionada = $_POST['terrazas'];

    switch ($terrazaSeleccionada) {
        case 'terrazaPrincipal':
            $_SESSION['nombre_sala'] = 'Terraza Principal'; 
            header('Location: ../ubicaciones/terrazas.php');
            exit();

        case 'terrazaSecun':
            $_SESSION['nombre_sala'] = 'Terraza Secundaria';
            header('Location: ../ubicaciones/terrazas.php');
            exit();

        case 'terrazaExt':
            $_SESSION['nombre_sala'] = 'Terraza Terciaria';
            header('Location: ../ubicaciones/terrazas.php');
            exit();

        default:
            echo "Terraza seleccionada no válida.";
            break;
    }
} else { 
    echo "No se ha seleccionado ninguna terraza.";
}