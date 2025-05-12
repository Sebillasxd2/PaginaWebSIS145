<?php
session_start();

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo json_encode([]);
    exit;
}

$carrito = [];
foreach ($_SESSION['carrito'] as $item) {
    $carrito[] = [
        'id' => $item['id_producto'],
        'nombre_producto' => $item['nombre'],
        'precio' => $item['precio'],
        'cantidad' => $item['cantidad'],
        'subtotal' => $item['precio'] * $item['cantidad']
    ];
}

echo json_encode($carrito);
?>