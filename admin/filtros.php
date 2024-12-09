<?php
// Obtener los valores de los filtros (si se han establecido)
$usuario_filter = isset($_GET['usuario']) ? $_GET['usuario'] : '';
$nombre_filter = isset($_GET['nombre']) ? $_GET['nombre'] : '';

// ==== QUERY CON LOS FILTROS ====
$sql = "SELECT id_usuario, nombre_usuario, nombre_real_usuario, nombre_rol 
        FROM tbl_usuario 
        INNER JOIN tbl_rol ON tbl_usuario.id_rol = tbl_rol.id_rol
        WHERE nombre_usuario LIKE :usuario AND nombre_real_usuario LIKE :nombre";

// uso bindValue para enlazar un valor a un parámetro de una consulta
$stmt = $conexion->prepare($sql);
$stmt->bindValue(':usuario', '%' . $usuario_filter . '%', PDO::PARAM_STR);
$stmt->bindValue(':nombre', '%' . $nombre_filter . '%', PDO::PARAM_STR);


$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
