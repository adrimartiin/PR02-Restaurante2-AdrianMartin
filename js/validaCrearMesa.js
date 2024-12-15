document.getElementById("numero_mesa").onblur = validaNumMesa;
document.getElementById("num_sillas_mesa").onblur = validaNumSillas;
document.getElementById("id_sala").onblur = validaSala;
document.getElementById("formCreaMesa").onsubmit = validaForm;

function validaNumMesa() {
    var numMesa = document.getElementById("numero_mesa").value;
    var inputMesa = document.getElementById("numero_mesa");
    var errorMesa = document.getElementById("error_numero_mesa");

    if(numMesa === "" || numMesa === null ){
        errorMesa.textContent = "El campo no debe estar vacío";
        inputMesa.classList.add("error-border");
        return false;
    } else {
        errorMesa.textContent = "";
        inputMesa.classList.remove("error-border");
        return true;
    }

}

function validaNumSillas(){
    var numSillas = document.getElementById("num_sillas_mesa").value;
    var inputSillas = document.getElementById("num_sillas_mesa");
    var errorSillas = document.getElementById("error_num_sillas_mesa");

    if(numSillas === "" || numSillas === null ){
        errorSillas.textContent = "El campo no debe estar vacío";
        inputSillas.classList.add("error-border");
        return false;
    } else {
        errorSillas.textContent = "";
        inputSillas.classList.remove("error-border");
        return true;
    }
}

function validaSala(){
    var idSala = document.getElementById("id_sala").value;
    var inputSala = document.getElementById("id_sala");
    var errorSala = document.getElementById("error_id_sala"); 

    if(idSala === "" || idSala === null ){
        errorSala.textContent = "El campo no debe estar vacío";
        inputSala.classList.add("error-border");
        return false;
    } else {
        errorSala.textContent = "";
        inputSala.classList.remove("error-border");
        return true;
    }
}

function validaForm(event) {
    event.preventDefault();

    if( validaNumMesa() && validaNumSillas() &&  validaSala()){
        document.getElementById("formCreaMesa").submit();
    }
}