function checkFormValidity() {
    const codigoEmpleado = document.getElementById("codigo_empleado");
    const password = document.getElementById("pwd");
    const submitBtn = document.getElementById("submitBtn");
    
    if (codigoEmpleado.value && password.value) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

function validar_codigo() {
    const codigoEmpleado = document.getElementById("codigo_empleado");
    const codigoError = document.getElementById("codigo_empleado_error");

    if (!codigoEmpleado.value) {
        codigoError.textContent = "El código de empleado es obligatorio.";
        codigoError.style.color = "red";
        codigoError.style.fontSize = "0.9em";
        codigoEmpleado.style.border = "1px solid red";
        checkFormValidity();
        return false;
    } else {
        codigoError.textContent = "";
        codigoEmpleado.style.border = "";
        checkFormValidity();
        return true;
    }
}

function validar_password() {
    const password = document.getElementById("pwd");
    const pwdError = document.getElementById("pwd_error");

    if (!password.value) {
        pwdError.textContent = "La contraseña es obligatoria.";
        pwdError.style.color = "red";
        pwdError.style.fontSize = "0.9em";
        password.style.border = "1px solid red";
        checkFormValidity();
        return false;
    } else {
        pwdError.textContent = "";
        password.style.border = "";
        checkFormValidity();
        return true;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const codigoEmpleado = document.getElementById("codigo_empleado");
    const password = document.getElementById("pwd");
    
    codigoEmpleado.addEventListener('input', checkFormValidity);
    password.addEventListener('input', checkFormValidity);
});

