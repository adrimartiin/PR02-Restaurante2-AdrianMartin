<?php

include_once '../db/conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

// Comprobar si la solicitud es POST y si el parámetro 'sala' está presente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sala'])) {
    $sala = $_POST['sala'];

    try {
        // Conectar a la base de datos con PDO
        $stmt = $conexion->prepare("SELECT id_sala FROM tbl_sala WHERE nombre_sala = ?");
        $stmt->execute([$sala]);

        // Obtener el ID de la sala
        $id_sala = null;
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id_sala = $row['id_sala'];

            // Consultar las mesas asociadas a la sala
            $stmtMesas = $conexion->prepare("SELECT * FROM tbl_mesa WHERE id_sala = ?");
            $stmtMesas->execute([$id_sala]);
            $mesas = $stmtMesas->fetchAll(PDO::FETCH_ASSOC);

            // Mostrar las mesas
            if ($mesas) {
                foreach ($mesas as $mesa) {
                    // Aquí podrías hacer algo con los datos de las mesas, por ejemplo, mostrarlas.
                }
            } else {
                echo "No hay mesas en esta sala.";
            }

        } else {
            echo "No se ha encontrado ninguna sala con el nombre especificado.";
        }

        // Consultar la capacidad total de la sala
        $stmtCapacidad = $conexion->prepare("SELECT capacidad_total FROM tbl_sala WHERE nombre_sala = ?");
        $stmtCapacidad->execute([$sala]);

        if ($row = $stmtCapacidad->fetch(PDO::FETCH_ASSOC)) {
            echo "<h2 style='text-align: center;'>Capacidad total de la sala: " . $row['capacidad_total'] . "</h2>";
        } else {
            echo "No se encontró la sala especificada.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

