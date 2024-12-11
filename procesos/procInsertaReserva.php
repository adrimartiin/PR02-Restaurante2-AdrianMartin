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
        $sala_id = htmlspecialchars($_POST['id_sala']); 
        $turno = htmlspecialchars($_POST['nombre_turno']);
        
        

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
            // Si existe, error de hay reserva mediante sweet alert
            $_SESSION['hay_reserva'] = true;
            ?>
             <form action="procReserva.php" method="post" name="regreso">
                    <input type="hidden" name="nombre_turno" value="<? echo $turno; ?>" />
                    <input type="hidden" name="mesa_id" value="<? echo $mesa_id; ?>" /> 
                    <input type="hidden" name="id_sala" value="<? echo $sala_id; ?>" /> 
                </form>
                <script>
                    document.regreso.submit();  
                </script>
            <?php
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

            // FALTAN SWEET ALERTS
            if ($insertStmt->execute()) {
                $_SESSION['reserva_done'] = true;
                ?>
                <form action="procDashboard.php" method="post" name="regreso">
                    <input type="hidden" name="mesa_id" value="<? echo $mesa_id; ?>" /> 
                    <input type="hidden" name="sala" value="<? echo $sala_id; ?>" /> 
                </form>
                <script>
                    document.regreso.submit();  
                </script>
                <?php
            } else {
                echo "Error al realizar la reserva";
            }
        }
    } catch (PDOException $e) {
        echo "Error en la conexión: " . $e->getMessage();
    }
    ?>
    


