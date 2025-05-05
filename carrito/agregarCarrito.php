<?php
session_start();
header('Content-Type: application/json');
require '../php/db.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Debes iniciar sesión']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    $stmt = $conn->prepare("SELECT * FROM carrito WHERE usuario_id = ? AND producto_id = ?");
    $stmt->bind_param("ii", $usuario_id, $producto_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $fila = $result->fetch_assoc();
        $nueva_cantidad = $fila['cantidad'] + $cantidad;
        $update = $conn->prepare("UPDATE carrito SET cantidad = ? WHERE usuario_id = ? AND producto_id = ?");
        $update->bind_param("iii", $nueva_cantidad, $usuario_id, $producto_id);
        $update->execute();
    } else {
        $insert = $conn->prepare("INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $usuario_id, $producto_id, $cantidad);
        $insert->execute();
    }

    echo json_encode(['status' => 'success', 'message' => 'Producto agregado']);
}
