<?php
session_start();
include_once '../db/conexion.php';

/*
En este archivo se hará el formulario de la reserva con dia de la reserva, hora de la reserva y número de personas que hay
===== COSAS A TENER EN CUENTA =====
- Validaciones JS del formulario de la reserva
- Verificar que no haya una reserva de esa mesa en esa misma hora
     - Si no hay ninguna reserva pues se hace la reserva y se hace update del estado de la mesa de libre a reservada
- Verificar que el número de personas no supere al número de sillas que hay en las mesas
*/
?>