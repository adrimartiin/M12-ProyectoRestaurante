<?php

if (isset($_POST['comedores'])) {
$comedorSeleccionado = $_POST['comedores'];

switch ($comedorSeleccionado){
    case 'comedor1':
        
    break;

    case 'comedor2':
        echo "Has seleccionado el comedor 2.";
    break;

}
} else { 
    echo "No se ha seleccionado ninguna terraza.";
}