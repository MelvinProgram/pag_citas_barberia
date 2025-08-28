<?php
session_start();
include("conexion.php");

// Verificar si es admin
if (!isset($_SESSION['usuario_id'])) {
    die("Debes iniciar sesión primero.");
}

$usuario = $_SESSION['usuario'];
$usuario_id = $_SESSION['usuario_id'];

$check = $conn->query("SELECT rol FROM usuarios WHERE nombre='$usuario' LIMIT 1");
$rol = $check->fetch_assoc()['rol'];

if($rol !== 'admin'){
  echo "⛔ Acceso denegado. Solo administradores.";
  exit;
}

// Obtener todas las citas
$sql = "SELECT c.id, c.fecha, c.hora, u.nombre AS usuario, b.nombre AS barberia 
        FROM citas c
        JOIN usuarios u ON c.usuario_id = u.id
        JOIN barberias b ON c.barberia_id = b.id
        WHERE b.admin_id = $usuario_id
        ORDER BY c.fecha, c.hora";
$result = $conn->query($sql);

// 🔥 Obtener los usuarios que tienen citas en la barbería del admin
$sql_usuarios = "SELECT DISTINCT u.id, u.nombre, u.email 
                 FROM usuarios u
                 JOIN citas c ON u.id = c.usuario_id
                 JOIN barberias b ON c.barberia_id = b.id
                 WHERE b.admin_id = $usuario_id";
$result_usuarios = $conn->query($sql_usuarios);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Administrador</title>
  <!-- <link rel="stylesheet" href="css/style.css"> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include("navbar.php"); ?>
  <div class="container mt-4">
    <h2 class="mb-4">📋 Administración de Citas</h2>

    <?php if($result->num_rows > 0): ?>
      <div class="table-responsive mb-5">
        <table class="table table-striped table-bordered align-middle">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Usuario</th>
              <th>Barbería</th>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while($cita = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $cita['id'] ?></td>
              <td><?= htmlspecialchars($cita['usuario']) ?></td>
              <td><?= htmlspecialchars($cita['barberia']) ?></td>
              <td><?= $cita['fecha'] ?></td>
              <td><?= $cita['hora'] ?></td>
              <td>
                <a href="editar-cita.php?id=<?= $cita['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                <a href="eliminar-cita.php?id=<?= $cita['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que quieres eliminar esta cita?')">Eliminar</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info">No hay citas registradas.</div>
    <?php endif; ?>

    <!-- 🔥 Sección de Usuarios -->
    <h2 class="mb-4">👤 Usuarios Registrados en tu Barbería</h2>
    <?php if($result_usuarios->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
          <thead class="table-dark">
            <tr>
              <th>ID Usuario</th>
              <th>Nombre</th>
              <th>Email</th>
            </tr>
          </thead>
          <tbody>
            <?php while($usuario = $result_usuarios->fetch_assoc()): ?>
            <tr>
              <td><?= $usuario['id'] ?></td>
              <td><?= htmlspecialchars($usuario['nombre']) ?></td>
              <td><?= htmlspecialchars($usuario['email']) ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-warning">Aún no hay usuarios registrados en tu barbería.</div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
