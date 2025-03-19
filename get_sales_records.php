<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

$servername = "localhost";
$username = "root";
$password = "";
$database = "garage";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

// Fetch all invoices
$sql = "SELECT * FROM invoice";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $invoices = [];
    while($row = $result->fetch_assoc()) {
        $invoices[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $invoices]);
} else {
    echo json_encode(['success' => false, 'message' => 'No sales records found.']);
}

$conn->close();
?>
