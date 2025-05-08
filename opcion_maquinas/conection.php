<?php
$servername = "localhost"; // Cambia esto si tu servidor MySQL está en otro lugar
$username = "TC2005B_602_03";
$password = "Gx[IA-apZzok";
$database = "TC2005B_602_03";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $database);

// Verificar conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}




?>
