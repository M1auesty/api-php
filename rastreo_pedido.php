<?php
header("Content-Type: application/json");
require_once "db.php";

// Recibir datos
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["id_pedido"])) {
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Falta el id_pedido"
    ]);
    exit;
}

$id_pedido = $data["id_pedido"];

try {
    // Consultar pedido
    $sql = "SELECT p.id_pedido, p.fecha, p.estado, p.total,
                   pa.metodo_pago, pa.estado AS estado_pago
            FROM pedido p
            LEFT JOIN pago pa ON p.id_pedido = pa.id_pedido
            WHERE p.id_pedido = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_pedido]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pedido) {
        echo json_encode([
            "estado" => "ok",
            "mensaje" => "Pedido encontrado",
            "pedido" => $pedido
        ]);
    } else {
        echo json_encode([
            "estado" => "error",
            "mensaje" => "Pedido no encontrado"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Error en la consulta: " . $e->getMessage()
    ]);
}
?>
