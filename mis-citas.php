<?php
session_start();
include("conexion.php");

if(!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}

$usuario = $_SESSION['usuario'];

// Obtener id del usuario
$usuario_sql = $conn->query("SELECT id FROM usuarios WHERE nombre='$usuario'");
$usuario_id = $usuario_sql->fetch_assoc()['id'];

$sql = "SELECT c.id, c.fecha, c.hora, b.nombre AS barberia 
        FROM citas c 
        JOIN barberias b ON c.barberia_id = b.id 
        WHERE c.usuario_id=$usuario_id 
        ORDER BY c.fecha, c.hora";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Citas</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- <link rel="stylesheet" href="css/style.css"> -->
</head>
<body>
  <?php include("navbar.php"); ?>

  <div class="container mt-5">
    <h2 class="mb-4 text-center">Mis citas reservadas</h2>

    <?php if($result->num_rows > 0): ?>
      <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php while($cita = $result->fetch_assoc()): ?>
          <div class="col">
            <div class="card h-100 border-primary">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($cita['barberia']) ?></h5>
                <p class="card-text"><b>Fecha:</b> <?= $cita['fecha'] ?></p>
                <p class="card-text"><b>Hora:</b> <?= $cita['hora'] ?></p>

                <div class="d-flex justify-content-start mt-3">
                   <a href="editar-cita.php?id=<?= $cita['id'] ?>" class="btn btn-sm btn-warning">Editar</a> <!-- Tengo que crear el archivo editar-cita.php para poder editar la cita-->
                  <a href="eliminar-cita.php?id=<?= $cita['id'] ?>"class="btn btn-sm btn-danger" 
                     onclick="return confirm('¿Seguro que quieres cancelar esta cita?');">
                     Cancelar
                  </a>
                </div>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
      <div class="mt-4 text-center">
        <a href="index.php" class="btn btn-secondary">Volver</a>
      </div>
    <?php else: ?>
      <div class="alert alert-info text-center">
        No tienes citas reservadas.
      </div>
      <div class="text-center mt-3">
        <a href="index.php" class="btn btn-primary">Ver barberías</a>
      </div>
    <?php endif; ?>
  </div>

  <!-- JS bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


