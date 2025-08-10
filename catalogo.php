<?php
header('Content-Type: application/json');
require_once 'db.php'; // conexión a la BD

try {
    $sql = "SELECT * FROM producto";
    $stmt = $pdo->query($sql);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "estado" => "ok",
        "mensaje" => "Lista de productos obtenida correctamente",
        "data" => $productos
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Error al obtener productos: " . $e->getMessage()
    ]);
}
