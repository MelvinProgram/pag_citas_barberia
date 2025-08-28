<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("conexion.php");

$rol = null;
$usuario_id = null;
$nombre_barberia = null;

// Detectar página actual
$current_page = basename($_SERVER['PHP_SELF']);

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
    $query = $conn->query("SELECT id, rol FROM usuarios WHERE nombre='$usuario' LIMIT 1");
    if ($query && $query->num_rows > 0) {
        $data = $query->fetch_assoc();
        $rol = $data['rol'];
        $usuario_id = $data['id'];

        if($rol === 'admin') {
            // Obtener el nombre de la barbería
            $b = $conn->query("SELECT nombre FROM barberias WHERE admin_id=$usuario_id LIMIT 1");
            if($b && $b->num_rows > 0){
                $nombre_barberia = $b->fetch_assoc()['nombre'];
            }
        }
    }
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Barber Flow citas</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" 
            aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= $current_page === 'index.php' ? 'active' : '' ?>" href="index.php">Inicio</a>
        </li>

        <?php if(!$rol): ?>
          <!-- Visitante -->
          <li class="nav-item">
            <a class="nav-link <?= $current_page === 'login.php' ? 'active' : '' ?>" href="login.php">Iniciar Sesión</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $current_page === 'registro.php' ? 'active' : '' ?>" href="registro.php">Registro Usuario</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $current_page === 'registro-admin.php' ? 'active' : '' ?>" href="registro-admin.php">Registro de Barbería</a>
          </li>

        <?php elseif($rol === 'usuario'): ?>
          <!-- Usuario normal -->
          <li class="nav-item">
            <a class="nav-link <?= $current_page === 'mis-citas.php' ? 'active' : '' ?>" href="mis-citas.php">Mis Citas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Cerrar Sesión</a>
          </li>

        <?php elseif($rol === 'admin'): ?>
          <!-- Administrador -->
          <li class="nav-item">
            <a class="nav-link <?= $current_page === 'admin-citas.php' ? 'active' : '' ?>" href="admin-citas.php">Panel Admin</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $current_page === 'mi-barberia.php' ? 'active' : '' ?>" href="mi-barberia.php?id=<?= $usuario_id ?>">
              <?= $nombre_barberia ? $nombre_barberia : "Mi Barbería" ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Cerrar Sesión</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
