<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['cliente_id'])) {
    http_response_code(401);
    echo json_encode(["mensaje"=>"No autenticado"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['id'], $data['action'])) {
    http_response_code(400);
    echo json_encode(["mensaje"=>"Datos incompletos"]);
    exit;
}

$id     = (int)$data['id'];
$action = $data['action']; // 'increase' o 'decrease'

// Obtener registro
$stmt = $conexion->prepare("SELECT cantidad FROM carrito WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["mensaje"=>"Item no encontrado"]);
    exit;
}
$row = $res->fetch_assoc();
$cant = (int)$row['cantidad'];
if ($action === 'increase') {
    $cant++;
} elseif ($action === 'decrease' && $cant > 1) {
    $cant--;
} else {
    // no permitimos cantidad < 1
    echo json_encode(["mensaje"=>"Cantidad inválida"]);
    exit;
}

$stmt2 = $conexion->prepare("UPDATE carrito SET cantidad = ? WHERE id = ?");
$stmt2->bind_param("ii", $cant, $id);
$stmt2->execute();

echo json_encode(["mensaje"=>"Cantidad actualizada"]);
