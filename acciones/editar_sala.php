<?php
session_start();
include_once '../db/conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

// Obtener el ID de la sala a editar
if (!isset($_GET['id'])) {
    echo "Error: Sala no especificada.";
    exit();
}
$id_sala = $_GET['id'];

// Consultar los datos de la sala
$sql = "SELECT * FROM tbl_sala WHERE id_sala = :id_sala";
$stmt = $conexion->prepare($sql);
$stmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
$stmt->execute();
$sala = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sala) {
    echo "Error: Sala no encontrada.";
    exit();
}

// Procesar el formulario al enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_sala = $_POST['nombre_sala'];
    $capacidad_total = $_POST['capacidad_total'];
    $id_tipo_sala = $_POST['id_tipo_sala'];

    // Subir imagen
    $directorio_destino = __DIR__ . '/../img/salas/';
    $nombre_archivo = basename($_FILES['imagen_sala']['name']);
    $ruta_relativa = "/img/salas/" . $nombre_archivo;
    $tipo_archivo = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
    $tipos_permitidos = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($tipo_archivo, $tipos_permitidos)) {
        echo "Formato de archivo no válido. Solo se permiten JPG, JPEG, PNG y GIF.";
        exit();
    }

    if (move_uploaded_file($_FILES['imagen_sala']['tmp_name'], $directorio_destino . $nombre_archivo)) {
        // Actualizar los datos de la sala en la base de datos
        $sql_update = "UPDATE tbl_sala 
                       SET nombre_sala = :nombre_sala, 
                           capacidad_total = :capacidad_total, 
                           id_tipo_sala = :id_tipo_sala, 
                           imagen_sala = :imagen_sala
                       WHERE id_sala = :id_sala";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bindParam(':nombre_sala', $nombre_sala, PDO::PARAM_STR);
        $stmt_update->bindParam(':capacidad_total', $capacidad_total, PDO::PARAM_INT);
        $stmt_update->bindParam(':id_tipo_sala', $id_tipo_sala, PDO::PARAM_INT);
        $stmt_update->bindParam(':imagen_sala', $ruta_relativa, PDO::PARAM_STR);
        $stmt_update->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);

        if ($stmt_update->execute()) {
            echo "<script>
                    alert('Sala actualizada correctamente.');
                    window.location.href = '../admin/dashboardAdmin.php';
                  </script>";
        } else {
            echo "Error al actualizar la sala.";
        }
    } else {
        echo "Error al subir la imagen.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" integrity="sha256-qWVM38RAVYHA4W8TAlDdszO1hRaAq0ME7y2e9aab354=" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Sala</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Sala</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre_sala" class="form-label">Nombre de la Sala</label>
                <input type="text" name="nombre_sala" class="form-control" id="nombre_sala" value="<?= htmlspecialchars($sala['nombre_sala']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="capacidad_total" class="form-label">Capacidad Total</label>
                <input type="number" name="capacidad_total" class="form-control" id="capacidad_total" value="<?= htmlspecialchars($sala['capacidad_total']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="id_tipo_sala" class="form-label">Tipo de Sala</label>
                <select name="id_tipo_sala" id="id_tipo_sala" class="form-select" required>
                    <?php
                    // Obtener los tipos de sala disponibles
                    $sql_tipos = "SELECT * FROM tbl_tipo_sala";
                    $stmt_tipos = $conexion->query($sql_tipos);
                    $tipos = $stmt_tipos->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($tipos as $tipo) {
                        $selected = $tipo['id_tipo_sala'] == $sala['id_tipo_sala'] ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($tipo['id_tipo_sala']) . "' $selected>" . htmlspecialchars($tipo['tipo_sala']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="imagen_sala" class="form-label">Imagen de la Sala</label>
                <input type="file" name="imagen_sala" class="form-control" id="imagen_sala" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Sala</button>
            <a href="../admin/dashboardAdmin.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js" integrity="sha256-1m4qVbsdcSU19tulVTbeQReg0BjZiW6yGffnlr/NJu4=" crossorigin="anonymous"></script>
</body>
</html>
