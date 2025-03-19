<?php
// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $id = $_POST['id'];
    $item_name = $_POST['item_name'];
    $made_by = $_POST['made_by'];
    $model = $_POST['model'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];

    // Connect to database
    $conn = new mysqli('localhost', 'root', '', 'garage');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the update query
    $sql = "UPDATE inventory SET item_name = ?, made_by = ?, model = ?, stock = ?, price = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdis", $item_name, $made_by, $model, $stock, $price, $id); // 's' = string, 'd' = double, 'i' = integer
    if ($stmt->execute()) {
        echo "Item updated successfully";
        header("Location: inventorymanagement.php"); // Redirect back to the inventory page
    } else {
        echo "Error updating item: " . $stmt->error;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
