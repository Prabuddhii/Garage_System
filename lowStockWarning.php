<?php
// Enable error reporting for debugging (optional)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "garage";

// Fetch low stock items (stock count < 5)
$query = "SELECT item_name, stock_count FROM inventory WHERE stock_count < 5";
$result = mysqli_query($conn, $query);

$lowStockItems = []; // Initialize an empty array

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $lowStockItems[] = [
            "name" => $row['item_name'], 
            "stock" => $row['stock_count']
        ];
    }
}

// Set the correct JSON content type
header('Content-Type: application/json');

// Encode the array into JSON and output it
echo json_encode($lowStockItems);
?>
