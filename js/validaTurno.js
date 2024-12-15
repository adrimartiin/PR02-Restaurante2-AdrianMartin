document.getElementById("nombre_turno").onblur = validaTurno;
document.getElementById("turnoForm").onsubmit = validaTurnoForm;

function validaTurno() {
    var turno = document.getElementById("nombre_turno").value;
    var inputTurno = document.getElementById("nombre_turno");
    var error = document.getElementById("error_turno");

    if(turno === "" || turno === null || turno === "0"){
        error.innerHTML = "El campo no debe estar vacío";
        inputTurno.classList.add("error-border");
        return false;
    } else {
        error.innerHTML = "";
        inputTurno.classList.remove("error-border");
        return true;
    }
}

function validaTurnoForm(event) {
    event.preventDefault();
    if(validaTurno()){
        document.getElementById("turnoForm").submit();
    }
}