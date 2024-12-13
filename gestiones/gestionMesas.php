<?php
session_start();
include_once '../db/conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

// ==== GESTIÓN DE PAGINACIÓN ====
$registrosPorPagina = isset($_GET['registros']) ? (int)$_GET['registros'] : 10;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $registrosPorPagina;

// Obtener todas las salas para el selector de filtros
$sqlSalas = "SELECT id_sala, nombre_sala FROM tbl_sala";
$stmtSalas = $conexion->prepare($sqlSalas);
$stmtSalas->execute();
$salas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);

// Contar el total de registros
$sqlCount = "SELECT COUNT(*) AS total FROM tbl_mesa";
$stmtCount = $conexion->prepare($sqlCount);
$stmtCount->execute();
$totalRegistros = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// ==== QUERY PARA MOSTRAR MESAS CON LÍMITE Y OFFSET ====
$salaFiltro = isset($_GET['sala']) ? (int)$_GET['sala'] : null;
$salaCondition = $salaFiltro ? " WHERE tbl_mesa.id_sala = :sala" : "";
$sql = "SELECT id_mesa, num_sillas_mesa, nombre_sala 
        FROM tbl_mesa 
        INNER JOIN tbl_sala ON tbl_mesa.id_sala = tbl_sala.id_sala 
        $salaCondition
        LIMIT :limit OFFSET :offset";
$stmt = $conexion->prepare($sql);
if ($salaFiltro) {
    $stmt->bindValue(':sala', $salaFiltro, PDO::PARAM_INT);
}
$stmt->bindValue(':limit', $registrosPorPagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <select name="sala" class="form-select form-select-sm me-3" style="width: auto;">
            <option value="">Filtrar por sala</option>
            <?php foreach ($salas as $sala): ?>
                <option value="<?= $sala['id_sala'] ?>" <?= (isset($_GET['sala']) && $_GET['sala'] == $sala['id_sala']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($sala['nombre_sala']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-search"></i>
        </button>
        <a href="./gestionMesas.php" class="btn btn-danger">
            <i class="fas fa-trash-alt"></i>
        </a>
    </form>

    <div class="d-flex align-items-center">
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
    </div>
</div>

<h3 id="titulo">Gestión de Mesas</h3>

<!-- Selector de registros por página -->
<form method="GET" class="mb-3 d-flex align-items-center">
    <label for="registros" class="me-2">Registros por página:</label>
    <select name="registros" id="registros" class="form-select form-select-sm me-3" style="width: auto;">
        <option value="5" <?= $registrosPorPagina === 5 ? 'selected' : '' ?>>5</option>
        <option value="10" <?= $registrosPorPagina === 10 ? 'selected' : '' ?>>10</option>
        <option value="20" <?= $registrosPorPagina === 20 ? 'selected' : '' ?>>20</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">Aplicar</button>
</form>

<?php
// Mostrar los resultados
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

foreach ($results as $row) {
    echo "<tr>";
    echo "<td scope='row'>" . htmlspecialchars($row['id_mesa']) . "</td>";
    echo "<td>" . htmlspecialchars($row['num_sillas_mesa']) . "</td>";
    echo "<td>" . htmlspecialchars($row['nombre_sala']) . "</td>";
    echo "<td>";
    echo "<a href='../acciones/editar_mesa.php?id=" . $row['id_mesa'] . "' class='btn btn-warning btn-sm'>Editar</a> ";
    echo "<a href='javascript:void(0);' class='btn btn-danger btn-sm' onclick='confirmDelete(" . $row['id_mesa'] . ")'>Eliminar</a>";
    echo "</td>";
    echo "</tr>";
}

echo "</tbody>";
echo "</table>";

// ==== GENERAR PAGINACIÓN ====
if ($totalPaginas > 1) {
    echo "<nav>";
    echo "<ul class='pagination justify-content-center'>";
    for ($i = 1; $i <= $totalPaginas; $i++) {
        $active = $i === $paginaActual ? 'active' : '';
        echo "<li class='page-item $active'>";
        echo "<a class='page-link' href='?pagina=$i&registros=$registrosPorPagina&sala=$salaFiltro'>$i</a>";
        echo "</li>";
    }
    echo "</ul>";
    echo "</nav>";
}
?>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Estás seguro?',
            text: "No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../acciones/eliminar_mesa.php?id=' + id;
            }
        });
    }
</script>

</body>
</html>


