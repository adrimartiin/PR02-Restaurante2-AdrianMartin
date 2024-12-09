<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

$success = false;  // Variable para verificar si la actualización fue exitosa

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    try {
        // Obtén los detalles del usuario
        $stmt = $conexion->prepare("SELECT * FROM tbl_usuario WHERE id_usuario = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo "Usuario no encontrado.";
            exit();
        }
    } catch (PDOException $e) {
        echo "Error al obtener datos: " . $e->getMessage();
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe los datos del formulario
    $nombre_usuario = $_POST['nombre_usuario'];
    $nombre_real_usuario = $_POST['nombre_real_usuario'];
    $password_usuario = $_POST['password_usuario'];
    $id_rol = $_POST['id_rol'];

    // Si se cambia la contraseña, la encriptamos
    if (!empty($password_usuario)) {
        $password_usuario = password_hash($password_usuario, PASSWORD_BCRYPT);
    } else {
        // Si la contraseña no se cambia, dejamos la original
        $password_usuario = $user['password_usuario'];
    }

    try {
        // Actualiza los datos del usuario
        $stmt = $conexion->prepare("UPDATE tbl_usuario SET nombre_usuario = :nombre_usuario, 
            nombre_real_usuario = :nombre_real_usuario, password_usuario = :password_usuario, id_rol = :id_rol 
            WHERE id_usuario = :id");
        $stmt->bindParam(':nombre_usuario', $nombre_usuario);
        $stmt->bindParam(':nombre_real_usuario', $nombre_real_usuario);
        $stmt->bindParam(':password_usuario', $password_usuario);
        $stmt->bindParam(':id_rol', $id_rol);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        $success = true;  // Si la actualización es exitosa, cambiamos la variable a true
    } catch (PDOException $e) {
        echo "Error al actualizar datos: " . $e->getMessage();
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container mt-5">
        <h2>Editar Usuario</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario"
                    value="<?php echo htmlspecialchars($user['nombre_usuario']); ?>">
            </div>
            <div class="mb-3">
                <label for="nombre_real_usuario" class="form-label">Nombre Real</label>
                <input type="text" class="form-control" id="nombre_real_usuario" name="nombre_real_usuario"
                    value="<?php echo htmlspecialchars($user['nombre_real_usuario']); ?>">
            </div>
            <div class="mb-3">
                <label for="password_usuario" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password_usuario" name="password_usuario"
                    placeholder="Dejar vacío si así se desea">
            </div>
            <div class="mb-3">
                <label for="id_rol" class="form-label">Rol</label>
                <select class="form-select" id="id_rol" name="id_rol">
                    <?php
                    // Obtener roles disponibles
                    $stmt = $conexion->query("SELECT * FROM tbl_rol");
                    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($roles as $rol) {
                        $selected = ($rol['id_rol'] == $user['id_rol']) ? "selected" : "";
                        echo "<option value='{$rol['id_rol']}' $selected>{$rol['nombre_rol']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="../admin/users.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <!-- Script SweetAlert para mostrar el mensaje de éxito -->
    <?php if ($success): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Usuario actualizado correctamente',
                showConfirmButton: true,
                didClose: () => {
                    window.location.href = '../admin/users.php'; 
                }
            });
        </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>