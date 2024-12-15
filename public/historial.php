<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

// Capturar el filtro de número de mesa desde el formulario
$filtro_mesa = isset($_GET['usuario']) ? trim($_GET['usuario']) : '';

// Consulta SQL base
$sql = "SELECT r.id_reserva, m.id_mesa, r.fecha_reserva, r.hora_inicio, r.hora_fin 
        FROM tbl_reserva r
        INNER JOIN tbl_mesa m ON r.id_mesa = m.id_mesa";

// Agregar filtro si se ingresó un número de mesa
if ($filtro_mesa !== '') {
    $sql .= " WHERE m.id_mesa = :filtro_mesa";
}

$stmt = $conexion->prepare($sql);

// Enlazar parámetros si hay filtro
if ($filtro_mesa !== '') {
    $stmt->bindParam(':filtro_mesa', $filtro_mesa, PDO::PARAM_INT);
}

$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" integrity="sha256-qWVM38RAVYHA4W8TAlDdszO1hRaAq0ME7y2e9aab354=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/historial.css">
    <title>Historial de Reservas</title>
</head>
<body>
<div class="navbar">
    <a href="../index.php">
        <img src="../img/icon.png" class="icon" alt="Icono">
    </a>
    <!-- Formulario para buscar por número de mesa -->
    <form class="d-flex align-items-center" method="GET" action="">
        <input type="text" name="usuario" class="form-control form-control-sm me-2" 
               placeholder="Número de Mesa" value="<?php echo htmlspecialchars($filtro_mesa); ?>">
        <button type="submit" class="btn btn-primary btn-sm me-3">
            <i class="fas fa-search"></i>
        </button>
        <a href="?" class="btn btn-danger">Limpiar Filtro</a>
    </form>

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
    <table class='table table-striped table-hover'>
        <thead>
            <tr>
                <th scope='col'>Id Reserva</th>
                <th scope='col'>Número de Mesa</th>
                <th scope='col'>Fecha de Reserva</th>
                <th scope='col'>Hora de Inicio</th>
                <th scope='col'>Hora de Fin</th>
                <th scope='col'>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_reserva']); ?></td>
                    <td><?php echo htmlspecialchars($row['id_mesa']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_reserva']); ?></td>
                    <td><?php echo htmlspecialchars($row['hora_inicio']); ?></td>
                    <td><?php echo htmlspecialchars($row['hora_fin']); ?></td>
                    <td>
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm" 
                           onclick="confirmDelete(<?php echo $row['id_reserva']; ?>)">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js" crossorigin="anonymous"></script>
<script>
    function confirmDelete(reservaId) {
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
                window.location.href = '../acciones/eliminar_reserva.php?id=' + reservaId;
            }
        });
    }
</script>
</body>
</html>

