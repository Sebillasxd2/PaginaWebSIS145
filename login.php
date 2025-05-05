<?php
session_start();
include 'conexion.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.html");
    exit();
}

$nombre = trim($_POST['nombre'] ?? '');
$contrasena = trim($_POST['contrasena'] ?? '');


if (empty($nombre) || empty($contrasena)) {
    $_SESSION['error_login'] = "Todos los campos son obligatorios";
    header("Location: login.html");
    exit();
}


$sql = "SELECT id, nombre, contrasena, rol FROM usuarios WHERE nombre = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    $_SESSION['error_login'] = "Error en el sistema. Por favor intenta más tarde.";
    header("Location: login.html");
    exit();
}

$stmt->bind_param("s", $nombre);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();
    
    
    if ($usuario['nombre'] === 'Sebastian Arduz') {
      
        $login_exitoso = ($contrasena === $usuario['contrasena']);
    } else {
       
        $login_exitoso = password_verify($contrasena, $usuario['contrasena']);
    }
    
    if ($login_exitoso) {
        
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol'] = $usuario['rol'];
        
       
        if ($usuario['rol'] === 'administrador') {
            header("Location: administracion.php");
        } else {
            header("Location: index.html");
        }
        exit();
    }
}


$_SESSION['error_login'] = "Nombre de usuario o contraseña incorrectos";
header("Location: login.html");
exit();
?>