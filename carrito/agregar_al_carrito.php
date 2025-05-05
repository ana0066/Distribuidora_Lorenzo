<?php
session_start();
require '../php/db.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo "No autorizado";
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$id_producto = $_POST['id_producto'];
$cantidad = $_POST['cantidad'];

$verifica = $conn->prepare("SELECT * FROM carrito WHERE id_usuario = ? AND id_producto = ?");
$verifica->bind_param("ii", $id_usuario, $id_producto);
$verifica->execute();
$result = $verifica->get_result();

if ($result->num_rows > 0) {
    // Si ya existe, solo actualizamos cantidad
    $row = $result->fetch_assoc();
    $nueva_cantidad = $row['cantidad'] + $cantidad;
    $update = $conn->prepare("UPDATE carrito SET cantidad = ? WHERE id_usuario = ? AND id_producto = ?");
    $update->bind_param("iii", $nueva_cantidad, $id_usuario, $id_producto);
    $update->execute();
} else {
    // Si no existe, insertamos nuevo
    $insert = $conn->prepare("INSERT INTO carrito (id_usuario, id_producto, cantidad, fecha_agregado) VALUES (?, ?, ?, NOW())");
    $insert->bind_param("iii", $id_usuario, $id_producto, $cantidad);
    $insert->execute();
}

echo "Producto agregado al carrito";
?>
