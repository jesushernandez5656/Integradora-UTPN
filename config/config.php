<?php
// Configuración de la base de datos
$host = "localhost";
$dbname = "utpn_db";
$username = "root"; 
$password = "";

// Conexión a la BD
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

// Configuración de correo institucional (Microsoft Office365)
$mail_host = "smtp.office365.com";
$mail_port = 587;
$mail_username = "24110010@utpn.edu.mx"; 
$mail_password = "Jenifer16"; 
$mail_from = "24110010@utpn.edu.mx"; 
$mail_from_name = "Soporte UTPN"; 
?>
