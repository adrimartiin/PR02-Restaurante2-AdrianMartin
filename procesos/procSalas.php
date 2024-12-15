<?php
// Conexión a la base de datos
include_once '../db/conexion.php';

// Iniciar sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

// Recoger y sanear lo que viene por GET
$sala_seleccionada = isset($_GET['tipo_sala']) ? htmlspecialchars($_GET['tipo_sala']) : null;

if (isset($sala_seleccionada)) {
    // Consulta para obtener las salas según el tipo de sala que se ha seleccionado
    $query = $conexion->prepare("
        SELECT 
            s.id_sala, 
            s.nombre_sala, 
            t.tipo_sala, 
            s.imagen_sala
        FROM 
            tbl_sala AS s
        INNER JOIN 
            tbl_tipo_sala AS t
        ON 
            s.id_tipo_sala = t.id_tipo_sala
        WHERE 
            t.tipo_sala = :tipo_sala
    ");
    $query->bindParam(':tipo_sala', $sala_seleccionada);
    $query->execute();
    $salas = $query->fetchAll(PDO::FETCH_ASSOC);
} 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/elige_sala.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <title>Seleccionar Sala</title>
</head>

<body>
    <div class="navbar">
        <a href="./procDashboard.php">
            <img src="../img/icon.png" class="icon" alt="Icono">
        </a>
        <a href="../public/historial.php" class="right-link">Historial</a>
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
    
    <form action="./gestion_salas.php" method="post" class="options">
        <?php
        foreach ($salas as $index => $sala) {
            $tipo_sala = $sala['tipo_sala'];
            $nombre_sala = $sala['nombre_sala'];
            $imagen_sala = $sala['imagen_sala'];
            $id_sala = $sala['id_sala'];
            ?>
            <div class="option fondo" style="background-image: url('..<?php echo $imagen_sala; ?>');">
                <h2><?php echo htmlspecialchars($nombre_sala); ?></h2>
                <div class="button-container">
                    <button type="submit" name="sala" value="<?php echo htmlspecialchars($id_sala); ?>"
                        class="select-button">
                        Seleccionar
                    </button>
                </div>
            </div>
        <?php } ?>
    </form>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="../js/dashboard.js"></script>
</html>

