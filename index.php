<?php
session_start();
include("conexion.php");

// Verificar rol y barberías a mostrar
if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $check = $conn->query("SELECT rol FROM usuarios WHERE id='$usuario_id' LIMIT 1");
    $rol = $check->fetch_assoc()['rol'];

    if ($rol === 'admin') {
        $result = $conn->query("SELECT * FROM barberias WHERE admin_id = $usuario_id");
    } else {
        $result = $conn->query("SELECT * FROM barberias");
    }
} else {
    $result = $conn->query("SELECT * FROM barberias");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Barberías Registradas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- <link rel="stylesheet" href="./css/style.css"> -->
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body>
  <?php include("navbar.php"); ?>

  <div class="container mt-5">
    <h1 class="mb-4 text-center">Bienvenido a Barber Flow</h1>

    <?php if(isset($_SESSION['usuario'])): ?>
      <p class="text-center">Hola, <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong></p>
    <?php endif; ?> 

    <h2 class="mb-4">Listado de Barberías</h2>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <div class="col">
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($row['nombre']) ?></h5>
                <p class="card-text"><b>Dirección:</b> <?= htmlspecialchars($row['direccion']) ?></p>
                <p class="card-text"><b>Teléfono:</b> <?= htmlspecialchars($row['telefono']) ?></p>
                <p class="card-text"><b>Horario:</b> <?= htmlspecialchars($row['horario']) ?></p>
                <p class="card-text"><?= htmlspecialchars($row['descripcion']) ?></p>

                <!-- Mapa de la barbería -->
                <?php if($row['lat'] && $row['lng']): ?>
                  <div class="mb-3" style="height: 200px;" id="map-<?= $row['id'] ?>"
                       data-lat="<?= $row['lat'] ?>"
                       data-lng="<?= $row['lng'] ?>"
                       data-nombre="<?= htmlspecialchars($row['nombre']) ?>">
                  </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['usuario']) && $rol !== 'admin'): ?>
                  <a href="reserva.php?id=<?= $row['id'] ?>" class="btn btn-primary w-100">Reservar cita</a>
                <?php elseif(!isset($_SESSION['usuario'])): ?>
                  <p class="text-center"><a href="login.php">Inicia sesión</a> para reservar</p>
                <?php else: ?>
                  <p class="text-center text-muted">Eres administrador de esta barbería</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="alert alert-warning">No hay barberías registradas.</div>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <!-- JS externo -->
  <script src="./js/index-mapa.js"></script>
</body>
</html>
