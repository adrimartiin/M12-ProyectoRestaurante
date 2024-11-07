// Abre el modal cuando se selecciona una mesa libre
function openPopup(idMesa) {
    document.getElementById('popup').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('mesa_id').value = idMesa;
}

// Cierra el modal
function closePopup() {
    document.getElementById('popup').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}
