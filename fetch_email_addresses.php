<?php
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

if (isset($_GET['customer_name']) && isset($_GET['vehicle_no']) && isset($_GET['mobile_no'])) {
    $customerName = $_GET['customer_name'];
    $vehicleNo = $_GET['vehicle_no'];
    $mobileNo = $_GET['mobile_no'];

    // Prepare the SQL statement to fetch email addresses for the selected customer, vehicle, and mobile
    $stmt = $conn->prepare("SELECT email FROM customers WHERE customer_name = ? AND vehicle_no = ? AND mobile = ?");
    $stmt->bind_param("sss", $customerName, $vehicleNo, $mobileNo);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Initialize an array to store email addresses
    $emailAddresses = [];

    // Fetch all email addresses
    while ($row = $result->fetch_assoc()) {
        $emailAddresses[] = $row['email'];
    }

    // Return the result as a JSON
    echo json_encode($emailAddresses);
}

// Close the connection
$conn->close();
?>
