<?php
session_start();
include 'conexion.php';

// Verificar si se recibieron datos JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id_producto']) || !isset($data['nombre'])) {
    http_response_code(400);
    echo json_encode(["mensaje" => "Datos incompletos"]);
    exit;
}

$id_producto = $data['id_producto'];
$nombre = $data['nombre'];

// Verificar si el producto ya está en el carrito
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$encontrado = false;
foreach ($_SESSION['carrito'] as &$item) {
    if ($item['id_producto'] == $id_producto) {
        $item['cantidad'] += 1;
        $encontrado = true;
        break;
    }
}

if (!$encontrado) {
    // Obtener precio del producto desde la base de datos
    $stmt = $conexion->prepare("SELECT precio FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
        $_SESSION['carrito'][] = [
            'id_producto' => $id_producto,
            'nombre' => $nombre,
            'precio' => $producto['precio'],
            'cantidad' => 1
        ];
    } else {
        http_response_code(404);
        echo json_encode(["mensaje" => "Producto no encontrado"]);
        exit;
    }
}

echo json_encode(["mensaje" => "Producto agregado al carrito correctamente"]);
?>