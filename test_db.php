<?php
require 'db.php';

try {
    $sql = "SELECT COUNT(*) as total FROM cliente";
    $stmt = $conn->query($sql);
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(["estado" => "ok", "total_clientes" => $fila['total']]);
} catch (PDOException $e) {
    echo json_encode(["estado" => "error", "mensaje" => $e->getMessage()]);
}
