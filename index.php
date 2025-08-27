<?php
session_start();
include("conexion.php");
$result = $conn->query("SELECT * FROM barberias");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Barberías Registradas</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php 
    include("navbar.php"); 
    $result = $conn->query("SELECT * FROM barberias"); 
  ?>

  <div class="container mt-5">
    <h1 class="mb-4 text-center">Bienvenido a Barber Flow</h1>

    <?php if(isset($_SESSION['usuario'])): ?>
      <p class="text-center">Hola, <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong></p>
    <?php endif; ?> 

    <h2 class="mb-4">Listado de Barberías</h2>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="col">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['nombre']) ?></h5>
              <p class="card-text"><b>Dirección:</b> <?= htmlspecialchars($row['direccion']) ?></p>
              <p class="card-text"><b>Teléfono:</b> <?= htmlspecialchars($row['telefono']) ?></p>
              <p class="card-text"><b>Horario:</b> <?= htmlspecialchars($row['horario']) ?></p>
              <p class="card-text"><?= htmlspecialchars($row['descripcion']) ?></p>
              <?php if(isset($_SESSION['usuario'])): ?>
                <a href="reserva.php?id=<?= $row['id'] ?>" class="btn btn-primary w-100">Reservar cita</a>
              <?php else: ?>
                <p class="text-center"><a href="login.php">Inicia sesión</a> para reservar</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <!-- JS bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

