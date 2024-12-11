<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    try {
        // Inicia la transacción
        $conexion->beginTransaction();

        // Eliminar ocupaciones relacionadas con el usuario
        $stmt = $conexion->prepare("DELETE FROM tbl_ocupacion WHERE id_usuario = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

  
        // Eliminar el usuario de la base de datos
        $stmt = $conexion->prepare("DELETE FROM tbl_usuario WHERE id_usuario = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        // Confirma la transacción
        $conexion->commit();

        header("Location: ../admin/users.php?success=1");
        exit();

    } catch (PDOException $e) {
        $conexion->rollBack();
        echo "Error al eliminar usuario y registros relacionados: " . $e->getMessage();
    }
} else {
    echo "No se ha proporcionado un ID de usuario.";
    exit();
}
?>

