<?php
// Database connection details
$servername = "localhost";   
$username = "root";         
$password = "";              
$dbname = "garage";         

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the item ID from the URL
$id = $_GET['id'];

// SQL query to delete the item
$sql = "DELETE FROM inventory WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "Item deleted successfully";
    header("Location: inventorymanagement.php"); // Redirect to the inventory page
} else {
    echo "Error deleting item: " . $conn->error;
}

// Close connection
$conn->close();
?>
