document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.select-button');
    
    buttons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            // Obtener el formulario al que pertenece el botón
            const form = button.closest('form');
            const action = button.name === 'desocupar' ? 'desocupar' : 'ocupar';
            const title = action === 'ocupar' ? '¿Seguro quieres ocupar esta mesa?' : '¿Seguro quieres desocupar esta mesa?';
            const confirmButtonText = action === 'ocupar' ? 'Sí, ocupar' : 'Sí, desocupar';

            // Mostrar el SweetAlert
            Swal.fire({
                title: title,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Desactivar el botón para evitar múltiples clics
                    button.disabled = true;
                    // Al confirmar, enviar el formulario
                    form.submit();
                }
            });
        });
    });
});
