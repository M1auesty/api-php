<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require 'db.php';

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['email']) || !isset($input['contrasena'])) {
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

$email = $input['email'];
$contrasena = $input['contrasena'];

try {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
        echo json_encode(["mensaje" => "Inicio de sesión exitoso"]);
    } else {
        echo json_encode(["error" => "Credenciales incorrectas"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Error en el servidor"]);
}
?>



