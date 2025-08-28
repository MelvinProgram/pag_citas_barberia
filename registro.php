<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $user = $_POST['user'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Verificar que coincidan las contraseñas
    if ($password !== $confirm_password) {
        echo "❌ Las contraseñas no coinciden. Inténtalo de nuevo.";
    } else {
        // Encriptar la contraseña
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, user, apellidos, email, password) VALUES ('$nombre','$user','$apellidos','$email','$password_hash')";
        if ($conn->query($sql) === TRUE) {
            echo "✅ Registro exitoso. <a href='login.php'>Inicia sesión aquí</a>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- <link rel="stylesheet" href="css/style.css"> -->
</head>
<body>
  <?php include("navbar.php"); ?>
  
  <div class="container mt-5" style="max-width: 400px;">
    <h2 class="mb-4 text-center">Registro</h2>
    <form method="POST">
      <div class="mb-3">
        <input type="text" class="form-control" name="user" placeholder="Usuario" required>
      </div>
      <div class="mb-3">
        <input type="text" class="form-control" name="nombre" placeholder="Nombre" required>
      </div>
      <div class="mb-3">
        <input type="text" class="form-control" name="apellidos" placeholder="Apellidos" required>
      </div>
      <div class="mb-3">
        <input type="email" class="form-control" name="email" placeholder="Correo electrónico" required>
      </div>
      <div class="mb-3">
        <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
      </div>
      <div class="mb-3">
        <input type="password" class="form-control" name="confirm_password" placeholder="Confirmar contraseña" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Registrarse</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
