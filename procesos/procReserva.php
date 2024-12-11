<?php
session_start();
include_once '../db/conexion.php';

$turno = htmlspecialchars($_POST['nombre_turno']);

?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/formReserva.css">
    <title>Reserva Mesas</title>
</head>

<body>
    <div class="container-reservas">
        <div class="container-dentro">
            <form action="procInsertaReserva.php" method="post">
                <h3>Reservar</h3>
                <label for="hora_inicio">Hora de la Reserva</label>
                <select id="hora_inicio" name="hora_inicio">
                    <option value="">Selecciona una hora</option>
                    <?php
                    if ($turno == "Mediodía") {
                        // Definir los intervalos de tiempo que quieres mostrar
                        $intervalos = array("00", "15", "30", "45");

                        for ($i = 12; $i <= 16; $i++) {
                            foreach ($intervalos as $minuto) {
                                echo "<option value='" . $i . ":" . $minuto . "'>" . $i . ":" . $minuto . "</option>";
                            }
                        }
                    } else if ($turno == "Noche") {
                        $intervalos = array("00", "15", "30", "45");

                        for ($i = 19; $i <= 23; $i++) {
                            foreach ($intervalos as $minuto) {
                                echo "<option value='" . $i . ":" . $minuto . "'>" . $i . ":" . $minuto . "</option>";
                            }
                        }
                    }
                    ?>
                </select><br>

                <label for="hora_final">Hora final de la Reserva</label>
                <select id="hora_final" name="hora_final">
                    <option value="">Selecciona la hora final</option>
                    <?php
                    if ($turno == "Mediodía") {
                        // Definir los intervalos de tiempo que quieres mostrar
                        $intervalos = array("00", "15", "30", "45");

                        for ($i = 12; $i <= 16; $i++) {
                            foreach ($intervalos as $minuto) {
                                echo "<option value='" . $i . ":" . $minuto . "'>" . $i . ":" . $minuto . "</option>";
                            }
                        }
                    } else if ($turno == "Noche") {
                        $intervalos = array("00", "15", "30", "45");

                        for ($i = 19; $i <= 23; $i++) {
                            foreach ($intervalos as $minuto) {
                                echo "<option value='" . $i . ":" . $minuto . "'>" . $i . ":" . $minuto . "</option>";
                            }
                        }
                    }
                    ?>
                </select><br>

                <label for="dia_reserva">Día de la reserva</label>
                <input type="date" id="dia_reserva" name="dia_reserva"><br>

                <label for="num_personas">Número de Personas</label>
                <select id='num_personas' name="num_personas">
                <option value=''>Selecciona un número de personas</option>
                <?php
                
                for($i=1; $i<16; $i++){
                    if($i == 1){
                        echo "<option value='$i'>$i Persona</option>";
                    } else {
                        echo "<option value='$i'>$i Personas</option>";
                    }
                }
                
                ?>
                </select><br>
                <button type="submit" name="reservar" id="reservar">Reservar</button>
            </form>
        </div>
    </div>
</body>

</html>