<?php
include_once '../db/conexion.php'; // Incluye la conexión a la base de datos
$imagen_subida = false;
$ruta_imagen = "";

// Verificar si se sube un archivo
if (!empty($_FILES['imagen_sala']['name'])) {
    $directorio_destino = __DIR__ . '/../img/salas/'; // Ajusta esta ruta según tu estructura de directorios
    $nombre_archivo = basename($_FILES['imagen_sala']['name']);
    $ruta_relativa = "/img/salas/" . $nombre_archivo; // Ruta relativa correcta

    // Validar tipo de archivo
    $tipo_archivo = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
    $tipos_permitidos = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($tipo_archivo, $tipos_permitidos)) {
        // Mover archivo
        if (move_uploaded_file($_FILES['imagen_sala']['tmp_name'], $directorio_destino . $nombre_archivo)) {
            $imagen_subida = true;

            try {
                // Verificar si la conexión es correcta
                if ($conexion) {
                    // Preparar la consulta
                    $sql = "INSERT INTO tbl_sala (imagen_sala) VALUES (:imagen_sala)";
                    $stmt = $conexion->prepare($sql);

                    // Vincular parámetro
                    $stmt->bindParam(':imagen_sala', $ruta_relativa, PDO::PARAM_STR);

                    // Ejecutar consulta
                    if ($stmt->execute()) {
                        echo "La imagen se ha subido y guardado correctamente.";
                    } else {
                        echo "Error al guardar la imagen en la base de datos.";
                    }
                } else {
                    echo "No se pudo establecer la conexión a la base de datos.";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error al mover la imagen.";
        }
    } else {
        echo "Formato de archivo no válido. Solo se permiten JPG, JPEG, PNG y GIF.";
    }
}
?>