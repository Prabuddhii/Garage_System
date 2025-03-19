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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $service_name = trim($_POST['service_name']);
    $price_small = trim($_POST['price_small']) !== '' ? floatval($_POST['price_small']) : null;
    $price_medium = trim($_POST['price_medium']) !== '' ? floatval($_POST['price_medium']) : null;
    $price_large = trim($_POST['price_large']) !== '' ? floatval($_POST['price_large']) : null;
    $price_mini_car = trim($_POST['price_mini_car']) !== '' ? floatval($_POST['price_mini_car']) : null;
    $price_car = trim($_POST['price_car']) !== '' ? floatval($_POST['price_car']) : null;
    $price_mini_van = trim($_POST['price_mini_van']) !== '' ? floatval($_POST['price_mini_van']) : null;
    $price_cab = trim($_POST['price_cab']) !== '' ? floatval($_POST['price_cab']) : null;
    $price_van = trim($_POST['price_van']) !== '' ? floatval($_POST['price_van']) : null;
    $price_jeep = trim($_POST['price_jeep']) !== '' ? floatval($_POST['price_jeep']) : null;
    $price_hiroof = trim($_POST['price_hiroof']) !== '' ? floatval($_POST['price_hiroof']) : null;
    $price_mini_lorry_h = trim($_POST['price_mini_lorry_h']) !== '' ? floatval($_POST['price_mini_lorry_h']) : null;
    $price_mini_lorry = trim($_POST['price_mini_lorry']) !== '' ? floatval($_POST['price_mini_lorry']) : null;
    $price_lorry_h = trim($_POST['price_lorry_h']) !== '' ? floatval($_POST['price_lorry_h']) : null;
    $price_lorry = trim($_POST['price_lorry']) !== '' ? floatval($_POST['price_lorry']) : null;
    $price_bus = trim($_POST['price_bus']) !== '' ? floatval($_POST['price_bus']) : null;

    // Validate required field: service_name
    if (empty($service_name)) {
        echo "<script>alert('Service name is required!'); window.history.back();</script>";
        exit();
    }

    // Validate provided prices (optional fields can be empty, but if filled, must be non-negative)
    $prices = [
        $price_small, $price_medium, $price_large, $price_mini_car, $price_car, 
        $price_mini_van, $price_cab, $price_van, $price_jeep, $price_hiroof, 
        $price_mini_lorry_h, $price_mini_lorry, $price_lorry_h, $price_lorry, $price_bus
    ];

    foreach ($prices as $price) {
        if ($price !== null && (!is_numeric($price) || $price < 0)) {
            echo "<script>alert('Prices must be numeric and non-negative!'); window.history.back();</script>";
            exit();
        }
    }

    // Check if the service already exists (by name)
    $stmt = $conn->prepare("SELECT id FROM services WHERE service_name = ?");
    $stmt->bind_param("s", $service_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If service exists, show an error message
        echo "<script>alert('Service already exists!'); window.history.back();</script>";
    } else {
        // Insert the new service with all price options (null for unfilled fields)
        $stmt = $conn->prepare("
            INSERT INTO services (
                service_name, price_small, price_medium, price_large, price_mini_car, 
                price_car, price_mini_van, price_cab, price_van, price_jeep, 
                price_hiroof, price_mini_lorry_h, price_mini_lorry, price_lorry_h, 
                price_lorry, price_bus
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "sddddddddddddddd", 
            $service_name, $price_small, $price_medium, $price_large, $price_mini_car, 
            $price_car, $price_mini_van, $price_cab, $price_van, $price_jeep, 
            $price_hiroof, $price_mini_lorry_h, $price_mini_lorry, $price_lorry_h, 
            $price_lorry, $price_bus
        );

        if ($stmt->execute()) {
            // Redirect or show success message
            echo "<script>alert('Service added successfully'); window.location.href = 'services.php';</script>";
        } else {
            // Show error message
            echo "<script>alert('Error adding service: " . addslashes($stmt->error) . "'); window.history.back();</script>";
        }

        $stmt->close();
    }
}

// Close connection
$conn->close();
?>