<?php
// Set the content type to JSON
header('Content-Type: application/json');

// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Update with your actual password if applicable
$dbname = "garage";

// Enable error reporting for debugging (remove or disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Create database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute query to fetch inventory
    $sql = "SELECT item_name, made_by, type, model, price FROM inventory";
    $result = $conn->query($sql);

    // Check if query was successful
    if ($result === false) {
        throw new Exception("Query failed: " . $conn->error);
    }

    // Fetch data into an array
    $inventory = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $inventory[] = [
                'item_name' => $row['item_name'],
                'made_by' => $row['made_by'],
                'type' => $row['type'],
                'model' => $row['model'],
                'price' => floatval($row['price']) // Ensure price is a number
            ];
        }
    }

    // Return JSON response
    if (empty($inventory)) {
        http_response_code(404);
        echo json_encode(['error' => 'No inventory items found']);
    } else {
        http_response_code(200);
        echo json_encode(['inventory' => $inventory]);
    }

} catch (Exception $e) {
    // Handle any errors and return a JSON error response
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    // Close the connection if it exists
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>