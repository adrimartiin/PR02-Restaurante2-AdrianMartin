document.getElementById("num_sillas_mesa").onblur = validaNumSillas;
document.getElementById("id_sala").onblur = validaSala;
document.getElementById("edit_form").onsubmit = validaEditForm;

function validaNumSillas(){
    var num_sillas = document.getElementById("num_sillas_mesa").value;
    var input_sillas = document.getElementById("num_sillas_mesa");
    var error_sillas = document.getElementById("error_num_sillas");

    if(num_sillas === "" || num_sillas === null){
        error_sillas.innerHTML = "El campo no debe estar vacío";
        input_sillas.classList.add("error-border");
        return false;
    } else {
        error_sillas.innerHTML = "";
        input_sillas.classList.remove("error-border");
        return true;
    }
}

function validaSala(){
    var sala = document.getElementById("id_sala").value;
    var input_sala = document.getElementById("id_sala");
    var error_sala = document.getElementById("error_id_sala");

    if(sala === "" || sala === null){
        error_sala.innerHTML = "El campo no debe estar vacío";
        input_sala.classList.add("error-border");
        return false;
    } else {
        error_sala.innerHTML = "";
        input_sala.classList.remove("error-border");
        return true;
    }
}

function validaEditForm(event){
    event.preventDefault();

    if(validaNumSillas() && validaSala()){
        document.getElementById("edit_form").submit();
    }
}