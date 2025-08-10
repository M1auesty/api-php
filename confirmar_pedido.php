<?php
header("Content-Type: application/json");
require_once "db.php";

// Recibir datos del cliente (id_cliente)
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["id_cliente"]) || empty($data["id_cliente"])) {
    echo json_encode(["estado" => "error", "mensaje" => "Faltan datos"]);
    exit;
}

$id_cliente = $data["id_cliente"];

try {
    // Verificar que el carrito tenga productos
    $sql = "SELECT * FROM carrito WHERE id_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_cliente]);
    $carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($carrito) === 0) {
        echo json_encode(["estado" => "error", "mensaje" => "El carrito está vacío"]);
        exit;
    }

    // Crear pedido
    $sql = "INSERT INTO pedido (fecha, estado, id_cliente, total) VALUES (NOW(), 'pendiente', ?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_cliente]);
    $id_pedido = $conn->lastInsertId();

    $total = 0;
    foreach ($carrito as $item) {
        // Obtener precio del producto
        $sqlProd = "SELECT precio FROM producto WHERE id_producto = ?";
        $stmtProd = $conn->prepare($sqlProd);
        $stmtProd->execute([$item["id_producto"]]);
        $precio = $stmtProd->fetchColumn();

        $total += $precio * $item["cantidad"];

        // Insertar detalle
        $sqlDet = "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
        $stmtDet = $conn->prepare($sqlDet);
        $stmtDet->execute([$id_pedido, $item["id_producto"], $item["cantidad"], $precio]);
    }

    // Actualizar total en pedido
    $sqlUpdate = "UPDATE pedido SET total = ? WHERE id_pedido = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->execute([$total, $id_pedido]);

    // Vaciar carrito (opcional)
    $conn->prepare("DELETE FROM carrito WHERE id_cliente = ?")->execute([$id_cliente]);

    echo json_encode(["estado" => "ok", "mensaje" => "Pedido confirmado", "id_pedido" => $id_pedido, "total" => $total]);

} catch (PDOException $e) {
    echo json_encode(["estado" => "error", "mensaje" => $e->getMessage()]);
}
?>
