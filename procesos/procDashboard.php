<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}


if (isset($_SESSION['reserva_done'])) {
        echo '<script> let reserva = true</script>';
        unset($_SESSION['reserva_done']);   
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar sala</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css"
        integrity="sha256-qWVM38RAVYHA4W8TAlDdszO1hRaAq0ME7y2e9aab354=" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
</head>

<body>
    <div class="navbar">
        <a href="../index.php">
            <img src="../img/icon.png" class="icon" alt="Icono">
        </a>
        <a href="./historial.php" class="right-link">Historial</a>
        <div class="user-info">
            <div class="dropdown">
                <i class="fas fa-caret-down" style="font-size: 16px; margin-right: 10px;"></i>
                <div class="dropdown-content">
                    <a href="../private/logout.php">Cerrar Sesión</a>
                </div>
            </div>
            <span><?php echo $_SESSION['username']; ?></span>
        </div>
        <div class="hamburger" id="hamburger-icon">
            &#9776;
        </div>
    </div>
    <div class="mobile-nav" id="mobile-nav">
        <a href="./historial.php">Historial</a>
        <a href="#"><?php echo $_SESSION['username']; ?></a>
        <a href="../private/logout.php">Cerrar Sesión</a>
    </div>

    <div class="options">
        <?php
        try {
            $sql = $conexion->query('SELECT tipo_sala FROM tbl_tipo_sala');
            $results = $sql->fetchAll();

            foreach ($results as $fila) {
                $tipo_sala = $fila['tipo_sala'];
                $background_image = '';

                // Definir imágenes de fondo para cada tipo de sala según la tabla
                switch ($tipo_sala) {
                    case 'Terraza':
                        $background_image = "url('../img/salas/terraza4.jpeg')";
                        break;
                    case 'Comedor':
                        $background_image = "url('../img/salas/comedor.jpeg')";
                        break;
                    case 'Sala Privada':
                        $background_image = "url('../img/salas/privada.jpg')";
                        break;
                    default:
                        $background_image = "url('../img/salas/default.jpg')";
                        break;
                }

                // Crear la opción con el fondo dinámico
                echo '<div class="option" style="background-image: ' . $background_image . '; background-size: cover; background-position: center;">';
                echo '<h2>' . $tipo_sala . '</h2>';
                echo '<div class="button-container">';
                echo '<a href="../procesos/procSalas.php?tipo_sala=' . urlencode($tipo_sala) . '" class="select-button">Seleccionar</a>';
                echo '</div>';
                echo '</div>';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"
        integrity="sha256-1m4qVbsdcSU19tulVTbeQReg0BjZiW6yGffnlr/NJu4=" crossorigin="anonymous"></script>
    <script src="../js/dashboard.js"></script>
    <script src="../js/navbar.js"></script>
    <script>
        if(typeof reserva !== 'undefined' && reserva ) { 
            Swal.fire({
                title: 'Reserva realizada con éxito',
                icon:'success',
                confirmButtonText: 'Aceptar'
            });
        }
    </script>
</body>

</html>
