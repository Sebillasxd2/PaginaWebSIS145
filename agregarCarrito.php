<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['cliente_id'])) {
    http_response_code(401);
    echo json_encode(["mensaje"=>"No autenticado"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['id_producto'])) {
    http_response_code(400);
    echo json_encode(["mensaje"=>"Datos incompletos"]);
    exit;
}

$cliente_id   = $_SESSION['cliente_id'];
$id_producto  = (int)$data['id_producto'];

// Obtener precio actual
$stmt = $conexion->prepare("SELECT precio FROM productos WHERE id = ?");
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["mensaje"=>"Producto no existe"]);
    exit;
}
$precio = $res->fetch_assoc()['precio'];

// ¿Ya existe en carrito?
$stmt = $conexion->prepare(
    "SELECT id, cantidad 
     FROM carrito 
     WHERE cliente_id = ? AND producto_id = ?"
);
$stmt->bind_param("ii", $cliente_id, $id_producto);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    // actualizar cantidad +1
    $row = $res->fetch_assoc();
    $newCant = $row['cantidad'] + 1;
    $stmt2 = $conexion->prepare(
      "UPDATE carrito SET cantidad = ? WHERE id = ?"
    );
    $stmt2->bind_param("ii", $newCant, $row['id']);
    $stmt2->execute();
} else {
    // insertar nuevo
    $stmt2 = $conexion->prepare(
      "INSERT INTO carrito (cliente_id, producto_id, cantidad, precio, fecha_agregado)
       VALUES (?, ?, 1, ?, NOW())"
    );
    $stmt2->bind_param("iid", $cliente_id, $id_producto, $precio);
    $stmt2->execute();
}

echo json_encode(["mensaje"=>"Agregado correctamente"]);
