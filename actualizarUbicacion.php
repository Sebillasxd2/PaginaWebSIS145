<?php
include 'conexion.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : null;
$desc = trim($_POST['descripcion']);
$url  = trim($_POST['url']);

if ($id) {
    // UPDATE existente
    $stmt = $conexion->prepare("UPDATE ubicacion SET descripcion = ?, url = ? WHERE id = ?");
    $stmt->bind_param("ssi", $desc, $url, $id);
    $ok = $stmt->execute();
    $stmt->close();
    header('Location: administracion.php?ubicacion_actualizada=' . ($ok? '1':'0') . '#crud-ubicacion');
} else {
    // INSERT nuevo
    $stmt = $conexion->prepare("INSERT INTO ubicacion (descripcion, url) VALUES (?, ?)");
    $stmt->bind_param("ss", $desc, $url);
    $ok = $stmt->execute();
    $stmt->close();
    header('Location: administracion.php?ubicacion_agregada=' . ($ok? '1':'0') . '#crud-ubicacion');
}

$conexion->close();
exit;
