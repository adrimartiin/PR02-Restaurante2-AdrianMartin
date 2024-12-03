<?php
session_start();
include_once '../db/conexion.php';

$noResultados = '';

$filtros = [];
$parametros = [];
$tipos = '';

// Filtros de búsqueda
if (!empty($_GET['nombre'])) {
    $filtros[] = 'nombre_camarero LIKE ?';
    $parametros[] = '%' . $_GET['nombre'] . '%';
    $tipos .= 's';  
}

if (!empty($_GET['apellido'])) {
    $filtros[] = 'apellido_camarero LIKE ?';
    $parametros[] = '%' . $_GET['apellido'] . '%';
    $tipos .= 's';  
}

// Por defecto 'asc'
$orden = isset($_GET['orden']) && $_GET['orden'] == 'desc' ? 'desc' : 'asc'; 


$nextOrden = $orden == 'asc' ? 'desc' : 'asc';


$sql = "SELECT id_camarero, nombre_camarero, apellido_camarero, codigo_camarero, password_camarero FROM tbl_camarero";


if (!empty($filtros)) {
    $sql .= ' WHERE ' . implode(' AND ', $filtros);
}

$sql .= " ORDER BY apellido_camarero $orden";

$stmt = mysqli_prepare($conn, $sql);

if ($tipos) {
    mysqli_stmt_bind_param($stmt, $tipos, ...$parametros);
}

mysqli_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($filtros) && mysqli_num_rows($resultado) === 0) {
    $noResultados = "<p style='color: red;'>No hay resultados para los filtros seleccionados</p>";
} elseif (mysqli_num_rows($resultado) === 0) {
    echo '<div class="alert alert-warning" role="alert">No se han encontrado registros</div>';
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar sala</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
            <span><?php echo $_SESSION['nombre_usuario']; ?></span>
        </div>
        <div class="hamburger" id="hamburger-icon">
            &#9776;
        </div>
    </div>

    <div class="container mt-4">
        <h3 class="mb-3">Lista de Camareros</h3>
        <form action="formCamareros.php" method="post">
            <button type="submit" class="select-button2" name="creaCamareros"> +</button>
        </form>
        <form method="get">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : ''; ?>" placeholder="Nombre">
            <label for="apellido">Apellido</label>
            <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo isset($_GET['apellido']) ? htmlspecialchars($_GET['apellido']) : ''; ?>" placeholder="Apellido">
            <button type="submit" class="select-button2" name="filtrarCamareros">Filtrar</button>
        </form>

        <?php
        // Si hay resultados
        if (isset($resultado) && mysqli_num_rows($resultado) > 0) {
            echo '<table class="table table-striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Nombre</th>';
            echo '<th>Apellido 
                    <a href="?'. $_SERVER["QUERY_STRING"] .'&orden=' . $nextOrden . '"> 
                    ' . ($orden == 'asc' ? '↓' : '↑') . '</a>
                </th>';
            echo '<th>Código</th>';
            echo '<th>Password</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = mysqli_fetch_assoc($resultado)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['id_camarero']) . '</td>';
                echo '<td>' . htmlspecialchars($row['nombre_camarero']) . '</td>';
                echo '<td>' . htmlspecialchars($row['apellido_camarero']) . '</td>';
                echo '<td>' . htmlspecialchars($row['codigo_camarero']) . '</td>';
                echo '<td>' . htmlspecialchars($row['password_camarero']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<div class="alert alert-warning" role="alert">No se han encontrado registros</div>';
        }
        ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>





