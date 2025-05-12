<?php
include 'conexion.php';

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];
$categoria = $_POST['categoria'];

$imagen = $_FILES['imagen']['name'];
$imagen_temporal = $_FILES['imagen']['tmp_name'];

if (!empty($imagen)) {
    // Si se sube una nueva imagen, se guarda
    move_uploaded_file($imagen_temporal, 'Imagenes/' . $imagen);
} else {
    // Si no se sube una nueva, usamos la que ya estaba en la base de datos
    $queryImagen = "SELECT imagen FROM productos WHERE id = ?";
    $stmt = $conexion->prepare($queryImagen);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imagenAnterior);
    $stmt->fetch();
    $stmt->close();
    $imagen = $imagenAnterior;
}

// Actualizamos el producto
$query = "UPDATE productos SET nombre=?, descripcion=?, precio=?, imagen=?, stock=?, categoria=? WHERE id=?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("ssdsisi", $nombre, $descripcion, $precio, $imagen, $stock, $categoria, $id);

if ($stmt->execute()) {
    header("Location: administracion.php?producto_editado=1");
} else {
    header("Location: administracion.php?producto_editado=0");
}

$stmt->close();
$conexion->close();
?>
