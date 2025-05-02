<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'distribuidoral');

if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión: ' . $conn->connect_error]);
    exit;
}

// Leer el JSON del body
$data = json_decode(file_get_contents("php://input"), true);

// Verificar si se recibió todo
if (
    isset($data['nombre']) && isset($data['valor']) &&
    isset($data['existencia']) && isset($data['urlImagen']) &&
    isset($data['categoria'])
) {
    $nombre = $data['nombre'];
    $valor = $data['valor'];
    $existencia = $data['existencia'];
    $urlImagen = $data['urlImagen'];
    $categoria = $data['categoria'];

    // Preparar y ejecutar el insert
    $stmt = $conn->prepare("INSERT INTO products (nombre, valor, existencia, urlImagen, categoria) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("siiss", $nombre, $valor, $existencia, $urlImagen, $categoria);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Producto agregado correctamente']);
    } else {
        echo json_encode(['error' => 'Error al agregar producto: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Datos incompletos']);
}

$conn->close();
?>
