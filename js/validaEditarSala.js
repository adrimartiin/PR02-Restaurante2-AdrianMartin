document.getElementById("nombre_sala").onblur = validaNombreSala;
document.getElementById("capacidad_total").onblur = validaCapacidadTotal;
document.getElementById("id_tipo_sala").onblur = validaTipoSala;
document.getElementById("editar_sala_form").onsubmit = validaFormEditarSala;

function validaNombreSala() {
    var nombreSala = document.getElementById("nombre_sala").value.trim();
    var inputNombre = document.getElementById("nombre_sala");
    var errorNombre = document.getElementById("error_nombre");

    if (nombreSala === "") {
        errorNombre.textContent = "El nombre de la sala no debe estar vacío.";
        inputNombre.classList.add("error-border");
        return false;
    } else {
        errorNombre.textContent = "";
        inputNombre.classList.remove("error-border");
        return true;
    }
}

function validaCapacidadTotal() {
    var capacidadTotal = document.getElementById("capacidad_total").value.trim();
    var inputCapacidad = document.getElementById("capacidad_total");
    var errorCapacidad = document.getElementById("error_capacidad");

    if (capacidadTotal === "" || isNaN(capacidadTotal) || parseInt(capacidadTotal) <= 0) {
        errorCapacidad.textContent = "La capacidad total debe ser un número positivo.";
        inputCapacidad.classList.add("error-border");
        return false;
    } else {
        errorCapacidad.textContent = "";
        inputCapacidad.classList.remove("error-border");
        return true;
    }
}

function validaTipoSala() {
    var idTipoSala = document.getElementById("id_tipo_sala").value;
    var inputTipoSala = document.getElementById("id_tipo_sala");
    var errorTipoSala = document.getElementById("error_tipo_sala");

    if (idTipoSala === "" || idTipoSala === null) {
        errorTipoSala.textContent = "Debes seleccionar un tipo de sala.";
        inputTipoSala.classList.add("error-border");
        return false;
    } else {
        errorTipoSala.textContent = "";
        inputTipoSala.classList.remove("error-border");
        return true;
    }
}

function validaImagenSala() {
    var imagenSala = document.getElementById("imagen_sala").value;
    var inputImagen = document.getElementById("imagen_sala");
    var errorImagen = document.getElementById("error_imagen");
    var tiposPermitidos = ["jpg", "jpeg", "png", "gif"];
    var extension = imagenSala.split('.').pop().toLowerCase();

    if (imagenSala && !tiposPermitidos.includes(extension)) {
        errorImagen.textContent = "Formato de imagen no válido. Permitidos: JPG, JPEG, PNG, GIF.";
        inputImagen.classList.add("error-border");
        return false;
    } else {
        errorImagen.textContent = "";
        inputImagen.classList.remove("error-border");
        return true;
    }
}

function validaFormEditarSala(event) {
    event.preventDefault();

    var validaNombre = validaNombreSala();
    var validaCapacidad = validaCapacidadTotal();
    var validaTipo = validaTipoSala();
    var validaImagen = validaImagenSala();

    if (validaNombre && validaCapacidad && validaTipo && validaImagen) {
        document.getElementById("editar_sala_form").submit();
    }
}
