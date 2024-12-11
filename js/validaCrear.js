document.getElementById("nombre_usuario").onblur = validaNombreUser;
document.getElementById("nombre_real_usuario").onblur = validaRealUser;
document.getElementById("password_usuario").onblur = validaPwd;
document.getElementById("id_rol").onblur = validaRole;
document.getElementById("insertForm").onsubmit = validaFormulario;

function validaNombreUser() {
    let nombreUsuario = document.getElementById("nombre_usuario").value;
    let inputNombreUsuario = document.getElementById("nombre_usuario");
    let errorNombreUsuario = document.getElementById("error_nombre_usuario");

    if (nombreUsuario === "") {
        errorNombreUsuario.innerHTML = "El nombre de usuario es obligatorio.";
        inputNombreUsuario.classList.add("error-border");
        return false;
    } else {
        errorNombreUsuario.innerHTML = "";
        inputNombreUsuario.classList.remove("error-border");
        return true;
    }  
}

function validaRealUser() {
    let nombreReal = document.getElementById("nombre_real_usuario").value;
    let inputRealName = document.getElementById("nombre_real_usuario");
    let errorRealName = document.getElementById("error_nombre_real");

    if (nombreReal === "") {
        errorRealName.innerHTML = "El nombre real es obligatorio.";
        inputRealName.classList.add("error-border");
        return false;
    } else {
        errorRealName.innerHTML = "";
        inputRealName.classList.remove("error-border");
        return true;
    }
}

function validaPwd() {
    let password = document.getElementById("password_usuario").value;
    let inputPassword = document.getElementById("password_usuario");
    let errorPassword = document.getElementById("error_pwd");

    if (password === "") {
        errorPassword.innerHTML = "La contraseña es obligatoria.";
        inputPassword.classList.add("error-border");
        return false;
    } else {
        errorPassword.innerHTML = "";
        inputPassword.classList.remove("error-border");
        return true;
    }
}

function validaRole() {
    let rol = document.getElementById("id_rol").value;
    let errorRole = document.getElementById("error_rol");

    if (rol === "" || rol === "0") {
        errorRole.innerHTML = "Debe seleccionar un rol.";
        document.getElementById("id_rol").classList.add("error-border");
        return false;
    } else {
        errorRole.innerHTML = "";
        document.getElementById("id_rol").classList.remove("error-border");
        return true;
    } 
}

function validaFormulario(event) {
    event.preventDefault();
    if(validaNombreUser() && validaRealUser() && validaPwd() && validaRole()){
        document.getElementById("insertForm").submit();
    }
}

