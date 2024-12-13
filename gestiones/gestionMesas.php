<?php
session_start();
include_once '../db/conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/users.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Gestión de Salas</title>
</head>

<body>
<div class="navbar d-flex justify-content-between align-items-center px-3">
        <a href="../admin/dashboardAdmin.php">
            <img src="../img/icon.png" class="icon" alt="Icono">
        </a>

        <div id="hamburger-icon" class="d-block d-md-none">
            <i class="fas fa-bars"></i> 
        </div>

        <form class="d-flex align-items-center" method="GET" action="">
            <input type="text" name="usuario" class="form-control form-control-sm me-2" placeholder="Usuario">
            <input type="text" name="nombre" class="form-control form-control-sm me-2" placeholder="Nombre">
            <button type="submit" class="btn btn-primary btn-sm me-3">
                <i class="fas fa-search"></i>
            </button>
            <a href="./users.php" class="btn btn-danger">
                <i class="fas fa-trash-alt"></i>
            </a>
        </form>

        <div class="d-flex align-items-center">
        <form class="d-flex align-items-center" method="GET" action="">
            <a href="../acciones/crear_mesa.php" class="btn btn-primary btn-sm me-3">Añadir Mesa</a>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo $_SESSION['username']; ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="../private/logout.php">Cerrar Sesión</a></li>
                </ul>
            </div>
            </form>
        </div>
    </div>

    <!-- Menú para dispositivos móviles -->
    <div class="mobile-nav" id="mobile-nav">
        <input type="text" name="usuario" class="form-control form-control-sm me-2" placeholder="Usuario">
            <input type="text" name="nombre" class="form-control form-control-sm me-2" placeholder="Nombre">
            <button type="submit" class="btn btn-primary btn-sm me-3">
                <i class="fas fa-search"></i>
            </button>
            <button type="reset" class="btn btn-danger">
                <i class="fas fa-trash-alt"></i>
            </button>
        <a href="#"><?php echo $_SESSION['username']; ?></a>
        <a href="../private/logout.php">Cerrar Sesión</a>
    </div>

    <h3 id="titulo">Gestión de Mesas</h3>

    <?php
    // ==== QUERY PARA MOSTRAR MESAS + EN LA SALA QUE SE ENCUENTRAN ====
    $sql = "SELECT id_mesa, num_sillas_mesa, nombre_sala FROM tbl_mesa INNER JOIN tbl_sala ON tbl_mesa.id_sala = tbl_sala.id_sala";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<table class='table table-striped table-hover'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th scope='col'>Numero de Mesa</th>";
    echo "<th scope='col'>Numero de sillas</th>";
    echo "<th scope='col'>Sala</th>";
    echo "<th scope='col'>Acciones</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    // Mostrar los resultados
    foreach ($results as $row) {
        echo "<tr>";
        echo "<td scope='row'>" . htmlspecialchars($row['id_mesa']) . "</td>";
        echo "<td>" . htmlspecialchars($row['num_sillas_mesa']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nombre_sala']) . "</td>";
        echo "<td>";
        echo "<a href='../acciones/editar_usuario.php?id=" . $row['id_mesa'] . "' class='btn btn-warning btn-sm'>Editar</a> ";
        echo "<a href='javascript:void(0);' class='btn btn-danger btn-sm' onclick='confirmDelete(" . $row['id_mesa'] . ")'>Eliminar</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    ?>
</body>