<?php
include 'conexion.php';

if (!isset($_GET['id'])) {
    die("ID no proporcionado.");
}

$id = (int) $_GET['id'];
$sql = "SELECT * FROM proveedores WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$proveedor = $resultado->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Proveedor</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
  <!-- HEADER -->
  <header>
    <div class="header-title">
      <img src="Imagenes/logo.png" alt="Logo" class="header-logo">
      <h1>Admin Panel</h1>
    </div>
    <nav>
      <a href="index.html">Inicio</a>
      <a href="administracion.php#lista-proveedores">Volver a Proveedores</a>
      <a href="login.html">Cerrar Sesión</a>
    </nav>
  </header>

  <!-- SECCIÓN EDITAR PROVEEDOR -->
  <section class="welcome-content" id="editar-proveedor">
    <h2>Editar Proveedor</h2>
    <form action="actualizarProveedores.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $proveedor['id']; ?>">

      <label for="nombre">Nombre del proveedor:</label><br>
      <input 
        type="text" 
        id="nombre" 
        name="nombre" 
        value="<?php echo htmlspecialchars($proveedor['nombre'], ENT_QUOTES); ?>" 
        required
      ><br><br>

      <p>Logo actual:</p>
      <div class="proveedor-logo-container">
        <img 
          src="Imagenes/<?php echo htmlspecialchars($proveedor['logo_url'], ENT_QUOTES); ?>" 
          alt="Logo <?php echo htmlspecialchars($proveedor['nombre'], ENT_QUOTES); ?>" 
          class="proveedor-logo"
        >
      </div><br>

      <label for="logo">Cambiar logo (opcional):</label><br>
      <input type="file" id="logo" name="logo" accept="image/*"><br><br>

      <button type="submit">Actualizar Proveedor</button>
    </form>
  </section>

  <footer>
    <p>© 2025 Mi Tienda. Todos los derechos reservados.</p>
  </footer>
</body>
</html>
