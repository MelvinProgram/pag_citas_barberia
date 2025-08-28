<?php
session_start();
include("conexion.php");

// Validaciones y lógica igual que antes
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
$usuario = $_SESSION['usuario'];
$usuario_id = $_SESSION['usuario_id'];

if (!isset($_GET['id'])) die("Barbería no especificada.");
$barberia_id = $_GET['id'];

$result = $conn->query("SELECT * FROM barberias WHERE id=$barberia_id");
if ($result->num_rows == 0) die("Barbería no encontrada.");
$barberia = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    $sql = "INSERT INTO citas (usuario_id, barberia_id, fecha, hora) 
            VALUES ($usuario_id, $barberia_id, '$fecha', '$hora')";
    if ($conn->query($sql) === TRUE) {
        echo "<p>✅ Cita reservada correctamente. <a href='mis-citas.php'>Ver mis citas</a></p>";
    } else {
        echo "Error al reservar la cita: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reservar cita</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- <link rel="stylesheet" href="css/style.css"> -->
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body>
<?php include("navbar.php"); ?>

<div class="container mt-5" style="max-width: 600px;">
    <h2 class="mb-4 text-center">Reservar cita en <?= htmlspecialchars($barberia['nombre']) ?></h2>

    <!-- Mapa -->
    <?php if($barberia['lat'] && $barberia['lng']): ?>
    <div id="map" style="height: 300px;" class="mb-4" 
         data-lat="<?= $barberia['lat'] ?>" data-lng="<?= $barberia['lng'] ?>" 
         data-nombre="<?= htmlspecialchars($barberia['nombre']) ?>"></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Fecha:</label>
        <input type="date" class="form-control" name="fecha" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Hora:</label>
        <input type="time" class="form-control" name="hora" required>
      </div>

      <button type="submit" class="btn btn-primary w-100 mt-3">Confirmar cita</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- JS externo -->
<script src="./js/reserva-mapa.js"></script>
</body>
</html>


