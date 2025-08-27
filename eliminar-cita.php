<?php
session_start();
include("conexion.php");

// Verificar admin
if(!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}

$usuario = $_SESSION['usuario'];
$check = $conn->query("SELECT rol FROM usuarios WHERE nombre='$usuario' LIMIT 1");
$rol = $check->fetch_assoc()['rol'];

if($rol !== 'admin'){
  echo "⛔ Acceso denegado.";
  exit;
}

$id = $_GET['id'];
$conn->query("DELETE FROM citas WHERE id=$id");

header("Location: admin-citas.php");
exit;
?>
