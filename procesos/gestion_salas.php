<?php
session_start();
include_once '../db/conexion.php';
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/gestion_mesas">
    <title>Mesas</title>
</head>
<body>
<div class="navbar">
        <a href="../index.php">
            <img src="../img/icon.png" class="icon" alt="Icono">
        </a>
        <div class="user-info">
            <div class="dropdown">
                <i class="fas fa-caret-down" style="font-size: 16px; margin-right: 10px;"></i>
                <div class="dropdown-content">
                    <a href="../private/logout.php">Cerrar Sesión</a>
                </div>
            </div>
            <span><?php echo $_SESSION['username']; ?></span>
        </div>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sala'])) {
        // Recojo el campo del formulario saneado
        $sala = intval(htmlspecialchars($_POST['sala']));
    
        // ===== QUERY PARA VER LA CAPACIDAD TOTAL DE LA SALA SELECCIONADA =====
        try {
            $sqlCapacidad = $conexion->prepare("SELECT capacidad_total FROM tbl_sala WHERE id_sala = :id_sala");
            $sqlCapacidad->bindParam(':id_sala', $sala);
            $sqlCapacidad->execute();
            $res = $sqlCapacidad->fetch(PDO::FETCH_ASSOC);
    
            if (isset($res)) {
                echo "<h2 style='text-align: center;'>Capacidad total de la sala: " . htmlspecialchars($res['capacidad_total']) . "</h2>";
            } else {
                echo "<h2 style='text-align: center;'>No se encontró capacidad para la sala seleccionada.</h2>";
            }
        } catch (PDOException $e) {
            echo "Error!" . $e->getMessage();
        }
    
        // ===== QUERY PARA MOSTRAR TODAS LAS MESAS QUE TIENE UNA SALA =====
        try {
            // Preparamos la consulta
            $sqlMesas = $conexion->prepare("SELECT nombre_sala, num_mesas_sala FROM tbl_sala WHERE id_sala = :id_sala");
    
            // Enlazamos el parámetro de la sala
            $sqlMesas->bindParam(':id_sala', $sala, PDO::PARAM_INT);
    
            // Ejecutamos la consulta
            $sqlMesas->execute();
    
            // Verificamos si hay resultados
            if ($sqlMesas->rowCount() > 0) {
                echo "<div class='container mt-4'>";
                // Iteramos sobre los resultados y los mostramos
                while ($row = $sqlMesas->fetch(PDO::FETCH_ASSOC)) {
                    $nombre_sala = htmlspecialchars($row['nombre_sala']);
                    $num_mesas_sala = htmlspecialchars($row['num_mesas_sala']);
                    
                    echo "<div class='card mb-3' style='max-width: 18rem;'>
                            <div class='card-body'>
                                <h5 class='card-title'><i class='fas fa-table'></i> Sala: $nombre_sala</h5>
                                <p class='card-text'><strong>Número de mesas:</strong> $num_mesas_sala</p>
                                <a href='#' class='btn btn-primary'>Detalles</a>
                            </div>
                        </div>";
                }
                echo "</div>";
            } else {
                echo "<h2 style='text-align: center;'>No se encontraron mesas para la sala seleccionada.</h2>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    
    } else {
        echo 'Error! No hay sala seleccionada.';
    }
    ?>
</body>
</html>

