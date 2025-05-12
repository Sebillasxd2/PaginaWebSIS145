<?php
session_start();

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["mensaje" => "ID de producto no proporcionado"]);
    exit;
}

$id_producto = $_GET['id'];

if (isset($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $key => $item) {
        if ($item['id_producto'] == $id_producto) {
            unset($_SESSION['carrito'][$key]);
            echo json_encode(["mensaje" => "Producto eliminado del carrito"]);
            exit;
        }
    }
}

http_response_code(404);
echo json_encode(["mensaje" => "Producto no encontrado en el carrito"]);
?>