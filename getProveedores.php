<?php
include 'conexion.php'; // Asegúrate que tienes conexión a tu DB

$sql = "SELECT id, nombre, logo_url FROM proveedores"; // Ajustamos la consulta para obtener los datos correctos
$result = $conexion->query($sql);

$proveedores = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Añadimos los datos de cada proveedor al arreglo
        $proveedores[] = [
            'id' => $row['id'], // El ID del proveedor
            'nombre' => $row['nombre'], // El nombre del proveedor
            'logo_url' => $row['logo_url'] // El logo URL
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($proveedores); // Devolvemos el JSON con los proveedores
?>
