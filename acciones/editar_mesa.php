<?php
session_start();
include_once '../db/conexion.php';

// Inicializar la variable para evitar el error
$no_mesa = false;

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

$success = false;  

if (isset($_GET['id'])) {
    $idMesa = (int)$_GET['id'];
    try {
        $stmt = $conexion->prepare("SELECT * FROM tbl_mesa WHERE id_mesa = :id");
        $stmt->bindParam(':id', $idMesa, PDO::PARAM_INT);
        $stmt->execute();
        $mesa = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$mesa) {
            $no_mesa = true; // Cambiar a true si no se encuentra la mesa
            exit();
        }
    } catch (PDOException $e) {
        echo "Error!" . $e->getMessage();
        exit();
    }
}

// Obtener las salas disponibles
$sqlSalas = "SELECT * FROM tbl_sala";
$stmtSalas = $conexion->prepare($sqlSalas);
$stmtSalas->execute();
$salas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numSillas = htmlspecialchars($_POST['num_sillas_mesa'] ?? '');
    $idSala = htmlspecialchars($_POST['id_sala'] ?? '');
    try {
        $stmtUpdate = $conexion->prepare("UPDATE tbl_mesa SET num_sillas_mesa = :num_sillas, id_sala = :id_sala WHERE id_mesa = :id_mesa");
        $stmtUpdate->bindParam(':num_sillas', $numSillas, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':id_sala', $idSala, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':id_mesa', $idMesa, PDO::PARAM_INT);

        if ($stmtUpdate->execute()) {
            $success = true;
        }
    } catch (PDOException $e) {
        echo "Error!" . $e->getMessage();
    }
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/users.css">
    <title>Editar Mesa</title>
</head>

<body>
    <div class="container mt-5">
        <h3 class="mb-4">Editar Mesa</h3>
        <form method="POST" id="edit_form">
            <div class="mb-3">
                <label for="num_sillas_mesa" class="form-label">Número de Sillas</label>
                <input type="number" name="num_sillas_mesa" id="num_sillas_mesa" class="form-control" value="<?= htmlspecialchars($mesa['num_sillas_mesa']) ?>">
                <span class="error-message" id="error_num_sillas"></span>
            </div>
            <div class="mb-3">
                <label for="id_sala" class="form-label">Sala</label>
                <select name="id_sala" id="id_sala" class="form-select" required>
                    <option value="">Seleccione una sala</option>
                    <?php foreach ($salas as $sala): ?>
                        <option value="<?= $sala['id_sala'] ?>" <?= $sala['id_sala'] == $mesa['id_sala'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sala['nombre_sala']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="error-message" id="error_id_sala"></span>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="../gestiones/gestionMesas.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <?php if ($success): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Mesa actualizada correctamente',
                showConfirmButton: true,
                didClose: () => {
                    window.location.href = '../gestiones/gestionMesas.php'; 
                }
            });
        </script>
    <?php endif; ?>
    <?php if($no_mesa): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'No se encontró la mesa',
                showConfirmButton: true
            });
        </script>
    <?php endif;?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/validaEditarMesa.js"></script>
</body>

</html>

