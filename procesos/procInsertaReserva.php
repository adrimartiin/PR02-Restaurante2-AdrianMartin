    
    <!-- <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script> -->
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
            echo "Ya existe una reserva en esta franja horaria";
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

            // Comentar con algún profe hoy
            /* Cuando hago un header location conforme se ha hecho la reserva en gestion_salas.php 
            entra en el error del primer if*/
            // FALTAN SWEET ALERTS
            if ($insertStmt->execute()) {
                echo "Reserva realizada";
            } else {
                echo "Error al realizar la reserva";
            }
        }
    } catch (PDOException $e) {
        echo "Error en la conexión: " . $e->getMessage();
    }
    ?>
    


