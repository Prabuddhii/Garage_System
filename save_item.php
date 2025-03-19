<?php
// Database connection details
$servername = "localhost";  // Replace with your server name
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "garage";         // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form data
    $item_name = trim($_POST['item_name']);
    $made_by = trim($_POST['made_by']);
    $type = trim($_POST['type']);      // Added type field
    $model = trim($_POST['model']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Validate required fields
    if (empty($item_name) || empty($made_by) || empty($type) || empty($model) || empty($price) || empty($stock)) {
        echo "All fields are required!";
        exit();
    }

    // Validate price and stock to be numeric
    if (!is_numeric($price) || !is_numeric($stock)) {
        echo "Price and stock must be numeric!";
        exit();
    }

    // Prepare and execute SQL to check if item already exists (optional)
    $stmt = $conn->prepare("SELECT * FROM inventory WHERE item_name = ? AND model = ? AND type = ?");
    $stmt->bind_param("sss", $item_name, $model, $type);  // Added type to check
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if the item already exists
    if ($result->num_rows > 0) {
        echo "Item already exists in inventory!";
    } else {
        // Prepare SQL query to insert new item into inventory table
        $stmt = $conn->prepare("INSERT INTO inventory (item_name, made_by, type, model, price, stock) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssii", $item_name, $made_by, $type, $model, $price, $stock);  // Added type to insert

        // Execute the query and check for success
        if ($stmt->execute()) {
            // Redirect or show success message
            echo "<script>alert('Item added successfully'); window.location.href = 'inventorymanagement.php';</script>";
        } else {
            // Show error message
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>