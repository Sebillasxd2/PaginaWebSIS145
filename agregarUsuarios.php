<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();

include 'conexion.php';

echo "<pre>"; 

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo "Error: Método no permitido. Redirigiendo a administracion.php";
    header("Location: administracion.php");
    exit();
}

$nombre = trim($_POST['nombre'] ?? '');
$contrasena = trim($_POST['contrasena'] ?? '');
$rol = trim($_POST['rol'] ?? '');

echo "Recibido POST:\n";
echo "Nombre: $nombre\n";
echo "Contraseña: [oculto por seguridad]\n";
echo "Rol: $rol\n";


if (empty($nombre) || empty($contrasena) || empty($rol)) {
    echo "Error: Campos vacíos detectados\n";
    header("Location: administracion.php?usuario_agregado=0&motivo=campos_vacios");
    exit();
} elseif (strlen($contrasena) < 6) {
    echo "Error: Contraseña demasiado corta\n";
    header("Location: administracion.php?usuario_agregado=0&motivo=contrasena_corta");
    exit();
} elseif (!in_array($rol, ['administrador', 'cliente'])) {
    echo "Error: Rol inválido\n";
    header("Location: administracion.php?usuario_agregado=0&motivo=rol_invalido");
    exit();
}


$sql_check = "SELECT id FROM usuarios WHERE nombre = ?";
$stmt_check = $conexion->prepare($sql_check);

if (!$stmt_check) {
    echo "Error preparando consulta de verificación: " . $conexion->error . "\n";
    header("Location: administracion.php?usuario_agregado=0&motivo=error_verificacion");
    exit();
}

$stmt_check->bind_param("s", $nombre);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    echo "Usuario ya existe en la base de datos\n";
    $stmt_check->close();
    header("Location: administracion.php?usuario_agregado=0&motivo=usuario_existente");
    exit();
}
$stmt_check->close();


$contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
if (!$contrasena_hash) {
    echo "Error al generar el hash de la contraseña\n";
    header("Location: administracion.php?usuario_agregado=0&motivo=hash_error");
    exit();
}


$sql_insert = "INSERT INTO usuarios (nombre, contrasena, rol) VALUES (?, ?, ?)";
$stmt_insert = $conexion->prepare($sql_insert);

if (!$stmt_insert) {
    echo "Error preparando inserción: " . $conexion->error . "\n";
    header("Location: administracion.php?usuario_agregado=0&motivo=error_insercion");
    exit();
}

$stmt_insert->bind_param("sss", $nombre, $contrasena_hash, $rol);
if ($stmt_insert->execute()) {
    echo "Usuario insertado correctamente\n";
    $stmt_insert->close();
    header("Location: administracion.php?usuario_agregado=1");
    exit();
} else {
    echo "Error al ejecutar inserción: " . $stmt_insert->error . "\n";
    $stmt_insert->close();
    header("Location: administracion.php?usuario_agregado=0&motivo=fallo_insertar");
    exit();
}
?>
