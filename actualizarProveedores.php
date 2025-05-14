<?php
include 'conexion.php';

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$logo_nombre = '';

if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
    $logo_nombre = 'Imagenes/' . basename($_FILES['logo']['name']);
    move_uploaded_file($_FILES['logo']['tmp_name'], $logo_nombre);
}

if ($logo_nombre !== '') {
    $sql = "UPDATE proveedores SET nombre = ?, logo_url = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssi", $nombre, $logo_nombre, $id);
} else {
    $sql = "UPDATE proveedores SET nombre = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $nombre, $id);
}

if ($stmt->execute()) {
    header("Location: administracion.php?proveedor_actualizado=1");
} else {
    echo "Error al actualizar proveedor: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
