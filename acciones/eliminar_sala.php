<?php
session_start();
include_once '../db/conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

// Verificar que se haya recibido el ID de la sala
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../admin/dashboardAdmin.php");
    exit();
}

$id_sala = intval($_GET['id']);

try {
    // Iniciar una transacción para garantizar que todas las eliminaciones se realicen correctamente
    $conexion->beginTransaction();

    // Eliminar sillas asociadas con mesas de la sala
    $querySillas = "DELETE FROM tbl_silla WHERE id_mesa IN (SELECT id_mesa FROM tbl_mesa WHERE id_sala = :id_sala)";
    $stmtSillas = $conexion->prepare($querySillas);
    $stmtSillas->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmtSillas->execute();

    // Eliminar mesas asociadas con la sala
    $queryMesas = "DELETE FROM tbl_mesa WHERE id_sala = :id_sala";
    $stmtMesas = $conexion->prepare($queryMesas);
    $stmtMesas->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmtMesas->execute();

    // Eliminar reservas asociadas con las mesas de la sala
    $queryReservas = "DELETE FROM tbl_reserva WHERE id_mesa IN (SELECT id_mesa FROM tbl_mesa WHERE id_sala = :id_sala)";
    $stmtReservas = $conexion->prepare($queryReservas);
    $stmtReservas->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmtReservas->execute();

    // Eliminar ocupaciones asociadas con las mesas de la sala
    $queryOcupaciones = "DELETE FROM tbl_ocupacion WHERE id_mesa IN (SELECT id_mesa FROM tbl_mesa WHERE id_sala = :id_sala)";
    $stmtOcupaciones = $conexion->prepare($queryOcupaciones);
    $stmtOcupaciones->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmtOcupaciones->execute();

    // Finalmente, eliminar la sala
    $querySala = "DELETE FROM tbl_sala WHERE id_sala = :id_sala";
    $stmtSala = $conexion->prepare($querySala);
    $stmtSala->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmtSala->execute();

    // Confirmar la transacción
    $conexion->commit();

    // Redirigir con un mensaje de éxito
    header("Location: ../gestiones/gestionSalas.php");
    exit();
} catch (Exception $e) {
    // Si hay algún error, deshacer la transacción
    $conexion->rollBack();
    error_log($e->getMessage()); // Registrar el error para diagnóstico
    header("Location: ../admin/dashboardAdmin.php?error=No se pudo eliminar la sala");
    exit();
}



