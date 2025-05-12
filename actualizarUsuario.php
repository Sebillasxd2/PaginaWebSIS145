<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: administracion.php');
    exit;
}

$id     = (int) $_POST['id'];
$nombre = trim($_POST['nombre']);
$rol    = $_POST['rol'];
$sql    = "";
$params = [];
$types  = "";

// Si dejaron contraseña, la hasheamos y la actualizamos
if (!empty($_POST['contrasena'])) {
    $hash = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $sql = "UPDATE usuarios SET nombre = ?, rol = ?, contrasena = ? WHERE id = ?";
    $types  = "sssi";
    $params = [$nombre, $rol, $hash, $id];
} else {
    $sql = "UPDATE usuarios SET nombre = ?, rol = ? WHERE id = ?";
    $types  = "ssi";
    $params = [$nombre, $rol, $id];
}

$stmt = $conexion->prepare($sql);
$stmt->bind_param($types, ...$params);
$ok = $stmt->execute();
$stmt->close();
$conexion->close();

header("Location: administracion.php?usuario_actualizado=" . ($ok ? '1' : '0'));
exit;
