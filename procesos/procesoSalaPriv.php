<?php

if (isset($_POST['salasPriv'])) {
    $salaPrivSeleccionada = $_POST['salasPriv'];

    switch ($salaPrivSeleccionada) {
        case 'salaPriv1':
            echo "Has seleccionado la sala privada 1.";
            break;
        case 'salaPriv2':
            echo "Has seleccionado la sala privada 2.";
            break;
        case 'salaPriv3':
            echo "Has seleccionado la sala privada 3.";
            break;
        case 'salaPriv4':
            echo "Has seleccionado la sala privada 4.";
            break;


    }
} else {
    echo "Debes seleccionar una sala privada.";
    exit();
}
