<?php
include 'conexion.php';

$id = $_POST['id'];

$sql = "DELETE FROM proveedores WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: administracion.php?proveedor_eliminado=1");
} else {
    echo "Error al eliminar proveedores: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
