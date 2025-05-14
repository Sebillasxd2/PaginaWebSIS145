<?php
include 'conexion.php';

if (!isset($_POST['id'])) {
    header('Location: administracion.php#crud-ubicacion');
    exit;
}

$id = (int) $_POST['id'];
$stmt = $conexion->prepare("DELETE FROM ubicacion WHERE id = ?");
$stmt->bind_param("i", $id);
$ok = $stmt->execute();
$stmt->close();
$conexion->close();

header('Location: administracion.php?ubicacion_eliminada=' . ($ok? '1':'0') . '#crud-ubicacion');
exit;
