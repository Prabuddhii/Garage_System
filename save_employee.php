<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "garage";

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $employee_name = $_POST['employee_name'];
    $position = $_POST['position'];
    $mobile = $_POST['mobile'];
    
   

    // Validate that all fields are provided
    if (empty($employee_name) || empty($position) || empty($mobile)  ) {
        die("All fields are required.");
    }

    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO employees (employee_name, position, mobile) VALUES (?, ?, ?)");
    
    // Check if the query preparation failed
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters to the prepared statement
    
    $stmt->bind_param("sss", $employee_name, $position, $mobile);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect or show success message
        echo "<script>alert('Employee added successfully'); window.location.href = 'employee.php';</script>";
    } else {
        // Show error message
        echo "Error: " . $stmt->error;
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}
?>
