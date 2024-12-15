document.getElementById("nombre_sala").onblur = validaNombreSala;
document.getElementById("id_tipo_sala").onblur = validaIdTipoSala;
document.getElementById("capacidad_total").onblur = validaCapacidadTotal;
document.getElementById("num_mesas_sala").onblur = validaNumMesasSala;
document.getElementById("imagen_sala").onblur = validaImg;
document.getElementById("crearSalaForm").onsubmit = validaCrearSalaForm;

function validaNombreSala() {
    var nombreSala = document.getElementById("nombre_sala").value;
    var inputNombreSala = document.getElementById("nombre_sala");
    var errorNombreSala = document.getElementById("error_nombre_sala");

    if(nombreSala == "" || nombreSala == null || nombreSala.length === 0){
        errorNombreSala.innerHTML = "El campo no debe estar vacío";
        inputNombreSala.classList.add("error-border");
        return false;
    } else {
        errorNombreSala.innerHTML = "";
        inputNombreSala.classList.remove("error-border");
        return true;
    }
}

function validaIdTipoSala() {
    var idTipoSala = document.getElementById("id_tipo_sala").value;
    var inputIdTipoSala = document.getElementById("id_tipo_sala");
    var errorIdTipoSala = document.getElementById("error_tipo_sala");

    if(idTipoSala === "" || idTipoSala === null || idTipoSala === "0"){
        errorIdTipoSala.innerHTML = "El campo no debe estar vacío";
        inputIdTipoSala.classList.add("error-border");
        return false;
    } else {
        errorIdTipoSala.innerHTML = "";
        inputIdTipoSala.classList.remove("error-border");
        return true;
    }
}

function validaCapacidadTotal() {
    var capacidadTotal = document.getElementById("capacidad_total").value;
    var inputCapacidadTotal = document.getElementById("capacidad_total");
    var errorCapacidadTotal = document.getElementById("error_capacidad_total");

    if(capacidadTotal === "" || capacidadTotal === null){
        errorCapacidadTotal.innerHTML = "El campo no debe estar vacío";
        inputCapacidadTotal.classList.add("error-border");
        return false;
    } else {
        errorCapacidadTotal.innerHTML = "";
        inputCapacidadTotal.classList.remove("error-border");
        return true;
    }
}

function validaNumMesasSala() {
    var numMesasSala = document.getElementById("num_mesas_sala").value;
    var inputNumMesasSala = document.getElementById("num_mesas_sala");
    var errorNumMesasSala = document.getElementById("error_num_mesas");

    if(numMesasSala === "" || numMesasSala === null){
        errorNumMesasSala.innerHTML = "El campo no debe estar vacío";
        inputNumMesasSala.classList.add("error-border");
        return false;
    } else {
        errorNumMesasSala.innerHTML = "";
        inputNumMesasSala.classList.remove("error-border");
        return true;
    }
}

function validaImg() {
    var img = document.getElementById("imagen_sala").value;
    var inputImg = document.getElementById("imagen_sala");
    var errorImg = document.getElementById("error_img");

    if(img === "" || img === null){
        errorImg.innerHTML = "El campo no debe estar vacío";
        inputImg.classList.add("error-border");
        return false;
    } else {
        errorImg.innerHTML = "";
        inputImg.classList.remove("error-border");
        return true;
    }
}

function validaCrearSalaForm(event) {
    event.preventDefault(); // Previene el envío del formulario
    var validacion = validaNombreSala() && validaIdTipoSala() && validaCapacidadTotal() && validaNumMesasSala() && validaImg();
    if(validacion) {
        document.getElementById("crearSalaForm").submit()
    }
}
