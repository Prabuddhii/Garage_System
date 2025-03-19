<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "garage";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// Fetch services and their prices for all vehicle categories
$sql = "SELECT id, service_name, price_small, price_medium, price_large, price_mini_car, 
               price_car, price_mini_van, price_cab, price_van, price_jeep, 
               price_hiroof, price_mini_lorry_h, price_mini_lorry, price_lorry_h, 
               price_lorry, price_bus 
        FROM services";

$result = $conn->query($sql);

$services = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = [
            'id' => $row['id'],
            'name' => $row['service_name'],
            'price_small' => floatval($row['price_small']),
            'price_medium' => floatval($row['price_medium']),
            'price_large' => floatval($row['price_large']),
            'price_mini_car' => floatval($row['price_mini_car']),
            'price_car' => floatval($row['price_car']),
            'price_mini_van' => floatval($row['price_mini_van']),
            'price_cab' => floatval($row['price_cab']),
            'price_van' => floatval($row['price_van']),
            'price_jeep' => floatval($row['price_jeep']),
            'price_hiroof' => floatval($row['price_hiroof']),
            'price_mini_lorry_h' => floatval($row['price_mini_lorry_h']),
            'price_mini_lorry' => floatval($row['price_mini_lorry']),
            'price_lorry_h' => floatval($row['price_lorry_h']),
            'price_lorry' => floatval($row['price_lorry']),
            'price_bus' => floatval($row['price_bus'])
        ];
    }
}

$conn->close();

// Return data as JSON
header('Content-Type: application/json');
echo json_encode(['services' => $services]);
?>