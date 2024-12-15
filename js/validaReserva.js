document.getElementById("hora_inicio").onblur = validaHoraInicio;
document.getElementById("hora_final").onblur = validaHoraFinal;
document.getElementById("dia_reserva").onblur = validaDiaReserva;
document.getElementById("num_personas").onblur = validaNumPersonas;
document.getElementById("formReserva").onsubmit = validaFormReserva;

function validaHoraInicio() {
    var hora_inicio = document.getElementById("hora_inicio").value;
    var inputHoraInicio = document.getElementById("hora_inicio");
    var errorHoraInicio = document.getElementById("error_hora_inicio");

    if (hora_inicio === "" || hora_inicio === "0" || hora_inicio === null) {
        errorHoraInicio.innerHTML = "El campo no debe estar vacío";
        inputHoraInicio.classList.add("error-border");
        return false;
    } else {
        errorHoraInicio.innerHTML = "";
        inputHoraInicio.classList.remove("error-border");
        return true;
    }
}

function validaHoraFinal() {
    var hora_inicio = parseInt(document.getElementById("hora_inicio").value, 10);
    var hora_final = parseInt(document.getElementById("hora_final").value, 10);
    var inputHoraFinal = document.getElementById("hora_final");
    var errorHoraFinal = document.getElementById("error_hora_final");

    if (hora_final === "" || hora_final === "0" || isNaN(hora_final)) {
        errorHoraFinal.innerHTML = "El campo no debe estar vacío";
        inputHoraFinal.classList.add("error-border");
        return false;
    } else if (!isNaN(hora_inicio) && hora_final <= hora_inicio) {
        errorHoraFinal.innerHTML = "La hora final debe ser mayor a la hora de inicio";
        inputHoraFinal.classList.add("error-border");
        return false;
    } else {
        errorHoraFinal.innerHTML = "";
        inputHoraFinal.classList.remove("error-border");
        return true;
    }
}

function validaDiaReserva() {
    var dia_reserva = document.getElementById("dia_reserva").value;
    var inputDiaReserva = document.getElementById("dia_reserva");
    var errorDiaReserva = document.getElementById("error_diaReserva");
    var fecha_actual = new Date();
    var fecha_reserva = new Date(dia_reserva);

    if (dia_reserva === "" || dia_reserva === null) {
        errorDiaReserva.innerHTML = "El campo no debe estar vacío";
        inputDiaReserva.classList.add("error-border");
        return false;
    } else if (fecha_reserva < fecha_actual.setHours(0, 0, 0, 0)) {
        errorDiaReserva.innerHTML = "La fecha de reserva no puede ser anterior a hoy";
        inputDiaReserva.classList.add("error-border");
        return false;
    } else {
        errorDiaReserva.innerHTML = "";
        inputDiaReserva.classList.remove("error-border");
        return true;
    }
}

function validaNumPersonas() {
    var num_personas = document.getElementById("num_personas").value;
    var inputNumPersonas = document.getElementById("num_personas");
    var errorNumPersonas = document.getElementById("error_numPersonas");

    if (num_personas === "" || num_personas === null || num_personas === "0") {
        errorNumPersonas.innerHTML = "El campo no debe estar vacío";
        inputNumPersonas.classList.add("error-border");
        return false;
    } else {
        errorNumPersonas.innerHTML = "";
        inputNumPersonas.classList.remove("error-border");
        return true;
    }
}

function validaFormReserva(event) {
    event.preventDefault();
    if (validaHoraInicio() && validaHoraFinal() && validaDiaReserva() && validaNumPersonas()) {
        document.getElementById("formReserva").submit();
    }
}
