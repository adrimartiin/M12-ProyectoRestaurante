document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.select-button');

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const action = button.name === 'desocupar' ? 'desocupar' : 'ocupar';
            const title = action === 'ocupar' ? '¿Seguro quieres ocupar esta mesa?' : '¿Seguro quieres desocupar esta mesa?';
            const confirmButtonText = action === 'ocupar' ? 'Sí, ocupar' : 'Sí, desocupar';
            const successTitle = action === 'ocupar' ? 'Mesa ocupada' : 'Mesa desocupada';
            const successMessage = action === 'ocupar' ? 'La mesa ha sido ocupada con éxito.' : 'La mesa ha sido desocupada con éxito.';

            Swal.fire({
                title: title,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: successTitle,
                        icon: 'success',
                        text: successMessage,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        if (action === 'desocupar') {
                            button.closest('form').submit();
                        } else {
                            button.closest('form').submit();
                        }
                    });
                }
            });
        });
    });
});