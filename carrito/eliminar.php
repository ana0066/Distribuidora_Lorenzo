<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['id_usuario'])) {
    http_response_code(403);
    echo "No autorizado.";
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_producto = intval($_POST['id_producto'] ?? 0);

$stmt = $conn->prepare("DELETE FROM carrito WHERE id_usuario = ? AND id_producto = ?");
$stmt->bind_param("ii", $id_usuario, $id_producto);
$stmt->execute();

echo "Producto eliminado del carrito.";
