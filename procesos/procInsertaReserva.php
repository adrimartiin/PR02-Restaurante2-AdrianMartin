<?php
session_start();
include_once '../db/conexion.php';

try {
    // Recoger los campos saneados del formulario
    $hora_inicio = htmlspecialchars($_POST['hora_inicio']);
    $hora_fin = htmlspecialchars($_POST['hora_final']);
    $dia_reserva = htmlspecialchars($_POST['dia_reserva']);
    $num_personas = htmlspecialchars($_POST['num_personas']);
    $mesa_id = htmlspecialchars($_SESSION['id_mesa']);

    // Query con placeholders con nombre
    $query = "
        SELECT * 
        FROM tbl_reserva 
        WHERE id_mesa = :mesa_id
          AND fecha_reserva = :fecha_reserva
          AND (
            (hora_inicio < :hora_fin AND hora_fin > :hora_inicio) OR 
            (hora_inicio < :hora_inicio AND hora_fin > :hora_fin) OR 
            (hora_inicio >= :hora_inicio AND hora_fin <= :hora_fin)
          )
    ";

    // Preparar la consulta
    $stmt = $conexion->prepare($query);

    // Asignar valores a los placeholders
    $stmt->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_reserva', $dia_reserva, PDO::PARAM_STR);
    $stmt->bindParam(':hora_inicio', $hora_inicio, PDO::PARAM_STR);
    $stmt->bindParam(':hora_fin', $hora_fin, PDO::PARAM_STR);

    // Ejecutar la consulta
    $stmt->execute();

    // Verificar si hay resultados
    if ($stmt->rowCount() > 0) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ya existe una reserva para esta mesa en el horario seleccionado.',
            });
        </script>
    ";
    } else {
        // Insertar la nueva reserva en tbl_reserva
        $insertQuery = "
       INSERT INTO tbl_reserva (id_mesa, fecha_reserva, hora_inicio, hora_fin)
       VALUES (:mesa_id, :fecha_reserva, :hora_inicio, :hora_fin)
   ";

        // Preparar la consulta de inserción
        $insertStmt = $conexion->prepare($insertQuery);

        // Asignar valores a los placeholders
        $insertStmt->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
        $insertStmt->bindParam(':fecha_reserva', $dia_reserva, PDO::PARAM_STR);
        $insertStmt->bindParam(':hora_inicio', $hora_inicio, PDO::PARAM_STR);
        $insertStmt->bindParam(':hora_fin', $hora_fin, PDO::PARAM_STR);

        // Ejecutar la inserción
        $insertStmt->execute();

        echo "
       <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
       <script>
           Swal.fire({
               icon: 'success',
               title: 'Reserva realizada',
               text: 'La reserva se ha realizado correctamente.'
           }).then(() => {
               window.location.href = 'gestion_salas.php';
           });
       </script>
   ";
    }

} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}

