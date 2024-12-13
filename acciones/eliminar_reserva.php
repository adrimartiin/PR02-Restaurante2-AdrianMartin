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
    $reservation_id = (int)$_GET['id'];
    try {
       
        $deleteReserva = $conexion->prepare("DELETE FROM tbl_reserva WHERE id_reserva = :id_reserva");
        $deleteReserva->bindParam(':id_reserva', $reservation_id, PDO::PARAM_INT);
        $deleteReserva->execute();
        
        $success = true;
    } catch (PDOException $e) {
        echo "Error!" . $e->getMessage();
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Eliminar Reserva</title>
</head>

<body>
    <div class="container mt-5">
        <?php if ($error): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error al eliminar la reserva',
                    text: 'No se encontró la reserva especificada o hubo un problema al eliminarla.',
                    showConfirmButton: true
                });
            </script>
        <?php elseif ($success): ?>
            <script>
                Swal.fire({
                    title: 'Reserva eliminada!',
                    text: 'La reserva y todas sus ocupaciones han sido eliminadas correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => window.location.href = '../public/historial.php');
            </script>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
