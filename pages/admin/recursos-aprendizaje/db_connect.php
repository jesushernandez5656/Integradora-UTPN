<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
// CORRECCIÓN: Cambiado a tu nombre de base de datos
$dbname = "recursos_utpn"; 

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer el charset a UTF-8 para manejar acentos
$conn->set_charset("utf8mb4");
?>