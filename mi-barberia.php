<?php
session_start();
include("conexion.php");

// Solo admin
if(!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
$usuario_id = $_GET['id']; // Obtenido desde navbar
$query = $conn->query("SELECT rol FROM usuarios WHERE id=$usuario_id LIMIT 1");
$rol = $query->fetch_assoc()['rol'];

if($rol !== 'admin') {
    echo "⛔ Acceso denegado.";
    exit;
}

// Obtener datos de la barbería del admin
$barberia = $conn->query("SELECT * FROM barberias WHERE admin_id=$usuario_id")->fetch_assoc();

// Actualizar datos
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $horario = $_POST['horario'];
    $descripcion = $_POST['descripcion'];

    $sql = "UPDATE barberias SET nombre='$nombre', direccion='$direccion', telefono='$telefono', 
            horario='$horario', descripcion='$descripcion' WHERE admin_id=$usuario_id";

    if($conn->query($sql) === TRUE) {
        echo "<p>✅ Datos actualizados correctamente.</p>";
        // refrescar datos
        $barberia = $conn->query("SELECT * FROM barberias WHERE admin_id=$usuario_id")->fetch_assoc();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Barbería</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Opcional: tus estilos adicionales -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include("navbar.php"); ?>
    <h2>Editar datos de mi barbería</h2>
    <form method="POST">
        <label>Nombre Barbería:</label><br>
        <input type="text" name="nombre" value="<?= $barberia['nombre'] ?>" required><br><br>

        <label>Dirección:</label><br>
        <input type="text" name="direccion" value="<?= $barberia['direccion'] ?>"><br><br>

        <label>Teléfono:</label><br>
        <input type="text" name="telefono" value="<?= $barberia['telefono'] ?>"><br><br>

        <label>Horario:</label><br>
        <input type="text" name="horario" value="<?= $barberia['horario'] ?>"><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion"><?= $barberia['descripcion'] ?></textarea><br><br>

        <button type="submit" class="btn">Actualizar barbería</button>
    </form>

    <!-- JS bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
