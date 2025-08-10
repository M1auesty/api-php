<?php
header('Content-Type: application/json');
include 'conexion.php';

// Obtener ID del cliente
$id_cliente = isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : 0;

if ($id_cliente <= 0) {
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Falta el ID del cliente"
    ]);
    exit;
}

// Consultar pedidos del cliente
$sql = "SELECT 
            id_pedido,
            fecha,
            estado,
            total
        FROM pedido
        WHERE id_cliente = ?
        ORDER BY fecha DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();

$pedidos = [];

while ($row = $result->fetch_assoc()) {
    $pedidos[] = $row;
}

if (empty($pedidos)) {
    echo json_encode([
        "estado" => "ok",
        "mensaje" => "No se encontraron pedidos",
        "pedidos" => []
    ]);
} else {
    echo json_encode([
        "estado" => "ok",
        "pedidos" => $pedidos
    ]);
}

$conn->close();
?>
