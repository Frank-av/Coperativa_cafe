<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "cooperativa_cafe";

$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8");
?>