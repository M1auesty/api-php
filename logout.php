<?php
header('Content-Type: application/json');
session_start();

// Si existe una sesión activa, destruirla
if (session_status() === PHP_SESSION_ACTIVE) {
    session_unset();
    session_destroy();
    echo json_encode(["estado" => "ok", "mensaje" => "Sesión cerrada correctamente"]);
} else {
    echo json_encode(["estado" => "error", "mensaje" => "No hay sesión activa"]);
}

