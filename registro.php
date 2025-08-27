<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre','$email','$password')";
    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso. <a href='login.php'>Inicia sesión aquí</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Usuario</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Opcional: tus estilos adicionales -->
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php
    include("navbar.php"); // <<--- aquí cargamos la barra
  ?>
  <div class="container mt-5" style="max-width: 400px;">
  <h2 class="mb-4 text-center">Registro</h2>
  <form method="POST">
    <div class="mb-3">
      <input type="text" class="form-control" name="nombre" placeholder="Nombre" required><br>
    </div>
    <div class="mb-3">
      <input type="email" class="form-control" name="email" placeholder="Correo electrónico" required><br>
    </div>
    <div class="mb-3">
      <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Registrarse</button>
  </form>
  </div>
  <!-- JS bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
