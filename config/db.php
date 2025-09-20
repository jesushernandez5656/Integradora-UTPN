<?php
// Datos de conexión
$host = "localhost";
$dbname = "utpn";           // 👈 tu base de datos en phpMyAdmin
$username = "root";
$password = "";

try {
    // Conexión con PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("❌ Error en la conexión: " . $e->getMessage());
}
?>
