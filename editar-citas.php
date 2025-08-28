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

$id = $_POST['id'];
$cita = $conn->query("SELECT * FROM citas WHERE id=$id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    $sql = "UPDATE citas SET fecha='$fecha', hora='$hora' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin-citas.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Cita</title>
  <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Opcional: tus estilos adicionales -->
    <!-- <link rel="stylesheet" href="css/style.css"> -->
</head>
<body>
  <?php include("navbar.php"); ?>
  <h2>Editar cita #<?= $id ?></h2>

  <form method="POST">
    <label>Fecha:</label><br>
    <input type="date" name="fecha" value="<?= $cita['fecha'] ?>" required><br><br>

    <label>Hora:</label><br>
    <input type="time" name="hora" value="<?= $cita['hora'] ?>" required><br><br>

    <button type="submit" class="btn">Actualizar cita</button>
  </form>
  <!-- JS bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
