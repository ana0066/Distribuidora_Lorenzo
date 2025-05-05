<?php
include "../php/db.php";

$result = $conn->query("SELECT * FROM products ORDER BY nombre ASC");
$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
?>
