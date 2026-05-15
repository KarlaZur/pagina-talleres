<?php
session_start();
include "conexion.php";

$nombre = $_POST["nombre"];
$email = $_POST["email"];
$password = $_POST["password"];
$edad = $_POST["edad"];
$genero = $_POST["genero"];
$escolaridad = $_POST["escolaridad"];
$procedencia = $_POST["procedencia"];
$institucion = $_POST["institucion"];
$telefono = $_POST["telefono"];

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios 
(nombre, email, password, edad, genero, escolaridad, procedencia, institucion, telefono) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
  "sssisssss",
  $nombre,
  $email,
  $password_hash,
  $edad,
  $genero,
  $escolaridad,
  $procedencia,
  $institucion,
  $telefono
);

if ($stmt->execute()) {
  $_SESSION["usuario_id"] = $stmt->insert_id;
  $_SESSION["usuario_email"] = $email;
  header("Location: index.php");
} else {
  echo "Error al registrar. Puede que el correo ya exista.";
}
?>