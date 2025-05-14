<?php
include 'conexion.php';

$descripcion = $_POST['descripcion'];
$url = $_POST['url'];

$sql = "INSERT INTO ubicacion (descripcion, url) VALUES (?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $descripcion, $url);

if ($stmt->execute()) {
    header("Location: panel.php?ubicacion_agregada=1");
} else {
    echo "Error al agregar ubicación: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
