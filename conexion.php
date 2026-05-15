<?php
$host = "db";
$user = "root";
$password = "root";
$db = "talleres_db";

$conexion = new mysqli($host, $user, $password, $db);

if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>