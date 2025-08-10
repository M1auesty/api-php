<?php
require_once "db.php"; // Conexión a BD

header("Content-Type: application/json; charset=UTF-8");

// Leer datos de entrada
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id_cliente']) || empty($data['id_cliente'])) {
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Falta el id_cliente"
    ]);
    exit;
}

$id_cliente = intval($data['id_cliente']);

// Consulta del carrito
$sql = "SELECT c.id_carrito, p.nombre, p.precio, c.cantidad, (p.precio * c.cantidad) AS subtotal
        FROM carrito c
        INNER JOIN producto p ON c.id_producto = p.id_producto
        WHERE c.id_cliente = :id_cliente";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($items) {
    echo json_encode([
        "estado" => "ok",
        "carrito" => $items
    ]);
} else {
    echo json_encode([
        "estado" => "ok",
        "carrito" => [],
        "mensaje" => "El carrito está vacío"
    ]);
}
?>
