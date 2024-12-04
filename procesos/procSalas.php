<?php
 /* Este archivo tendrá que tener: 
 1) un select de todas las salas segun el tipo de sala que es
 2) pintar lo correspondiente a cada sala (div con botón y eso apoyarse del código de los choose antiguos)
 3) cuando se pulse el botón, debe redireccionar a la sala correspondiente con los parámetros necesarios (id_sala y tipo_sala)
 */

 
 session_start();
 include_once '../db/conexion.php';
 
 // Verifica si el usuario está logueado
 if (!isset($_SESSION['loggedin'])) {
     header("Location: ../index.php");
     exit();
 }

 // Recibir por get y saneada la sala seleccionada desde el dashboard
$sala_seleccionada = $_GET

 ?>
 
