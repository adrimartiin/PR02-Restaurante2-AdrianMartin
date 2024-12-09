<?php
session_start();
include '../db/conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recogida de campos saneados con htmlspecialchars + trim
    $nombre_usuario = htmlspecialchars(trim($_POST['nombre_usuario']));
    $pwd = htmlspecialchars(trim($_POST['pwd']));

    $_SESSION['username'] = $nombre_usuario;
    $_SESSION['pwd'] = $pwd;

    if (empty($nombre_usuario) || empty($pwd)) {
        $_SESSION['error'] = "Ambos campos son obligatorios.";
        header("Location: ../index.php");
        exit();
    }

    try {
        $sql = "SELECT * FROM tbl_usuario WHERE nombre_usuario = :nombre_usuario";
        $stmt = $conexion->prepare($sql); 
        $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($pwd, $usuario['password_usuario'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
                $_SESSION['rol_id'] = $usuario['id_rol'];

                unset($_SESSION['nombre_usuario']);
                unset($_SESSION['pwd']);
                unset($_SESSION['error']);

                
                
                if ($usuario['nombre_usuario'] === 'admin') {
                    header("Location: ../admin/dashboardAdmin.php");
                } else {
                    header("Location: ../procesos/procDashboard.php");
                }
                exit();
            } else {
                $_SESSION['error'] = "Los datos introducidos son incorrectos.";
                header("Location: ../index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Los datos introducidos son incorrectos.";
            header("Location: ../index.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error en la consulta: " . $e->getMessage();
        header("Location: ../index.php");
        exit();
    }
} else {
    header("Location: ../public/login.php");
    exit();
}



