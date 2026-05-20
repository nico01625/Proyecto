<?php
// config/conexion.php

$host = "localhost";
$user = "root"; // Usuario por defecto en XAMPP
$password = "BASES202610"; // Contraseña por defecto en XAMPP
$database = "tiendamulticategoria"; // El nombre exacto de tu base de datos

// Crear la conexión
$conexion = new mysqli($host, $user, $password, $database);

// Comprobar la conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Establecer el conjunto de caracteres para evitar problemas con tildes y ñ
$conexion->set_charset("utf8");
?>