<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar sala</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
</head>

<body>
    <div class="navbar">
        <a href="../index.php">
            <img src="../img/icon.png" class="icon" alt="Icono">
        </a>
        <a href="./historial.php" class="right-link">Historial</a>
        <div class="user-info">
            <div class="dropdown">
                <i class="fas fa-caret-down" style="font-size: 16px; margin-right: 10px;"></i>
                <div class="dropdown-content">
                    <a href="../private/logout.php">Cerrar Sesión</a>
                </div>
            </div>
            <span><?php echo $_SESSION['nombre_usuario']; ?></span>
        </div>
        <div class="hamburger" id="hamburger-icon">
            &#9776;
        </div>
    </div>
    
    <form action="insertaCamareros.php" method="POST" id="formCamareros">
    <h2>Nuevo Camarero</h2>
            <label for="nombre">Nombre:</label>
            <br>
            <input type="text" name="nombre" id="nombre" placeholder="Nombre del camarero">
            <span id="error-nombre" class="error"></span>
            <br>
            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" id="apellido" placeholder="Apellido del camarero">
            <br>
            <span id="error-apellido" class="error"></span>
            <br>
            <label for="code">Código del Camarero</label>
            <br>
            <input type="text" name="code" id="code" placeholder="Código del camarero">
            <span id="error-code" class="error"></span>
            <br>
            <label for="psswd">Contraseña del Camarero:</label>
            <br>
            <input type="password" name="psswd" id="psswd" placeholder="Contraseña del camarero">
            <span id="error-psswd" class="error"></span>
            <br>
            <button type="submit" name="insert" id="insert">Añadir Camarero</button>
        </div>
    </form>
    <script src="validaFormCamareros.js"></script>
