<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'garage';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database Connection Failed: " . $conn->connect_error]));
}

if (isset($_GET['vehicle_no']) && !empty($_GET['vehicle_no'])) {
    $vehicle_no = trim($_GET['vehicle_no']);
    $stmt = $conn->prepare("SELECT customer_name, mobile FROM customers WHERE vehicle_no = ?");
    $stmt->bind_param("s", $vehicle_no);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(["customer_name" => "", "mobile" => ""]);
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "Vehicle number is required"]);
}

$conn->close();
?>