<?php
// Configuración de la base de datos
$servername = "localhost"; // O tu servidor de BD
$username = "root";        // Tu usuario de BD
$password = "";            // Tu contraseña de BD
$dbname = "universidad_db"; // El nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer el charset a UTF-8 para manejar acentos y caracteres especiales
$conn->set_charset("utf8mb4");
?>