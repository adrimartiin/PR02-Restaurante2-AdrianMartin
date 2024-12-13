<?php
session_start();
include_once '../db/conexion.php';

// Inicializar variables
$success = false;
$error = false;

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $idMesa = (int)$_GET['id'];
    try {
        $conexion->beginTransaction(); // Iniciar la transacción
        
        // Consulta para eliminar la mesa
        $stmtDelete = $conexion->prepare("DELETE FROM tbl_mesa WHERE id_mesa = :id");
        $stmtDelete->bindParam(':id', $idMesa, PDO::PARAM_INT);
        if ($stmtDelete->execute()) {
            $conexion->commit(); // Confirmar la transacción
            $success = true;
        } else {
            $conexion->rollBack(); // Deshacer la transacción en caso de error
            $error = true;
        }
    } catch (PDOException $e) {
        $conexion->rollBack(); 
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
    <title>Eliminar Mesa</title>
</head>

<body>
    <div class="container mt-5">
        <?php if ($error): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error al eliminar la mesa',
                    text: 'No se encontró la mesa especificada.',
                    showConfirmButton: true
                });
            </script>
        <?php elseif ($success): ?>
            <script>
                Swal.fire({
                title: 'Mesa eliminada!',
                text: 'La mesa se ha eliminado correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            }).then(() => window.location.href = '../gestiones/gestionMesas.php');
            </script>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


