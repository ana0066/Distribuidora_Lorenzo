<?php
header("Content-Type: application/json");
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$conn = conectarDB();

$id = $data["id"];
$atributo = $data["atributo"];
$nuevoValor = $data["nuevoValor"];

if ($atributo == "valor" || $atributo == "existencia") {
    $query = "UPDATE products SET $atributo = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("di", $nuevoValor, $id);
} else {
    $query = "UPDATE products SET $atributo = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $nuevoValor, $id);
}

if ($stmt->execute()) {
    echo json_encode(["message" => "Producto actualizado con éxito"]);
} else {
    echo json_encode(["error" => "Error al actualizar producto"]);
}

$stmt->close();
$conn->close();
?>
