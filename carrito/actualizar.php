<?php
session_start();
include '../php/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id_usuario = $_SESSION['usuario_id'];
    $id_producto = intval($data['id_producto']);
    $cantidad = intval($data['cantidad']);

    // Validación de stock
    $stmt = $conn->prepare("SELECT existencia FROM products WHERE id = ?");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado.']);
        exit;
    }

    $producto = $res->fetch_assoc();
    if ($cantidad < 1 || $cantidad > $producto['existencia']) {
        echo json_encode(['success' => false, 'message' => "Cantidad no válida. Stock disponible: {$producto['existencia']}"]);
        exit;
    }

    // Actualizar cantidad en el carrito
    $stmt = $conn->prepare("UPDATE carrito SET cantidad = ?, fecha_agregado = NOW() WHERE id_usuario = ? AND id_producto = ?");
    $stmt->bind_param("iii", $cantidad, $id_usuario, $id_producto);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la cantidad']);
    }
    exit;
}
