<?php
session_start();
include "conexion.php";

if (!isset($_SESSION["usuario_id"])) {
  header("Location: index.php");
  exit();
}

$usuario_id = $_SESSION["usuario_id"];
$taller_id = $_POST["taller_id"];

$sql = "DELETE FROM inscripciones WHERE usuario_id = ? AND taller_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $usuario_id, $taller_id);
$stmt->execute();

header("Location: index.php");
?>