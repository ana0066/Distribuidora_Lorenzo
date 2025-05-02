<?php
header("Content-Type: application/json");
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$conn = conectarDB();

$id = $data["id"];

$query = "DELETE FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Producto eliminado con éxito"]);
} else {
    echo json_encode(["error" => "Error al eliminar producto"]);
}

$stmt->close();
$conn->close();
?>
