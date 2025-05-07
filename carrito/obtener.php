<?php
session_start();
include '../php/db.php';

// Configurar la cabecera para devolver JSON
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

$stmt = $conn->prepare("
    SELECT 
        p.id AS id_producto, 
        p.nombre, 
        p.valor, 
        p.existencia, 
        c.cantidad, 
        p.urlImagen 
    FROM carrito c 
    JOIN products p ON c.id_producto = p.id 
    WHERE c.id_usuario = ?
");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$html = '';
$total = 0;
$cantidad = 0;

while ($item = $result->fetch_assoc()) {
    $subtotal = $item['valor'] * $item['cantidad'];
    $dataMax = isset($item['existencia']) ? htmlspecialchars($item['existencia']) : '0';
    $dataId = isset($item['id_producto']) ? htmlspecialchars($item['id_producto']) : '0';

    $html .= "<div class='carrito-item'>
                 <img src='" . htmlspecialchars($item['urlImagen']) . "' alt='" . htmlspecialchars($item['nombre']) . "'>
                 <p>" . htmlspecialchars($item['nombre']) . "</p>
                 <p class='precio-unitario'>" . number_format($item['valor'], 2) . "</p>
                 <input type='number' 
                        value='" . $item['cantidad'] . "' 
                        class='input-cantidad' 
                        data-id='" . $dataId . "' 
                        data-max='" . $dataMax . "' 
                        data-min='1'>
                 <p class='subtotal-item'>" . number_format($subtotal, 2) . "</p>
               </div>";

    $total += $subtotal;
    $cantidad += $item['cantidad'];
}

echo json_encode([
    'html' => $html,
    'total' => $total,
    'cantidad' => $cantidad
]);
exit;
?>