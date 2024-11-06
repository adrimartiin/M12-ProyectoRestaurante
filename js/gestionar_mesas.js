function abrirFormulario(id_mesa, num_sillas) {
    document.getElementById('id_mesa').value = id_mesa;
    document.getElementById('form-popup').style.display = 'block';
}

function cerrarFormulario() {
    document.getElementById('form-popup').style.display = 'none';
}

function desocuparMesa(id_mesa) {
    // Se puede hacer una llamada AJAX o redirigir a un script PHP que maneje la desocupación
} 