<?php
require_once "db.php";
header("Content-Type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"), true);

if (
    !isset($data['id_cliente']) || empty($data['id_cliente']) ||
    !isset($data['id_producto']) || empty($data['id_producto']) ||
    !isset($data['cantidad']) || empty($data['cantidad'])
) {
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Faltan datos: id_cliente, id_producto o cantidad"
    ]);
    exit;
}

$id_cliente  = intval($data['id_cliente']);
$id_producto = intval($data['id_producto']);
$cantidad    = intval($data['cantidad']);

try {
    // Insertar o actualizar cantidad si ya existe el producto en el carrito
    $sql = "INSERT INTO carrito (id_cliente, id_producto, cantidad)
            VALUES (:id_cliente, :id_producto, :cantidad)
            ON DUPLICATE KEY UPDATE cantidad = cantidad + :cantidad";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":id_cliente" => $id_cliente,
        ":id_producto" => $id_producto,
        ":cantidad" => $cantidad
    ]);

    echo json_encode([
        "estado" => "ok",
        "mensaje" => "Producto agregado al carrito"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Error: " . $e->getMessage()
    ]);
}
?>
