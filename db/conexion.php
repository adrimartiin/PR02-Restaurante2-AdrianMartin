<?php
 $host = "localhost";
 $nombreBD = "bd_restaurante";
 $userBD = "root";
 $passBD  = "";
try{
    $conexion = new PDO("mysql:host=$host;dbname=$nombreBD", $userBD, $passBD);
} catch (PDOException $e){
    echo $e->getMessage();
}