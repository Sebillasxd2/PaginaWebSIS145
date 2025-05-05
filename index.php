<?php
include 'conexion.php';

$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : ''; 

$sql = "SELECT * FROM productos";
if ($categoria) {
    $sql .= " WHERE categoria = '$categoria'";
}

$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    echo "<h2>Productos en Inventario</h2>";
    echo "<table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Categoría</th>
            <th>Fecha de Creación</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>";

    while ($row = $resultado->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['nombre']}</td>
            <td>{$row['descripcion']}</td>
            <td>{$row['precio']}</td>
            <td>{$row['stock']}</td>
            <td>{$row['categoria']}</td>
            <td>{$row['fecha_creacion']}</td>
            <td><img src='Imagenes/{$row['imagen']}' alt='{$row['nombre']}' class='product-image'></td>
            <td><a href='eliminarProducto.php?id={$row['id']}' class='button'>Eliminar</a></td>
          </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No se encontraron productos para la categoría seleccionada.</p>";
}

$conexion->close();
?>
