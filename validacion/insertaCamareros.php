<?php
include_once '../db/conexion.php';

// ==== CUANDO SE LE DE AL BOTÓN HACE INSERT EN LA TABLA CAMAREROS CON LO QUE SE HAYA INTRODUCIDO DEL FORM
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_camarero_insert = mysqli_real_escape_string($conn, $_POST['nombre'] );
    $apellido_camarero_insert = mysqli_real_escape_string($conn, $_POST['apellido'] );
    $code_camarero_insert = mysqli_real_escape_string($conn, $_POST['code'] );
    $passwd_camarero_insert = mysqli_real_escape_string($conn, password_hash($_POST['psswd'], PASSWORD_BCRYPT)); // Cifrado de contraseña

    if ($conn) {
        $sql_check = "SELECT * FROM tbl_camarero WHERE nombre_camarero = ? OR apellido_camarero = ? OR codigo_camarero = ?";
        $stmt_check = mysqli_prepare($conn, $sql_check);

        if ($stmt_check) {
            mysqli_stmt_bind_param($stmt_check, "sss", $nombre_camarero_insert, $apellido_camarero_insert, $code_camarero_insert);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);

            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                echo "<p style='color: red;'>Ya existe un camarero con el mismo nombre, apellido o código.</p>";
                echo "<a href='formCamareros.php'>Volver a Introducir</a>";
                exit();
            }

            mysqli_stmt_close($stmt_check);
        } else {
            echo "Error al preparar la consulta de validación: " . mysqli_error($conn);
            exit();
        }

        $sql = "INSERT INTO tbl_camarero (nombre_camarero, apellido_camarero, codigo_camarero, password_camarero) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssss", $nombre_camarero_insert, $apellido_camarero_insert, $code_camarero_insert, $passwd_camarero_insert);

            if (mysqli_stmt_execute($stmt)) {
                header('Location: pagina_camareros.php');
                exit();
            } else {
                echo "Error al ejecutar la consulta: " . mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "Error al preparar la consulta de inserción: " . mysqli_error($conn);
        }
    } else {
        echo "Error de conexión a la base de datos.";
    }

    // Cerrar la conexión
    mysqli_close($conn);
}
?>
