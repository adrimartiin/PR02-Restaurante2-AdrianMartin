<?php
session_start();
include_once '../db/conexion.php';

// Recoger del formulario la mesa que se quiere enviar
$id_mesa = htmlspecialchars($_POST['id_mesa']);
$id_sala = htmlspecialchars($_POST['id_sala']);
$_SESSION['id_mesa'] = $id_mesa;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/formReserva.css">
    <title>Turno</title>
</head>
<body>
    <!-- Formulario para reservar una mesa -->
    <div class="container-reservas">
        <div class="container-dentro">
            <form action="procReserva.php" method="post" id="turnoForm">
                <h3>Selección de turnos</h3>
                <label for="turno">Turno</label>
                <select id="nombre_turno" name="nombre_turno">
                    <option value="">Selecciona un turno</option>
                    <?php
                    $queryTurnos = $conexion->query("SELECT nombre_turno FROM tbl_turnos");
                    $turnos = $queryTurnos->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($turnos as $turno) {
                        echo "<option value='" . $turno['nombre_turno'] . "'" . ($turno['nombre_turno'] == $nombre_turno ? " selected" : "") . ">" . $turno['nombre_turno'] . "</option>";
                    }
                    ?>    
                </select><br>
                <span class="error-message" id="error_turno"></span>
                <input type="hidden" name="id_sala" value="<?php echo $id_sala; ?>">
                <button type="submit" name="reservar" id="reservar">Ir a Reserva</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    <script src="../js/validaTurno.js"></script>
</body>
</html>
