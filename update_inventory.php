<?php
header('Content-Type: application/json');

// Get JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

$itemName = $data['itemName'];
$quantity = $data['quantity'];

// Establish database connection (assuming you use MySQL)
$mysqli = new mysqli("localhost", "username", "password", "database_name");

if ($mysqli->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

// Update query to reduce stock
$query = "UPDATE inventory SET stock = stock - ? WHERE item_name = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("is", $quantity, $itemName);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update stock"]);
}

$stmt->close();
$mysqli->close();
?>
