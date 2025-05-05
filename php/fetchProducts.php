<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);  
header('Content-Type: application/json; charset=UTF-8');
$mysqli = new mysqli('localhost','root','','distribuidoral');
if ($mysqli->connect_errno) {
  echo json_encode(['error' => 'Error de conexión: '.$mysqli->connect_error]);
  exit;
}

// Leer filtro de categoría (GET)
$categoria = '';
if (isset($_GET['categoria']) && $_GET['categoria'] !== '') {
  // Prevenir inyección
  $categoria = $mysqli->real_escape_string($_GET['categoria']);
  $sql = "SELECT id, nombre, valor, existencia, urlImagen, categoria
          FROM products
          WHERE categoria = '$categoria'";
} else {
  $sql = "SELECT id, nombre, valor, existencia, urlImagen, categoria
          FROM products";
}

$result = $mysqli->query($sql);
$productos = [];

while ($row = $result->fetch_assoc()) {
  $productos[] = $row;
}

echo json_encode($productos, JSON_UNESCAPED_UNICODE);
$mysqli->close();
