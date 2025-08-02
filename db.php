<?php
$host = 'localhost';
$dbname = 'api_usuarios';
$user = 'root';
$pass = '';
$port = 3307; // ajusta si es necesario

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

