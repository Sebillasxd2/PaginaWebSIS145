<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['cliente_id'])) {
    http_response_code(401);
    echo json_encode(["mensaje"=>"No autenticado"]);
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    http_response_code(400);
    echo json_encode(["mensaje"=>"ID no proporcionado"]);
    exit;
}

$stmt = $conexion->prepare("DELETE FROM carrito WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows) {
    echo json_encode(["mensaje"=>"Eliminado correctamente"]);
} else {
    http_response_code(404);
    echo json_encode(["mensaje"=>"Item no encontrado"]);
}
