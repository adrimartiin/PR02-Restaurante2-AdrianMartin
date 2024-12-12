<?php
session_start();
include_once '../db/conexion.php';

// Verificar si el u    suario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

// Archivo de filtrado
include_once 'filtros.php';
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/dashboard.css">
    <title>Recursos</title>
</head>

<body>
    <div class="navbar">
        <a href="../index.php">
            <img src="../img/icon.png" class="icon" alt="Icono">
        </a>
        <div class="user-info">
            <div class="dropdown">
                <i class="fas fa-caret-down" style="font-size: 16px; margin-right: 10px;"></i>
                <div class="dropdown-content">
                    <a href="../private/logout.php">Cerrar Sesión</a>
                </div>
            </div>
            <span><?php echo $_SESSION['username']; ?></span>
        </div>
        <div class="hamburger" id="hamburger-icon">
            &#9776;
        </div>
    </div>
    <div class="mobile-nav" id="mobile-nav">
        <a href="./historial.php">Historial</a>
        <a href="#"><?php echo $_SESSION['username']; ?></a>
        <a href="../private/logout.php">Cerrar Sesión</a>
    </div>

    
    <h3 id="titulo">Gestión de Recursos</h3>
    <div class="options">
        <div class="option privadas">
            <h2>Salas</h2>
            <div class="button-container">
                <a href="../gestiones/gestionSalas.php" class="select-button">Seleccionar</a>
            </div>
        </div>
        <div class="option mesas">
            <h2>Mesas</h2>
            <div class="button-container">
                <a href="" class="select-button">Seleccionar</a>
            </div>
        </div>
    </div>

<script src="../js/dashboard.js"></script>
<script src="../js/navbar.js.js"></script>
</body>

</html>