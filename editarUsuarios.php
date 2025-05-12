<?php
include 'conexion.php';

if (!isset($_GET['id'])) {
    header('Location: administracion.php');
    exit;
}

$id = (int) $_GET['id'];
$stmt = $conexion->prepare("SELECT nombre, rol FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) {
    header('Location: administracion.php');
    exit;
}
$user = $res->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Usuario</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
  <header>…</header>

  <section class="welcome-content">
    <h2>Editar Usuario</h2>
    <form action="actualizarUsuario.php" method="post">
      <input type="hidden" name="id" value="<?= $id ?>">

      <label>Nombre:</label>
      <input type="text" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required><br><br>

      <label>Rol:</label>
      <select name="rol" required>
        <option value="administrador" <?= $user['rol']==='administrador'?'selected':'' ?>>Administrador</option>
        <option value="cliente"       <?= $user['rol']==='cliente'      ?'selected':'' ?>>Cliente</option>
      </select><br><br>

      <label>Nueva contraseña (opcional):</label>
      <input type="password" name="contrasena"><br><br>

      <button type="submit">Actualizar Usuario</button>
    </form>
  </section>

  <footer>…</footer>
</body>
</html>
