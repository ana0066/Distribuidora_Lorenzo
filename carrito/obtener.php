<?php
session_start();
include '../php/db.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['items' => [], 'total' => 0]);
    exit;
}

$idUsuario = $_SESSION['usuario_id'];

$sql = "SELECT c.id_producto AS id, p.nombre, p.valor AS precio, p.urlImagen AS imagen, c.cantidad
        FROM carrito c
        JOIN products p ON c.id_producto = p.id
        WHERE c.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();

$carrito = [];
$total = 0;

while ($fila = $resultado->fetch_assoc()) {
    $fila['subtotal'] = $fila['precio'] * $fila['cantidad']; // Calcula el subtotal
    $total += $fila['subtotal']; // Suma al total
    $carrito[] = $fila;
}

echo json_encode(['items' => $carrito, 'total' => $total]);
exit;
