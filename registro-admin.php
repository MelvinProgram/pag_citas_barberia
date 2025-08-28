<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $user = $_POST['user'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $barberia = $_POST['barberia'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $horario = $_POST['horario'];
    $descripcion = $_POST['descripcion'];
    $lat = $_POST['lat'] ?? null;
    $lng = $_POST['lng'] ?? null;

    if ($password !== $confirm_password) {
        $error = "❌ Las contraseñas no coinciden. Intenta de nuevo.";
    } else {
        $checkEmail = $conn->query("SELECT id FROM usuarios WHERE email='$email' LIMIT 1");
        if ($checkEmail->num_rows > 0) {
            $error = "❌ Este correo ya está registrado. Intenta con otro.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            // Insertar en la tabla usuarios
            $sql = "INSERT INTO usuarios (nombre, user, apellidos, email, password, rol) 
                    VALUES ('$nombre', '$user', '$apellidos', '$email', '$password_hash', 'admin')";
            if ($conn->query($sql) === TRUE) {
                $admin_id = $conn->insert_id;
                $sql2 = "INSERT INTO barberias (nombre, direccion, telefono, horario, descripcion, admin_id, lat, lng)
                         VALUES ('$barberia', '$direccion', '$telefono', '$horario', '$descripcion', $admin_id, '$lat', '$lng')";
                if ($conn->query($sql2) === TRUE) {
                    $success = "✅ Registro completado. Ahora puedes iniciar sesión como administrador.";
                } else {
                    $error = "Error al registrar barbería: " . $conn->error;
                }
            } else {
                $error = "Error al registrar usuario: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- <link rel="stylesheet" href="css/style.css"> -->
</head>
<body>
<?php include("navbar.php"); ?>

<div class="container mt-5" style="max-width: 700px;">
    <h2 class="mb-4 text-center">Registro de Administrador de Barbería</h2>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?= $success ?> <a href="login.php">Ir a Login</a></div>
    <?php endif; ?>

    <form method="POST" class="mb-3">
        <h4 class="mb-3">Datos del Administrador</h4>

        <div class="mb-3">
            <label class="form-label">Usuario:</label>
            <input type="text" class="form-control" name="user" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" class="form-control" name="nombre" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Apellidos:</label>
            <input type="text" class="form-control" name="apellidos" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña:</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirmar Contraseña:</label>
            <input type="password" class="form-control" name="confirm_password" required>
        </div>

        <h4 class="mb-3 mt-4">Datos de la Barbería</h4>
        <div class="mb-3">
            <label class="form-label">Nombre Barbería:</label>
            <input type="text" class="form-control" name="barberia" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Dirección:</label>
            <input type="text" class="form-control" name="direccion" id="direccion">
        </div>

        <!-- Mapa -->
        <div id="map" style="height: 300px;" class="mb-3"></div>
        <input type="hidden" name="lat" id="lat">
        <input type="hidden" name="lng" id="lng">

        <div class="mb-3">
            <label class="form-label">Teléfono:</label>
            <input type="text" class="form-control" name="telefono">
        </div>
        <div class="mb-3">
            <label class="form-label">Horario:</label>
            <input type="text" class="form-control" name="horario">
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción:</label>
            <textarea class="form-control" name="descripcion" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-3 mb-3">Registrar Administrador</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="./js/main.js"></script>
</body>
</html>
