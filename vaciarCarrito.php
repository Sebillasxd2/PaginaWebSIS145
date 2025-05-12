<?php
session_start();
unset($_SESSION['carrito']);
echo json_encode(["mensaje" => "Carrito vaciado correctamente"]);
?>