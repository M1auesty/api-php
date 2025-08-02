<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require 'db.php';

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['nombre']) || !isset($input['email']) || !isset($input['contrasena'])) {
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

$nombre = $input['nombre'];
$email = $input['email'];
$contrasena = password_hash($input['contrasena'], PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $email, $contrasena]);
    echo json_encode(["mensaje" => "Usuario registrado correctamente"]);
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo json_encode(["error" => "El correo ya está registrado"]);
    } else {
        echo json_encode(["error" => "Error interno"]);
    }
}
?>

