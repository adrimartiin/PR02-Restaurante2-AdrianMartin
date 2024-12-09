<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

// Inicializar variables de comprobación de creación de usuarios a false inicialmente
$usuario_creado = false;
$usuario_existente = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe los datos del formulario saneados
    $nombre_usuario = htmlspecialchars($_POST['nombre_usuario']);
    $nombre_real_usuario = htmlspecialchars($_POST['nombre_real_usuario']);
    $password_usuario = htmlspecialchars($_POST['password_usuario']);
    $id_rol = htmlspecialchars($_POST['id_rol']);

    // Comprobar si el nombre de usuario ya existe
    try {
        $stmt = $conexion->prepare("SELECT * FROM tbl_usuario WHERE nombre_usuario = :nombre_usuario OR nombre_real_usuario = :nombre_real_usuario");
        $stmt->bindParam(':nombre_usuario', $nombre_usuario);
        $stmt->bindParam(':nombre_real_usuario', $nombre_real_usuario);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Si existe un usuario con el mismo nombre de usuario o nombre real
            $usuario_existente = true;
        } else {
            $password_usuario = password_hash($password_usuario, PASSWORD_BCRYPT);

            // Insertar el nuevo usuario en la base de datos
            $stmt = $conexion->prepare("INSERT INTO tbl_usuario (nombre_usuario, nombre_real_usuario, password_usuario, id_rol) 
                                        VALUES (:nombre_usuario, :nombre_real_usuario, :password_usuario, :id_rol)");
            $stmt->bindParam(':nombre_usuario', $nombre_usuario);
            $stmt->bindParam(':nombre_real_usuario', $nombre_real_usuario);
            $stmt->bindParam(':password_usuario', $password_usuario);
            $stmt->bindParam(':id_rol', $id_rol);
            $stmt->execute();

            // Establecer la variable a true si el usuario se crea correctamente
            $usuario_creado = true;
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/users.css">
    <title>Crear Usuario</title>
</head>

<body>
    <div class="container mt-5">
        <h2>Crear Nuevo Usuario</h2>
        <form method="POST" id="insertForm">
            <div class="mb-3">
                <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario">
                <span class="error-message" id="error_nombre_usuario"></span>
            </div>
            <div class="mb-3">
                <label for="nombre_real_usuario" class="form-label">Nombre Real</label>
                <input type="text" class="form-control" id="nombre_real_usuario" name="nombre_real_usuario">
                <span class="error-message" id="error_nombre_real"></span>
            </div>
            <div class="mb-3">
                <label for="password_usuario" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password_usuario" name="password_usuario">
                <span class="error-message" id="error_pwd"></span>
            </div>
            <div class="mb-3">
                <label for="id_rol" class="form-label">Rol</label>
                <select class="form-select" id="id_rol" name="id_rol">
                    <?php
                    // Obtener roles disponibles
                    $stmt = $conexion->query("SELECT * FROM tbl_rol");
                    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($roles as $rol) {
                        echo "<option value='{$rol['id_rol']}'>{$rol['nombre_rol']}</option>";
                    }
                    ?>
                    <span class="error-message" id="error_rol"></span>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
            <a href="../admin/users.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/dashboard.js"></script>
    <script src="../js/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        <?php if ($usuario_creado): ?>
            Swal.fire({
                title: 'Usuario creado!',
                text: 'El usuario ha sido creado correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            }).then(function () {
                window.location.href = '../admin/users.php';
            });
        <?php endif; ?>

        <?php if ($usuario_existente): ?>
            Swal.fire({
                title: 'Error!',
                text: 'Ya existe este usuario',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        <?php endif; ?>
    </script>

</body>
<script src="../js/validaCrear.js"></script>

</html>