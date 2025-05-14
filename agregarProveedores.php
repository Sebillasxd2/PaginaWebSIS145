<?php
include 'conexion.php';

$nombre = $_POST['nombre'];
$logo_nombre = '';

if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
    $logo_nombre = 'Imagenes/' . basename($_FILES['logo']['name']);
    move_uploaded_file($_FILES['logo']['tmp_name'], $logo_nombre);
}

$sql = "INSERT INTO proveedores (nombre, logo_url) VALUES (?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $nombre, $logo_nombre);

if ($stmt->execute()) {
    header("Location: administracion.php?proveedor_agregado=1");
} else {
    echo "Error al agregar proveedor: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
