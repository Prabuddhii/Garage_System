<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $service_name = $_POST['service_name'];
    $price_small = $_POST['price_small'];
    $price_medium = $_POST['price_medium'];
    $price_large = $_POST['price_large'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'garage');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update query to update all price categories
    $sql = "UPDATE services SET service_name = ?, price_small = ?, price_medium = ?, price_large = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdddd", $service_name, $price_small, $price_medium, $price_large, $id);

    if ($stmt->execute()) {
        header("Location: Services.php"); // Redirect to the services page after successful update
    } else {
        echo "Error updating service.";
    }

    $stmt->close();
    $conn->close();
}
?>
