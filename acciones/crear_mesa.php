<?php
session_start();
include_once '../db/conexion.php';

// Verificar sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

$mesa_creada = false;
$error_mesa_existente = false;
$error_mesas_excedidas = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_mesa = htmlspecialchars(trim($_POST['numero_mesa']));
    $num_sillas_mesa = htmlspecialchars(trim($_POST['num_sillas_mesa']));
    $id_sala = htmlspecialchars($_POST['id_sala']);

    try {
        // Verificar si ya existe una mesa con el número especificado
        $stmt = $conexion->prepare("SELECT * FROM tbl_mesa WHERE id_mesa = :numero_mesa");
        $stmt->bindParam(':numero_mesa', $numero_mesa);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error_mesa_existente = true;
        } else {
            // Validar si ya existen 12 mesas en la sala
            $stmt2 = $conexion->prepare("SELECT COUNT(*) as total_mesas FROM tbl_mesa WHERE id_sala = :id_sala");
            $stmt2->bindParam(':id_sala', $id_sala);
            $stmt2->execute();
            $mesas_en_sala = $stmt2->fetch(PDO::FETCH_ASSOC)['total_mesas'];

            if ($mesas_en_sala >= 12) {
                $error_mesas_excedidas = true;
            } else {
                // Insertar nueva mesa
                $stmt3 = $conexion->prepare("INSERT INTO tbl_mesa (id_mesa, id_sala, num_sillas_mesa) VALUES (:numero_mesa, :id_sala, :num_sillas_mesa)");
                $stmt3->bindParam(':numero_mesa', $numero_mesa);
                $stmt3->bindParam(':id_sala', $id_sala);
                $stmt3->bindParam(':num_sillas_mesa', $num_sillas_mesa);
                $stmt3->execute();
                $mesa_creada = true;
            }
        }
    } catch (PDOException $e) {
        $error_message = "Error al crear la mesa: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Crear Mesa</title>
</head>

<body>
    <div class="container mt-5">
        <h2>Crear Nueva Mesa</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="numero_mesa" class="form-label">Número de Mesa</label>
                <input type="number" class="form-control" id="numero_mesa" name="numero_mesa" required>
            </div>
            <div class="mb-3">
                <label for="num_sillas_mesa" class="form-label">Número de Sillas</label>
                <input type="number" class="form-control" id="num_sillas_mesa" name="num_sillas_mesa" required>
            </div>
            <div class="mb-3">
                <label for="id_sala" class="form-label">Sala</label>
                <select class="form-select" id="id_sala" name="id_sala" required>
                    <option value="">Seleccione una sala</option>
                    <?php
                    $stmt = $conexion->query("SELECT id_sala, nombre_sala FROM tbl_sala");
                    $salas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($salas as $sala) {
                        echo "<option value='{$sala['id_sala']}'>{$sala['nombre_sala']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Crear Mesa</button>
            <a href="../admin/dashboardAdmin.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script>
        <?php if ($mesa_creada): ?>
            Swal.fire({
                title: 'Mesa creada!',
                text: 'La mesa se ha creado correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            }).then(() => window.location.href = '../gestiones/gestionMesas.php');
        <?php elseif ($error_mesa_existente): ?>
            Swal.fire({
                title: 'Error!',
                text: 'Ya existe una mesa con este número.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        <?php elseif ($error_mesas_excedidas): ?>
            Swal.fire({
                title: 'Error!',
                text: 'No se pueden crear más de 12 mesas por sala.',
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
</body>

</html>
