function confirmDesocupar(idMesa) {
    Swal.fire({
        title: '¿Seguro que quieres desocupar esta mesa?',
        text: "¡Esta acción no se puede deshacer!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, desocupar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '../actions/gestion_comedor.php';
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'accion';
            input.value = 'desocupar';
            form.appendChild(input);
            let inputMesa = document.createElement('input');
            inputMesa.type = 'hidden';
            inputMesa.name = 'id_mesa';
            inputMesa.value = idMesa;
            form.appendChild(inputMesa);
            document.body.appendChild(form);
            form.submit();
        }
    });
}