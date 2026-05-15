<?php
session_start();
include "conexion.php";

$email = $_POST["email"];
$password = $_POST["password"];

$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
  $usuario = $resultado->fetch_assoc();

  if (password_verify($password, $usuario["password"])) {
    $_SESSION["usuario_id"] = $usuario["id"];
    $_SESSION["usuario_email"] = $usuario["email"];
    header("Location: index.php");
  } else {
    echo "Contraseña incorrecta.";
  }
} else {
  echo "El usuario no existe.";
}
?>