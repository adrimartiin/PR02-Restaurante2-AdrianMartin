    document.getElementById("nombre_usuario").onblur = validaUsername;
    document.getElementById("pwd").onblur = validaPassword;
    document.getElementById("loginForm").onsubmit = validaForm;
    
    function validaUsername() {
    let nombre_usuario = document.getElementById("nombre_usuario").value;
    let input_username = document.getElementById("nombre_usuario");
    let error_username = document.getElementById("error_username");

    if(nombre_usuario === "" || nombre_usuario === null){
        error_username.textContent = "El nombre de usuario es obligatorio.";
        input_username.classList.add("error-border");
        return false;
    } else if(nombre_usuario.length < 4){
        error_username.textContent = "El nombre de usuario debe tener 4 caracteres mínimo.";
        input_username.classList.add("error-border");
        return false;
    } else {
        error_username.textContent = "";
        input_username.classList.remove("error-border");
        return true;
    }
    }

    function validaPassword() {
    let pwd = document.getElementById("pwd").value;
    let input_pwd = document.getElementById("pwd");
    let pwdError = document.getElementById("pwd_error");

    if(pwd === "" || pwd === null){
        pwdError.textContent = "La contraseña es obligatoria.";
        input_pwd.classList.add("error-border");
        return false;
    } else if(pwd.length < 8){
        pwdError.textContent = "La contraseña debe tener 8 caracteres mínimo.";
        input_pwd.classList.add("error-border");
        return false;
    } else if(!pwd.match(/[A-Z]/) || !pwd.match(/[a-z]/) || !pwd.match(/[0-9]/)){
        pwdError.textContent = "La contraseña debe contener al menos una letra mayúscula o minúscula y un número.";
        input_pwd.classList.add("error-border");
        return false;
    } else {
        pwdError.textContent = "";
        input_pwd.classList.remove("error-border");
        return true;
    }
    }

    function validaForm(event) {
    event.preventDefault();
    if (validaUsername() && validaPassword()) {
        document.getElementById("loginForm").submit(); 
    }
    }