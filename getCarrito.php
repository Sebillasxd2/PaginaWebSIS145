<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['cliente_id'])) {
    http_response_code(401);
    echo json_encode([]);
    exit;
}

$cliente_id = $_SESSION['cliente_id'];
$sql = "SELECT c.id, p.nombre AS nombre_producto, c.cantidad, c.precio,
               (c.cantidad * c.precio) AS subtotal
        FROM carrito c
        JOIN productos p ON p.id = c.producto_id
        WHERE c.cliente_id = ?
        ORDER BY c.fecha_agregado ASC";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$res = $stmt->get_result();

$items = [];
while ($row = $res->fetch_assoc()) {
    $items[] = [
      'id'               => (int)$row['id'],
      'nombre_producto'  => $row['nombre_producto'],
      'precio'           => (float)$row['precio'],
      'cantidad'         => (int)$row['cantidad'],
      'subtotal'         => (float)$row['subtotal']
    ];
}

header('Content-Type: application/json');
echo json_encode($items);
