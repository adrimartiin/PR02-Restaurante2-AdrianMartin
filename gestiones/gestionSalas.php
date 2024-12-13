<?php
session_start();
include_once '../db/conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

// Obtener filtros desde GET
$sala_filter = isset($_GET['sala']) ? $_GET['sala'] : '';
$tipo_sala_filter = isset($_GET['tipo_sala']) ? $_GET['tipo_sala'] : '';

// ==== QUERY PARA SACAR RESULTADOS FILTRADOS O SIN FILTRAR ====
$sql = "SELECT tbl_sala.id_sala, tbl_sala.nombre_sala, tbl_sala.capacidad_total, tbl_sala.imagen_sala, tbl_tipo_sala.tipo_sala
        FROM tbl_sala 
        INNER JOIN tbl_tipo_sala ON tbl_sala.id_tipo_sala = tbl_tipo_sala.id_tipo_sala
        WHERE (:sala = '' OR tbl_sala.id_sala = :sala) 
        AND (:tipo_sala = '' OR tbl_tipo_sala.id_tipo_sala = :tipo_sala)
        ORDER BY tbl_sala.nombre_sala ASC";
$stmt = $conexion->prepare($sql);
$stmt->bindValue(':sala', $sala_filter, PDO::PARAM_STR);
$stmt->bindValue(':tipo_sala', $tipo_sala_filter, PDO::PARAM_STR);
$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consultar para llenar los filtros
$querySalas = "SELECT id_sala, nombre_sala FROM tbl_sala";
$stmtSalas = $conexion->prepare($querySalas);
$stmtSalas->execute();
$salas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);

$queryTipos = "SELECT id_tipo_sala, tipo_sala FROM tbl_tipo_sala";
$stmtTipos = $conexion->prepare($queryTipos);
$stmtTipos->execute();
$tipos = $stmtTipos->fetchAll(PDO::FETCH_ASSOC);
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

        <form class="d-flex align-items-center" method="GET" action="">
            <select name="tipo_sala" class="form-control form-control-sm me-2">
                <option value="">Tipo de Sala</option>
                <?php foreach ($tipos as $tipo): ?>
                    <option value="<?= htmlspecialchars($tipo['id_tipo_sala']) ?>"
                        <?= $tipo_sala_filter == $tipo['id_tipo_sala'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tipo['tipo_sala']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="sala" class="form-control form-control-sm me-2">
                <option value="">Sala</option>
                <?php foreach ($salas as $sala): ?>
                    <option value="<?= htmlspecialchars($sala['id_sala']) ?>" <?= $sala_filter == $sala['id_sala'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sala['nombre_sala']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary btn-sm me-3">
                <i class="fas fa-search"></i>
            </button>
            <a href="gestionSalas.php" class="btn btn-danger">
                <i class="fas fa-trash-alt"></i>
            </a>
        </form>

        <div class="d-flex align-items-center">
            <a href="../acciones/crear_sala.php" class="btn btn-primary btn-sm me-3">Crear Sala</a>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <?= htmlspecialchars($_SESSION['username']) ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="../private/logout.php">Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </div>

    <h3 id="titulo">Gestión de Salas</h3>

    <?php if (!empty($resultados)): ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Nombre Sala</th>
                    <th scope="col">Tipo Sala</th>
                    <th scope="col">Capacidad Total</th>
                    <th scope="col">Imagen</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultados as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nombre_sala']) ?></td>
                        <td><?= htmlspecialchars($row['tipo_sala']) ?></td>
                        <td><?= htmlspecialchars($row['capacidad_total']) ?></td>
                        <td><img src="..<?= htmlspecialchars($row['imagen_sala']) ?>" width="100" height="100"></td>
                        <td>
                            <a href="../acciones/editar_sala.php?id=<?= $row['id_sala'] ?>"
                                class="btn btn-warning btn-sm">Editar</a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm"
                                onclick="confirmDelete(<?= $row['id_sala'] ?>)">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se han encontrado resultados para este filtro.</p>
    <?php endif; ?>

    <script src="../js/dashboard.js"></script>
    <script src="../js/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

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
                    window.location.href = '../acciones/eliminar_sala.php?id=' + userId;
                }
            });
        }
    </script>
</body>

</html>