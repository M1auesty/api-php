<?php
header("Content-Type: application/json");
require_once "db.php";

// Recibir datos del pago
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["id_pedido"], $data["monto"], $data["metodo_pago"])) {
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Faltan datos para procesar el pago"
    ]);
    exit;
}

$id_pedido = $data["id_pedido"];
$monto = $data["monto"];
$metodo_pago = strtolower($data["metodo_pago"]);
$fecha_pago = date("Y-m-d H:i:s");
$estado_pago = "pagado"; // Simulación de éxito

try {
    // Guardar el pago
    $sql = "INSERT INTO pago (id_pedido, monto, fecha, metodo_pago, estado)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_pedido, $monto, $fecha_pago, $metodo_pago, $estado_pago]);

    echo json_encode([
        "estado" => "ok",
        "mensaje" => "Pago procesado exitosamente",
        "id_pedido" => $id_pedido,
        "monto" => $monto,
        "metodo_pago" => $metodo_pago,
        "estado_pago" => $estado_pago
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Error al registrar el pago: " . $e->getMessage()
    ]);
}
?>
