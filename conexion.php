<?php
$servidor = "localhost";
$usuario = "root";
$password = ""; 
$basedatos = "databasesis145"; 
$puerto = 3307;


$conexion = new mysqli($servidor, $usuario, $password, $basedatos, $puerto);


if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}


$conexion->set_charset("utf8");
?>