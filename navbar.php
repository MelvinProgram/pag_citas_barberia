<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("conexion.php");

$rol = null;
$usuario_id = null;
$nombre_barberia = null;

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
<nav class="navbar">
  <div class="container">
    <a href="index.php" class="logo">Barber Flow citas</a>
    <ul class="menu">
      <li><a href="index.php">Inicio</a></li>

      <?php if(!$rol): ?>
        <!-- Visitante -->
        <li><a href="login.php">Iniciar Sesión</a></li>
        <li><a href="registro.php">Registro Usuario</a></li>
        <li><a href="registro-admin.php">Registro Admin</a></li>

      <?php elseif($rol === 'usuario'): ?>
        <!-- Usuario normal -->
        <li><a href="mis-citas.php">Mis Citas</a></li>
        <li><a href="logout.php">Cerrar Sesión</a></li>

      <?php elseif($rol === 'admin'): ?>
        <!-- Administrador -->
        <li><a href="admin-citas.php">Panel Admin</a></li>
        <li><a href="mi-barberia.php?id=<?= $usuario_id ?>">
            <?= $nombre_barberia ? $nombre_barberia : "Mi Barbería" ?>
        </a></li>
        <li><a href="logout.php">Cerrar Sesión</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
