<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $rol = 'cliente'; // Fijo, no se muestra en el formulario

    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, contrasena, rol) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $contrasena, $rol);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Cuenta creada exitosamente. Ahora puedes iniciar sesión.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error_creacion'] = "Error al crear la cuenta. Intenta nuevamente.";
    }

    $stmt->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Cuenta</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
  <header>
    <div class="header-title">
      <img src="Imagenes/logo.png" alt="Logo" class="header-logo">
      <h1>Mi Tienda</h1>
    </div>
    <nav>
      <a href="index.html">Inicio</a>
      <a href="login.php">Volver al login</a>
    </nav>
  </header>

  <section class="welcome-content">
    <div class="login-form">
      <h2>Crear Cuenta</h2>

      <?php if (isset($_SESSION['error_creacion'])): ?>
        <div class="error-message">
          <?php 
            echo $_SESSION['error_creacion'];
            unset($_SESSION['error_creacion']); 
          ?>
        </div>
      <?php endif; ?>

      <form action="crearCuenta.php" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>

        <button type="submit">Registrarse</button>
      </form>
    </div>
  </section>

  <footer>
    <p>© 2025 Mi Tienda. Todos los derechos reservados.</p>
  </footer>
</body>
</html>
