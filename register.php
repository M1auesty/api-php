<?php
require 'db.php'; 
header('Content-Type: application/json');

// Recibir datos JSON
$data = json_decode(file_get_contents("php://input"), true);

// Validar campos requeridos
if (
    empty($data['nombre']) || 
    empty($data['email']) || 
    empty($data['contrasena']) || 
    empty($data['telefono']) || 
    empty($data['dirreccion']) || 
    empty($data['documento'])
) {
    echo json_encode(["estado" => "error", "mensaje" => "Faltan datos"]);
    exit;
}

try {
    // Encriptar contraseña
    $passwordHash = password_hash($data['contrasena'], PASSWORD_DEFAULT);

    // Insertar en la base de datos
    $stmt = $conn->prepare("
        INSERT INTO cliente (nombre, email, contrasena, telefono, dirreccion, documento) 
        VALUES (:nombre, :email, :contrasena, :telefono, :dirreccion, :documento)
    ");

    $stmt->execute([
        ':nombre'     => $data['nombre'],
        ':email'      => $data['email'],
        ':contrasena' => $passwordHash,
        ':telefono'   => $data['telefono'],
        ':dirreccion' => $data['dirreccion'],
        ':documento'  => $data['documento']
    ]);

    echo json_encode(["estado" => "ok", "mensaje" => "Usuario registrado correctamente"]);
} catch (PDOException $e) {
    echo json_encode(["estado" => "error", "mensaje" => $e->getMessage()]);
    exit;
}


