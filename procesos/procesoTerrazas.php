<?php
if (isset($_POST['terrazas'])) {
$terrazaSeleccionada = $_POST['terrazas'];

switch ($terrazaSeleccionada){
    case 'terraza1':
        echo "Has seleccionado la terraza 1.";
    break;

    case 'terraza2':
        echo "Has seleccionado la terraza 2.";
    break;

    case 'terraza3':
        echo "Has seleccionado la terraza 3.";
    break;

}
} else { 
    echo "No se ha seleccionado ninguna terraza.";
}