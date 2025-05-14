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
        // Sanitize
        $id    = (int)$row['id'];
        $nombre= htmlspecialchars($row['nombre'], ENT_QUOTES);
        $precio= number_format($row['precio'], 2, '.', '');
        $img   = htmlspecialchars($row['imagen'], ENT_QUOTES);

        echo "<tr>
            <td>{$id}</td>
            <td>{$nombre}</td>
            <td>" . htmlspecialchars($row['descripcion']) . "</td>
            <td>\${$precio}</td>
            <td>{$row['stock']}</td>
            <td>" . htmlspecialchars($row['categoria']) . "</td>
            <td>{$row['fecha_creacion']}</td>
            <td><img src='Imagenes/{$img}' alt='{$nombre}' class='product-image'></td>
            <td>
                <!-- Botón Agregar al carrito -->
                <button 
                    class='agregarCarrito' 
                    data-id='{$id}' 
                    data-nombre='{$nombre}' 
                    data-precio='{$precio}'>
                  Añadir al carrito
                </button>
            </td>
          </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No se encontraron productos para la categoría seleccionada.</p>";
}

$conexion->close();
?>
