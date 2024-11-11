document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.select-button');
    
    buttons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            console.log('Button clicked'); // Verificar si el botón se ha clickeado

            // Obtener el formulario al que pertenece el botón
            const form = button.closest('form');
            
            const action = button.name === 'desocupar' ? 'desocupar' : 'ocupar';
            const title = action === 'ocupar' ? '¿Seguro quieres ocupar esta mesa?' : '¿Seguro quieres desocupar esta mesa?';
            const confirmButtonText = action === 'ocupar' ? 'Sí, ocupar' : 'Sí, desocupar';
            const successTitle = action === 'ocupar' ? 'Mesa ocupada' : 'Mesa desocupada';
            const successMessage = action === 'ocupar' ? 'La mesa ha sido ocupada con éxito.' : 'La mesa ha sido desocupada con éxito.';

            // Mostrar el SweetAlert
            Swal.fire({
                title: title,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Al confirmar, enviar el formulario
                    form.submit(); // Envía el formulario directamente
                }
            });
        });
    });
});
