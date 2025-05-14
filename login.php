<?php
session_start();
include 'conexion.php';

$nombre = $_POST['nombre'];
$contrasena = $_POST['contrasena'];

$sql = "SELECT id, nombre, contrasena, rol FROM usuarios WHERE nombre = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $nombre);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    // Verificar contraseña (si la guardas hasheada)
    if (password_verify($contrasena, $usuario['contrasena'])) {
        // Aquí guardas el ID del cliente en sesión
        $_SESSION['cliente_id'] = $usuario['id'];
        $_SESSION['rol']       = $usuario['rol'];
        $_SESSION['nombre']    = $usuario['nombre'];

        if ($usuario['rol'] === 'cliente') {
            header("Location: index.html");
            exit();
        } elseif ($usuario['rol'] === 'administrador') {
            header("Location: administracion.php");
            exit();
        }
    } else {
        $_SESSION['error_login'] = "Contraseña incorrecta.";
        header("Location: login.html");
        exit();
    }
} else {
    $_SESSION['error_login'] = "Nombre de usuario no encontrado.";
    header("Location: login.html");
    exit();
}
?>
