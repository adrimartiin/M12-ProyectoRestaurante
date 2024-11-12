document.getElementById("filterForm").onsubmit = validaForm;

function validaForm(event) {
    event.preventDefault();

    let camarero = document.getElementById("camarero").value;
    let mesa = document.getElementById("mesa").value;
    let fecha = document.getElementById("fecha").value;

    if (camarero === "" && mesa === "" && fecha === "") {
        let formError = document.getElementById("form_error");
        formError.textContent = "Debe completar al menos un campo.";
        formError.style.display = "block";
        return false;
    }

    let formError = document.getElementById("form_error");
    formError.textContent = "";
    formError.style.display = "none";

    let validCamarero = validaCampo("camarero");
    let validMesa = validaCampo("mesa");
    let validFecha = validaCampo("fecha");

    if (validCamarero || validMesa || validFecha) {
        document.getElementById("filterForm").submit();
    }
}

function validaCampo(campoId) {
    let valor = document.getElementById(campoId).value;
    let input = document.getElementById(campoId);

    if (valor === "") {
        input.classList.add("error-border");
        return false;
    } else {
        input.classList.remove("error-border");
        return true;
    }
}
