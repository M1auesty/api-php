<?php
require 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['email']) || empty($data['contrasena'])) {
    echo json_encode(["estado" => "error", "mensaje" => "Email y contraseña son obligatorios"]);
    exit;
}

try {
    // Buscar usuario por email
    $stmt = $conn->prepare("SELECT * FROM cliente WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $data['email']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($data['contrasena'], $usuario['contrasena'])) {
        echo json_encode([
            "estado" => "ok",
            "mensaje" => "Login exitoso",
            "usuario" => [
                "id_cliente" => $usuario['id_cliente'],
                "nombre"     => $usuario['nombre'],
                "email"      => $usuario['email']
            ]
        ]);
    } else {
        echo json_encode(["estado" => "error", "mensaje" => "Credenciales incorrectas"]);
    }
} catch (PDOException $e) {
    echo json_encode(["estado" => "error", "mensaje" => $e->getMessage()]);
}




