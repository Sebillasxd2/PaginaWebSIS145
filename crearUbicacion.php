<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario']) && !isset($_SESSION['nombre'])) {
    echo "No autorizado";
    exit;
}

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = $_POST['descripcion'];
    $url = $_POST['url'];

    $stmt = $conexion->prepare("INSERT INTO ubicacion (descripcion, url) VALUES (?, ?)");
    $stmt->bind_param("ss", $descripcion, $url);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }
    $stmt->close();
}
?>