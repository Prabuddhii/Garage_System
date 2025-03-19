<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$dbname = 'garage';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $vehicle_no = isset($_GET['vehicle_no']) ? $_GET['vehicle_no'] : '';
    $customer_name = isset($_GET['customer_name']) ? $_GET['customer_name'] : '';

    if (empty($vehicle_no) || empty($customer_name)) {
        echo json_encode([]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT mobile_no FROM customers WHERE vehicle_no = :vehicle_no AND customer_name = :customer_name");
    $stmt->execute([
        ':vehicle_no' => $vehicle_no,
        ':customer_name' => $customer_name
    ]);

    $mobileNumbers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($mobileNumbers);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>