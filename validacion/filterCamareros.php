<?php
include_once '../db/conexion.php';

$noResultados = '';

$filtros = [];
$parametros = [];
$tipos = '';

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

if (!empty($filtros)) {
    $sql = "SELECT nombre_camarero, apellido_camarero FROM tbl_camarero";

    // Asegúrate de agregar el espacio entre WHERE y las condiciones
    $sql .= ' WHERE ' . implode(' AND ', $filtros);

    $stmt = mysqli_prepare($conn, $sql);
    if ($tipos) {
        mysqli_stmt_bind_param($stmt, $tipos, ...$parametros);
    }
    mysqli_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($filtros) && mysqli_num_rows($resultado) === 0) {
        $noResultados = "<p style='color: red;'>No hay resultados para los filtros seleccionados</p>";
    }
} else {
    $resultado = null;
}

