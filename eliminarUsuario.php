<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];
    $eliminado = false;

    // Preparar y ejecutar DELETE
    $sql = "DELETE FROM usuarios WHERE id = ?";
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $eliminado = true;
        }
        $stmt->close();
    }

    $conexion->close();

    // Redirigir de vuelta a administracion.php con indicador
    header("Location: administracion.php?usuario_eliminado=" . ($eliminado ? '1' : '0'));
    exit();
} else {
    // Si se accede de otra forma, lo mandamos al panel
    header("Location: administracion.php");
    exit();
}
?>
