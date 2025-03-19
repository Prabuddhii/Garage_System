<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "garage";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

if (isset($_GET['term'])) {
    $term = $_GET['term'];
    $stmt = $conn->prepare("SELECT DISTINCT vehicle_no FROM customers WHERE vehicle_no LIKE ? LIMIT 10");
    $likeTerm = $term . '%';
    $stmt->bind_param("s", $likeTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicleNumbers = [];
    while ($row = $result->fetch_assoc()) {
        $vehicleNumbers[] = $row['vehicle_no'];
    }
    echo json_encode($vehicleNumbers);
    $stmt->close();
} else {
    echo json_encode(['error' => 'Missing term parameter']);
}

$conn->close();
?>