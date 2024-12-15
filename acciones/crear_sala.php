<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

$sala_creada = false;
$sala_existente = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_sala = htmlspecialchars(trim($_POST['nombre_sala']));
    $id_tipo_sala = htmlspecialchars($_POST['id_tipo_sala']);
    $capacidad_total = htmlspecialchars($_POST['capacidad_total']);
    $num_mesas_sala = htmlspecialchars($_POST['num_mesas_sala']);

    try {
        $stmt = $conexion->prepare("SELECT * FROM tbl_sala WHERE nombre_sala = :nombre_sala");
        $stmt->bindParam(':nombre_sala', $nombre_sala);
        $stmt->execute();
       
        if ($stmt->rowCount() > 0) {
            $sala_existente = true;
        } else {
            // Procesar imagen
            include '../procesos/procesar_imagen.php';
            if ($imagen_subida) {
                $stmt2 = $conexion->prepare("INSERT INTO tbl_sala (nombre_sala, id_tipo_sala, capacidad_total, num_mesas_sala, imagen_sala) 
                                            VALUES (:nombre_sala, :id_tipo_sala, :capacidad_total, :num_mesas_sala, :imagen_sala)");
                $stmt2->bindParam(':nombre_sala', $nombre_sala);
                $stmt2->bindParam(':id_tipo_sala', $id_tipo_sala);
                $stmt2->bindParam(':capacidad_total', $capacidad_total);
                $stmt2->bindParam(':num_mesas_sala', $num_mesas_sala);
                $stmt2->bindParam(':imagen_sala', $ruta_relativa);
                $stmt2->execute();
                $sala_creada = true;
            }
        }
    } catch (PDOException $e) {
        $error_message = "Error al crear la sala: " . $e->getMessage();
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/users.css">
    <title>Crear Sala</title>
</head>

<body>
    <div class="container mt-5">
        <h2>Crear Nueva Sala</h2>
        <form method="POST" enctype="multipart/form-data" id="crearSalaForm">
            <div class="mb-3">
                <label for="nombre_sala" class="form-label">Nombre de Sala</label>
                <input type="text" class="form-control" id="nombre_sala" name="nombre_sala">
                <span class="error-message" id="error_nombre_sala"></span>
            </div>
            <div class="mb-3">
                <label for="id_tipo_sala" class="form-label">Tipo de Sala</label>
                <select class="form-select" id="id_tipo_sala" name="id_tipo_sala">
                    <option value="">Seleccione un tipo de sala</option>
                    <?php
                    $stmt = $conexion->query("SELECT * FROM tbl_tipo_sala");
                    $tipos_sala = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($tipos_sala as $tipo) {
                        echo "<option value='{$tipo['id_tipo_sala']}'>{$tipo['tipo_sala']}</option>";
                    }
                    ?>
                </select>
                <span class="error-message" id="error_tipo_sala"></span>
            </div>
            <div class="mb-3">
                <label for="capacidad_total" class="form-label">Capacidad Total</label>
                <input type="number" class="form-control" id="capacidad_total" name="capacidad_total">
                <span class="error-message" id="error_capacidad_total"></span>
            </div>
            <div class="mb-3">
                <label for="num_mesas_sala" class="form-label">Número de Mesas</label>
                <input type="number" class="form-control" id="num_mesas_sala" name="num_mesas_sala">
                <span class="error-message" id="error_num_mesas"></span>
            </div>
            <div class="mb-3">
                <label for="imagen_sala" class="form-label">Imagen de Sala</label>
                <input type="file" class="form-control" id="imagen_sala" name="imagen_sala" accept="image/*">
                <span class="error-message" id="error_img"></span>
            </div>
            <button type="submit" class="btn btn-primary">Crear Sala</button>
            <a href="../gestiones/gestionSalas.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if ($sala_creada): ?>
            Swal.fire({
                title: 'Sala creada!',
                text: 'La sala ha sido creada correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            }).then(() => window.location.href = '../gestiones/gestionSalas.php');
        <?php elseif ($sala_existente): ?>
            Swal.fire({
                title: 'Error!',
                text: 'Ya existe una sala con ese nombre.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        <?php elseif (!empty($error_message)): ?>
            Swal.fire({
                title: 'Error!',
                text: '<?php echo $error_message; ?>',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        <?php endif; ?>
    </script>
    <script src="../js/validaCrearSala.js"></script>
</body>
</html>
