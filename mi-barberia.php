<?php
session_start();
include("conexion.php");

// Solo admin
if(!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Verificar rol
$query = $conn->query("SELECT rol FROM usuarios WHERE id=$usuario_id LIMIT 1");
$rol = $query->fetch_assoc()['rol'];
if($rol !== 'admin') {
    echo "⛔ Acceso denegado.";
    exit;
}

// Obtener datos de la barbería
$barberia = $conn->query("SELECT * FROM barberias WHERE admin_id=$usuario_id")->fetch_assoc();

// Actualizar datos
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $horario = $_POST['horario'];
    $descripcion = $_POST['descripcion'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];

    $sql = "UPDATE barberias SET nombre='$nombre', direccion='$direccion', telefono='$telefono',
            horario='$horario', descripcion='$descripcion', lat='$lat', lng='$lng' 
            WHERE admin_id=$usuario_id";

    if($conn->query($sql) === TRUE) {
        echo "<p class='alert alert-success'>✅ Datos actualizados correctamente.</p>";
        $barberia = $conn->query("SELECT * FROM barberias WHERE admin_id=$usuario_id")->fetch_assoc();
    } else {
        echo "<p class='alert alert-danger'>Error: " . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Barbería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- <link rel="stylesheet" href="css/style.css"> -->
</head>
<body>
<?php include("navbar.php"); ?>

<div class="container mt-5" style="max-width: 600px;">
    <h2 class="mb-4 text-center">Editar datos de mi barbería</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nombre Barbería:</label>
            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($barberia['nombre']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Dirección:</label>
            <input type="text" id="direccion" class="form-control" name="direccion" value="<?= htmlspecialchars($barberia['direccion']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono:</label>
            <input type="text" class="form-control" name="telefono" value="<?= htmlspecialchars($barberia['telefono']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Horario:</label>
            <input type="text" class="form-control" name="horario" value="<?= htmlspecialchars($barberia['horario']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción:</label>
            <textarea class="form-control" name="descripcion" rows="4"><?= htmlspecialchars($barberia['descripcion']) ?></textarea>
        </div>

        <!-- Coordenadas ocultas -->
        <input type="hidden" name="lat" id="lat" value="<?= $barberia['lat'] ?>">
        <input type="hidden" name="lng" id="lng" value="<?= $barberia['lng'] ?>">

        <!-- Mapa para editar ubicación -->
        <div id="map-edit" style="height: 300px;" class="mb-3"></div>

        <button type="submit" class="btn btn-primary mb-5 w-100">Actualizar barbería</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="js/editar-barberia.js"></script>
</body>
</html>
