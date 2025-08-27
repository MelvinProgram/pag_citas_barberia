<?php
    session_start();
    include("conexion.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $barberia = $_POST['barberia'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $horario = $_POST['horario'];
        $descripcion = $_POST['descripcion'];

        // Registrar admin en usuarios
        $sql = "INSERT INTO usuarios (nombre, email, password, rol) 
                VALUES ('$nombre', '$email', '$password', 'admin')";

        if ($conn->query($sql) === TRUE) {
            $admin_id = $conn->insert_id;

            // Registrar barbería asociada
            $sql2 = "INSERT INTO barberias (nombre, direccion, telefono, horario, descripcion, admin_id)
                    VALUES ('$barberia', '$direccion', '$telefono', '$horario', '$descripcion', $admin_id)";

            if ($conn->query($sql2) === TRUE) {
                echo "<p>✅ Registro completado. Ahora puedes iniciar sesión como administrador.</p>";
                echo "<a href='login.php'>Ir a Login</a>";
            } else {
                echo "Error al registrar barbería: " . $conn->error;
            }
        } else {
            echo "Error al registrar usuario: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro Administrador</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Opcional: tus estilos adicionales -->
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include("navbar.php"); ?>

  <div class="container mt-5" style="max-width: 600px;">
    <h2 class="mb-4 text-center">Registro de Administrador de Barbería</h2>
    
    <form method="POST">
      <h4 class="mb-3">Datos del Administrador</h4>
      
      <div class="mb-3">
        <label class="form-label">Nombre:</label>
        <input type="text" class="form-control" name="nombre" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email:</label>
        <input type="email" class="form-control" name="email" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Contraseña:</label>
        <input type="password" class="form-control" name="password" required>
      </div>

      <h4 class="mb-3 mt-4">Datos de la Barbería</h4>

      <div class="mb-3">
        <label class="form-label">Nombre Barbería:</label>
        <input type="text" class="form-control" name="barberia" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Dirección:</label>
        <input type="text" class="form-control" name="direccion">
      </div>

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

      <button type="submit" class="btn btn-primary w-100 mt-3">Registrar Administrador</button>
    </form>
  </div>

  <!-- JS bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

