<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        if (password_verify($password, $usuario['password'])) {
          $_SESSION['usuario'] = $usuario['nombre'];
          $_SESSION['usuario_id'] = $usuario['id']; // 🔥 Guardamos el ID en la sesión
          header("Location: index.php");
          exit;
      } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
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
  <h2 class="mb-4 text-center">Iniciar Sesión</h2>
  <form method="POST">
    <div class="mb-3">
      <input type="email" class="form-control" name="email" placeholder="Correo electrónico" required>
    </div>
    <div class="mb-3">
      <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
    </div>
      <button type="submit" class="btn btn-primary w-100">Ingresar</button>
    </form>
    <p class="mt-3 text-center">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
  </div>
  <!-- JS bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
