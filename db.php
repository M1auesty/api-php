<?php
$host = "localhost";
$dbname = "api_usuarios";
$usuario = "root";
$clave = "";
$puerto = 3307; // Si usas XAMPP con puerto distinto

try {
    $conn = new PDO("mysql:host=$host;port=$puerto;dbname=$dbname;charset=utf8mb4", $usuario, $clave);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 🔹 Alias para compatibilidad con código que use $pdo
    $pdo = $conn;

} catch (PDOException $e) {
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Error de conexión: " . $e->getMessage()
    ]);
    exit; // Evita que el script siga ejecutándose
}
?>
