<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" integrity="sha256-qWVM38RAVYHA4W8TAlDdszO1hRaAq0ME7y2e9aab354=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css"
    integrity="sha256-qWVM38RAVYHA4W8TAlDdszO1hRaAq0ME7y2e9aab354=" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/historial.css">
    <title>Historial de Ocupaciones</title>
</head>
<body>
<div class="navbar">
        <a href="../index.php">
            <img src="../img/icon.png" class="icon" alt="Icono">
        </a>
        <div class="user-info">
        <div class="dropdown">
            <i class="fas fa-caret-down" style="font-size: 16px; margin-right: 10px;"></i>
            <div class="dropdown-content">
                <a href="../private/logout.php">Cerrar Sesión</a>
            </div>
        </div>
        <span><?php echo $_SESSION['username']; ?></span>
    </div>
</div>
    <div class="container">
        <h1>Historial de Reservas</h1>
        <?php
        $sql = "SELECT id_reserva, id_mesa, fecha_reserva, hora_inicio, hora_fin FROM tbl_reserva";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<table class='table table-striped table-hover'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th scope='col'>Id Reserva</th>";
        echo "<th scope='col'>Numero mesa</th>";
        echo "<th scope='col'>Fecha de Reserva</th>";
        echo "<th scope='col'>Hora de Inicio</th>";
        echo "<th scope='col'>Hora de Fin</th>";
        echo "<th scope='col'>Acciones</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // Mostrar los resultados
        foreach ($results as $row) {
            echo "<tr>";
            echo "<td scope='row'>" . htmlspecialchars($row['id_reserva']) . "</td>";
            echo "<td>" . htmlspecialchars($row['id_mesa']) . "</td>";
            echo "<td>" . htmlspecialchars($row['fecha_reserva']) . "</td>";
            echo "<td>" . htmlspecialchars($row['hora_inicio']) . "</td>";
            echo "<td>" . htmlspecialchars($row['hora_fin']) . "</td>";
            echo "<td>";
            echo "<a href='javascript:void(0);' class='btn btn-danger btn-sm' onclick='confirmDelete(" . $row['id_reserva'] . ")'>Eliminar</a>";
            echo "</td>";
            echo "</tr>";
        }

        ?>
       
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function confirmDelete(userId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás cambiar esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../acciones/eliminar_reserva.php?id=' + userId;
                }
            });
        }
    </script>  
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js" integrity="sha256-1m4qVbsdcSU19tulVTbeQReg0BjZiW6yGffnlr/NJu4=" crossorigin="anonymous"></script> 
</body>
</html>
