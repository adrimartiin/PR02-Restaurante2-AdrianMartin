document.getElementById("nombre").onblur = validaNombre;
document.getElementById("apellido").onblur = validaApellido;
document.getElementById("code").onblur = validaCode;
document.getElementById("psswd").onblur = validaPsswd;
document.getElementById("formCamareros").onsubmit = validaFormCamareros;

function validaNombre() {
let name = document.getElementById("nombre").value;
let input_name = document.getElementById("nombre");
let error_name = document.getElementById("error-nombre");

if(name === "" || name === null){
    error_name.textContent = "El código de empleado es obligatorio.";
    input_name.classList.add("error-border");
    return false;
} else {
    error_name.textContent = "";
    input_name.classList.remove("error-border");
    return true;
}
}

function validaApellido() {
    let surname = document.getElementById("apellido").value;
    let input_surname = document.getElementById("apellido");
    let error_surname = document.getElementById("error-apellido");

    if(surname === "" || surname === null){
        error_surname.textContent = "El apellido del empleado es obligatorio.";
        input_surname.classList.add("error-border");
        return false;
    } else {
        error_surname.textContent = "";
        input_surname.classList.remove("error-border");
        return true;
    }
}

function validaCode() {
    let code = document.getElementById("code").value;
    let input_code = document.getElementById("code");
    let error_code = document.getElementById("error-code");

    if(code === "" || code === null){
        error_code.textContent = "El código de empleado es obligatorio.";
        input_code.classList.add("error-border");
        return false;
    } else {
        error_code.textContent = "";
        input_code.classList.remove("error-border");
        return true;
    }
}

function validaPsswd() {
    let password = document.getElementById("psswd").value;
    let input_password = document.getElementById("psswd");
    let error_password = document.getElementById("error-psswd");

    if(password === "" || password === null){
        error_password.textContent = "La contraseña es obligatoria.";
        input_password.classList.add("error-border");
        return false;
    } else {
        error_password.textContent = "";
        input_password.classList.remove("error-border");
        return true;
    }
}

function validaFormCamareros(event) {
    event.preventDefault();
    if (validaNombre() && validaApellido() && validaCode() && validaPsswd()) {
        document.getElementById("formCamareros").submit(); 
    }
}

