<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "garage";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['vehicle_no']) || !isset($data['customer_name']) || !isset($data['mobile'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    $conn->close();
    exit;
}

$vehicleNo = $conn->real_escape_string($data['vehicle_no']);
$customerName = $conn->real_escape_string($data['customer_name']);
$mobileNo = $conn->real_escape_string($data['mobile']);

$sql_check = "SELECT * FROM customers WHERE vehicle_no = '$vehicleNo' LIMIT 1";
$result = $conn->query($sql_check);

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Vehicle number already exists']);
} else {
    $sql = "INSERT INTO customers (vehicle_no, customer_name, mobile) VALUES ('$vehicleNo', '$customerName', '$mobileNo')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }
}

$conn->close();
?>