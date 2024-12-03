<?php
include_once '../db/conexion.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_sala'])) {
    $id_sala = $_POST['id_sala'];
    $fecha_hora_ocupacion = date("Y-m-d H:i:s");
    $id_camarero = $_SESSION['id_camarero']; // ID del camarero en sesión

    try {
        mysqli_autocommit($conn, false);
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

        $queryMesasLibres = "SELECT id_mesa FROM tbl_mesa WHERE id_sala = ? AND estado_mesa = 'libre'";
        $stmtMesasLibres = mysqli_prepare($conn, $queryMesasLibres);
        mysqli_stmt_bind_param($stmtMesasLibres, "i", $id_sala);
        mysqli_stmt_execute($stmtMesasLibres);
        $resultMesasLibres = mysqli_stmt_get_result($stmtMesasLibres);

        if (mysqli_num_rows($resultMesasLibres) > 0) {
            $queryActualizarMesa = "UPDATE tbl_mesa SET estado_mesa = 'ocupada' WHERE id_mesa = ?";
            $queryRegistrarOcupacion = "INSERT INTO tbl_ocupacion (id_mesa, id_camarero, fecha_hora_ocupacion) VALUES (?, ?, ?)";

            $stmtActualizarMesa = mysqli_prepare($conn, $queryActualizarMesa);
            $stmtRegistrarOcupacion = mysqli_prepare($conn, $queryRegistrarOcupacion);

            while ($mesa = mysqli_fetch_assoc($resultMesasLibres)) {
                $id_mesa = $mesa['id_mesa'];

                mysqli_stmt_bind_param($stmtActualizarMesa, "i", $id_mesa);
                mysqli_stmt_execute($stmtActualizarMesa);

                mysqli_stmt_bind_param($stmtRegistrarOcupacion, "iis", $id_mesa, $id_camarero, $fecha_hora_ocupacion);
                mysqli_stmt_execute($stmtRegistrarOcupacion);
            }

            mysqli_commit($conn);
            echo "Todas las mesas de la sala han sido reservadas correctamente.";
        } else {
            echo "No hay mesas libres en esta sala.";
        }

        mysqli_stmt_close($stmtMesasLibres);
        mysqli_stmt_close($stmtActualizarMesa);
        mysqli_stmt_close($stmtRegistrarOcupacion);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error en la transacción: " . $e->getMessage();
    }
}
?>

