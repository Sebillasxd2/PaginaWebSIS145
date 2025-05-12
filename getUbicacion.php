<?php
include 'conexion.php';

$sql = "SELECT * FROM ubicacion LIMIT 1";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    echo json_encode($fila);
} else {
    echo json_encode(["descripcion" => "", "url" => ""]);
}
?>
