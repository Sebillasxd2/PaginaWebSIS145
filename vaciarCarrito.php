<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['cliente_id'])) {
    http_response_code(401);
    echo json_encode(["mensaje"=>"No autenticado"]);
    exit;
}

$cliente_id = $_SESSION['cliente_id'];
$stmt = $conexion->prepare("DELETE FROM carrito WHERE cliente_id = ?");
$stmt->bind_param("i", $cliente_id);
$stmt->execute();

echo json_encode(["mensaje"=>"Carrito vaciado"]);
