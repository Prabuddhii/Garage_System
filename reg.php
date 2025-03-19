<?php
// Database connection settings
$servername = "localhost"; // Server name
$username = "root"; // Your database username (default for localhost is root)
$password = ""; // Your database password (default for localhost is empty)
$dbname = "garage"; // Your database name

// Create the connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for any connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);

    // Check if the passwords match
    if ($password !== $cpassword) {
        echo "Passwords do not match!";
    } else {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if the email already exists
        $sql_check_email = "SELECT * FROM user_form WHERE email = '$email'";
        $result = $conn->query($sql_check_email);

        if ($result->num_rows > 0) {
            echo "Email is already registered!";
        } else {
            // Insert the new user into the database
            $sql = "INSERT INTO user_form (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";

            if ($conn->query($sql) === TRUE) {
                // Redirect to login page after successful registration
                header("Location: login.html");  // Redirect to login page
                exit(); // Make sure to call exit after the header redirect
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

// Close the connection
$conn->close();
?>
