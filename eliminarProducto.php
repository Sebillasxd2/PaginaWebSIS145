<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $eliminado = false;


    $sqlImg = "SELECT imagen FROM productos WHERE id = ?";
    $stmtImg = $conexion->prepare($sqlImg);
    $stmtImg->bind_param("i", $id);
    $stmtImg->execute();
    $resultado = $stmtImg->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $imagen = $fila['imagen'];
        $rutaImagen = 'Imagenes/' . $imagen;

        // Eliminar imagen si existe
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
    }

 
    $sql = "DELETE FROM productos WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $eliminado = true;
    }

    $conexion->close();


    header("Location: administracion.php?eliminado=" . ($eliminado ? '1' : '0') . "&id=" . $id);
    exit();
}
?>