<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['totalProductos' => 0]);
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

$sql = "SELECT SUM(cantidad) AS totalProductos FROM carrito WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(['totalProductos' => $row['totalProductos'] ?? 0]);
?>
