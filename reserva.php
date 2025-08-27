<?php
session_start();
include("conexion.php");

// 1. Validar que el usuario esté logueado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
$usuario = $_SESSION['usuario'];
$usuario_id = $_SESSION['usuario_id'];

// 2. Validar que se pase el id de la barbería por GET
if (!isset($_GET['id'])) {
    die("Barbería no especificada.");
}
$barberia_id = $_GET['id'];

// 3. Obtener datos de la barbería y validar que exista
$result = $conn->query("SELECT * FROM barberias WHERE id=$barberia_id");
if ($result->num_rows == 0) {
    die("Barbería no encontrada.");
}
$barberia = $result->fetch_assoc();

// 4. Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    // Insertar la cita
    $sql = "INSERT INTO citas (usuario_id, barberia_id, fecha, hora) 
            VALUES ($usuario_id, $barberia_id, '$fecha', '$hora')";

    if ($conn->query($sql) === TRUE) {
        // Notificar al administrador
        $admin_email = "admin@tubarberia.com";
        $asunto = "Nueva cita reservada";
        $mensaje = "Se ha reservado una nueva cita:\n\n".
                   "Usuario: $usuario\n".
                   "Barbería: ".$barberia['nombre']."\n".
                   "Fecha: $fecha\n".
                   "Hora: $hora\n\n".
                   "Accede al panel de administración para más detalles.";

        $headers = "From: notificaciones@tubarberia.com\r\n".
                   "Reply-To: notificaciones@tubarberia.com\r\n".
                   "X-Mailer: PHP/" . phpversion();

        mail($admin_email, $asunto, $mensaje, $headers);

        // Confirmación al usuario
        $user_sql = $conn->query("SELECT email FROM usuarios WHERE id=$usuario_id");
        $email_usuario = $user_sql->fetch_assoc()['email'];

        $asunto_user = "Confirmación de tu cita en ".$barberia['nombre'];
        $mensaje_user = "Hola $usuario,\n\n".
                        "Tu cita ha sido reservada con éxito:\n".
                        "📍 Barbería: ".$barberia['nombre']."\n".
                        "📅 Fecha: $fecha\n".
                        "⏰ Hora: $hora\n\n".
                        "Gracias por confiar en nosotros.\n".
                        "Barbería Citas";

        mail($email_usuario, $asunto_user, $mensaje_user, $headers);

        echo "<p>✅ Cita reservada correctamente. Se ha enviado un correo de confirmación. 
              <a href='mis-citas.php'>Ver mis citas</a></p>";
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
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include("navbar.php"); ?>

  <div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4 text-center">Reservar cita en <?= htmlspecialchars($barberia['nombre']) ?></h2>

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

  <!-- JS bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

