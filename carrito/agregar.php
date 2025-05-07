<?php
session_start();
include '../php/db.php';

if (!isset($_SESSION['usuario'])) {
    echo "Debes iniciar sesión.";
    exit;
}

if (!isset($_SESSION['usuario_id'])) {
  header("Location: ../php/login.php");
  exit;
}
$idUsuario = $_SESSION['usuario_id'];
$id_usuario = $_SESSION['usuario_id'];
$idProducto = $_POST['id_producto'] ?? null;

if (!$idProducto) {
    echo "Producto no válido.";
    exit;
}

// Verificar stock
$producto = $conn->query("SELECT existencia FROM products WHERE id = $idProducto")->fetch_assoc();
if (!$producto || $producto['existencia'] <= 0) {
    echo "Producto sin stock.";
    exit;
}

// Verificar si ya está en el carrito
$carrito = $conn->query("SELECT * FROM carrito WHERE id_usuario = $idUsuario AND id_producto = $idProducto");
if ($carrito->num_rows > 0) {
    $item = $carrito->fetch_assoc();
    if ($item['cantidad'] >= $producto['existencia']) {
        echo "No puedes agregar más unidades. Stock limitado.";
        exit;
    }
    $conn->query("UPDATE carrito SET cantidad = cantidad + 1 WHERE id = {$item['id']}");
} else {
    $conn->query("INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES ($idUsuario, $idProducto, 1)");
}

echo "Producto agregado al carrito.";
