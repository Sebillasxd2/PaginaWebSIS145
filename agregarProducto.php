<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria = $_POST['categoria'];


    $imagen = $_FILES['imagen']['name'];
    $imagenTmp = $_FILES['imagen']['tmp_name'];
    $directorioImagen = 'Imagenes/' . $imagen;


    if (move_uploaded_file($imagenTmp, $directorioImagen)) {

        $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria, imagen) 
                VALUES ('$nombre', '$descripcion', '$precio', '$stock', '$categoria', '$imagen')";

if ($conexion->query($sql) === TRUE) {
    header("Location: administracion.php");
    exit();
} else {
    echo "<p>Error al agregar el producto: " . $conexion->error . "</p>";
}

    } else {
        echo "<p>Error al subir la imagen.</p>";
    }

    $conexion->close();
}
?>
