<?php
include 'conexion.php';

if (!isset($_GET['id'])) {
    header('Location: administracion.php');
    exit;
}

$id = (int) $_GET['id'];
$stmt = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    header('Location: administracion.php');
    exit;
}
$prod = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Producto</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
  <header>…</header>

  <section class="welcome-content">
    <h2>Editar Producto</h2>
    <form action="actualizarProducto.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $prod['id'] ?>">
      <label>Nombre:</label>
      <input type="text" name="nombre" value="<?= htmlspecialchars($prod['nombre']) ?>" required><br><br>

      <label>Descripción:</label>
      <textarea name="descripcion" required><?= htmlspecialchars($prod['descripcion']) ?></textarea><br><br>

      <label>Precio:</label>
      <input type="number" name="precio" step="0.01" value="<?= $prod['precio'] ?>" required><br><br>

      <label>Stock:</label>
      <input type="number" name="stock" value="<?= $prod['stock'] ?>" required><br><br>

      <label>Categoría:</label>
      <input type="text" name="categoria" value="<?= htmlspecialchars($prod['categoria']) ?>" required><br><br>

      <label>Imagen actual:</label><br>
      <img src="Imagenes/<?= htmlspecialchars($prod['imagen']) ?>" width="120"><br><br>

      <label>Subir nueva imagen (opcional):</label>
      <input type="file" name="imagen"><br><br>

      <button type="submit">Actualizar Producto</button>
    </form>
  </section>

  <footer>…</footer>
</body>
</html>
