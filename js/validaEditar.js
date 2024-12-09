document.getElementById("nombre_usuario").onblur = validaUserName;
document.getElementById("nombre_real_usuario").onblur = validaRealName;
document.getElementById("id_rol").onblur = validaRol;
document.getElementById("edit_form").onsubmit = validaFormulario;

function validaUserName() {
    let userName = document.getElementById("nombre_usuario").value;
    let inputUserName = document.getElementById("nombre_usuario");
    let error_userName = document.getElementById("error_nombre_usuario");

    if (userName === "" || userName === null || userName.length === 0) {
        error_userName.innerHTML = "El nombre de usuario es obligatorio";
        inputUserName.classList.add("error-border");
        return false;
    } else {
        error_userName.innerHTML = "";
        inputUserName.classList.remove("error-border");
        return true;
    }
}

function validaRealName() {
    let realName = document.getElementById("nombre_real_usuario").value;
    let inputRealName = document.getElementById("nombre_real_usuario");
    let error_realName = document.getElementById("error_nombre_real");

    if (realName === "" || realName === null || realName.length === 0) {
        error_realName.innerHTML = "El nombre real es obligatorio";
        inputRealName.classList.add("error-border");
        return false;
    } else {
        error_realName.innerHTML = "";
        inputRealName.classList.remove("error-border");
        return true;
    }
}

function validaRol() {
    let rol = document.getElementById("id_rol").value;
    let error_rol = document.getElementById("error_rol");

    if (rol === "" || rol === null || rol === "0") {
        error_rol.innerHTML = "Debe seleccionar un rol";
        document.getElementById("id_rol").classList.add("error-border");
        return false;
    } else {
        error_rol.innerHTML = "";
        document.getElementById("id_rol").classList.remove("error-border");
        return true;
    }
}

function validaFormulario(event) {
    event.preventDefault();

    if(validaUserName() && validaRealName() && validaRol()){
        document.getElementById("edit_form").submit();
    }
}






