<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

$usuario_creado = false; // Variable para comprobar si el usuario fue creado
$usuario_existente = false; // Variable para saber si el usuario ya existe

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe los datos del formulario
    $nombre_usuario = $_POST['nombre_usuario'];
    $nombre_real_usuario = $_POST['nombre_real_usuario'];
    $password_usuario = $_POST['password_usuario'];
    $id_rol = $_POST['id_rol'];

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
            // Encriptar la contraseña antes de guardarla
            $password_usuario = password_hash($password_usuario, PASSWORD_BCRYPT);

            // Insertar el nuevo usuario en la base de datos
            $stmt = $conexion->prepare("INSERT INTO tbl_usuario (nombre_usuario, nombre_real_usuario, password_usuario, id_rol) 
                                        VALUES (:nombre_usuario, :nombre_real_usuario, :password_usuario, :id_rol)");
            $stmt->bindParam(':nombre_usuario', $nombre_usuario);
            $stmt->bindParam(':nombre_real_usuario', $nombre_real_usuario);
            $stmt->bindParam(':password_usuario', $password_usuario);
            $stmt->bindParam(':id_rol', $id_rol);
            $stmt->execute();

            // Establecer la variable a true si el usuario fue creado exitosamente
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
        <form method="POST">
            <div class="mb-3">
                <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
            </div>
            <div class="mb-3">
                <label for="nombre_real_usuario" class="form-label">Nombre Real</label>
                <input type="text" class="form-control" id="nombre_real_usuario" name="nombre_real_usuario" required>
            </div>
            <div class="mb-3">
                <label for="password_usuario" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password_usuario" name="password_usuario" required>
            </div>
            <div class="mb-3">
                <label for="id_rol" class="form-label">Rol</label>
                <select class="form-select" id="id_rol" name="id_rol" required>
                    <?php
                    // Obtener roles disponibles
                    $stmt = $conexion->query("SELECT * FROM tbl_rol");
                    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($roles as $rol) {
                        echo "<option value='{$rol['id_rol']}'>{$rol['nombre_rol']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
            <a href="../admin/users.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="../js/dashboard.js"></script>
    <script src="../js/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        // Verificar si el usuario fue creado exitosamente
        <?php if ($usuario_creado): ?>
            Swal.fire({
                title: 'Usuario creado!',
                text: 'El usuario ha sido creado correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            }).then(function () {
                window.location.href = '../admin/users.php'; // Redirige a la página de usuarios
            });
        <?php endif; ?>

        // Verificar si el usuario ya existe
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

</html>