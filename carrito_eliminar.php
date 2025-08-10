<?php
require_once "db.php";
header("Content-Type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id_cliente']) || empty($data['id_cliente'])) {
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Falta el id_cliente"
    ]);
    exit;
}

$id_cliente = intval($data['id_cliente']);

try {
    $sql = "DELETE FROM carrito WHERE id_cliente = :id_cliente";
    $stmt = $conn->prepare($sql);
    $stmt->execute([":id_cliente" => $id_cliente]);

    echo json_encode([
        "estado" => "ok",
        "mensaje" => "Carrito eliminado"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Error: " . $e->getMessage()
    ]);
}
?>

