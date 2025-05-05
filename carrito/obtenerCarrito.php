<?php
session_start();
header('Content-Type: application/json');
require '../php/db.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No logueado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$query = "SELECT c.id, c.cantidad, p.nombre, p.valor, p.urlImagen 
          FROM carrito c
          JOIN products p ON c.producto_id = p.id
          WHERE c.usuario_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$carrito = [];
while ($row = $result->fetch_assoc()) {
    $carrito[] = $row;
}

echo json_encode(['status' => 'success', 'carrito' => $carrito]);
