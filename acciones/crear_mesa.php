<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

$mesa_creada = false;
$mesa_existente = false;
$error_message = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_mesa = $_POST['id_mesa'];
    var_dump($id_mesa);
    die();
    
} else {
    echo "No hay post";
}
?>