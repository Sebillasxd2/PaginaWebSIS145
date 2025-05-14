<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Obtener datos actuales de la ubicación
    $sql = "SELECT * FROM ubicacion WHERE id = $id";
    $resultado = $conexion->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $ubicacion = $resultado->fetch_assoc();
    } else {
        echo "<p>Ubicación no encontrada.</p>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $descripcion = $conexion->real_escape_string($_POST['descripcion']);
    $url = $conexion->real_escape_string($_POST['url']);

    $sql = "UPDATE ubicacion SET descripcion = '$descripcion', url = '$url' WHERE id = $id";

    if ($conexion->query($sql) === TRUE) {
        header("Location: administracion.php?actualizado=1");
        exit;
    } else {
        echo "<p>Error al actualizar la ubicación: " . $conexion->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Ubicación</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h2>Editar Ubicación</h2>
    <form action="editarUbicacion.php" method="post">
        <input type="hidden" name="id" value="<?php echo $ubicacion['id']; ?>">
        <label for="descripcion">Descripción:</label><br>
<textarea id="textareaUbicacion" name="descripcion" required><?php echo htmlspecialchars($ubicacion['descripcion']); ?></textarea><br><br>

        <label for="url">URL:</label><br>
        <input id="urlUbicacion"type="text" name="url" value="<?php echo htmlspecialchars($ubicacion['url']); ?>" required><br><br>
        <button type="submit">Guardar Cambios</button>
        <a href="administracion.php">Cancelar</a>
    </form>
</body>
</html>
