<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$database = "garage";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

$filterType = $_POST['filter_type'] ?? 'daily';
$filterValue = $_POST['filter_value'] ?? date('Y-m-d');

$sql = "";
switch ($filterType) {
    case 'daily':
        $sql = "SELECT invoice_date AS date, customer_name, total_amount 
                FROM invoice 
                WHERE DATE(invoice_date) = ?";
        break;
    case 'weekly':
        $sql = "SELECT invoice_date AS date, customer_name, total_amount 
                FROM invoice 
                WHERE YEARWEEK(invoice_date) = YEARWEEK(?)";
        break;
    case 'monthly':
        $sql = "SELECT invoice_date AS date, customer_name, total_amount 
                FROM invoice 
                WHERE YEAR(invoice_date) = ? AND MONTH(invoice_date) = ?";
        $filterValueParts = explode('-', $filterValue);
        $year = $filterValueParts[0];
        $month = $filterValueParts[1];
        break;
    case 'yearly':
        $sql = "SELECT invoice_date AS date, customer_name, total_amount 
                FROM invoice 
                WHERE YEAR(invoice_date) = ?";
        break;
}

$stmt = $conn->prepare($sql);
if ($filterType === 'monthly') {
    $stmt->bind_param("ii", $year, $month);
} else {
    $stmt->bind_param("s", $filterValue);
}
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($data);
?>