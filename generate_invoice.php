<?php
header('Content-Type: text/html; charset=UTF-8');

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "garage";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get invoice ID from query parameter
$invoice_id = isset($_GET['invoice_id']) && is_numeric($_GET['invoice_id']) ? (int)$_GET['invoice_id'] : 0;

// Fetch invoice details
$sql = "SELECT * FROM invoice WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $invoice_id);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();

// Fetch invoice items
if ($invoice) {
    $sqlItems = "SELECT * FROM invoice_items WHERE invoice_id = ?";
    $stmtItems = $conn->prepare($sqlItems);
    $stmtItems->bind_param('i', $invoice_id);
    $stmtItems->execute();
    $resultItems = $stmtItems->get_result();
    $items = $resultItems->fetch_all(MYSQLI_ASSOC);

    // Start output buffering
    ob_start();
    echo "<!DOCTYPE html>";
    echo "<html><head>";
    echo "<title>Invoice #" . htmlspecialchars($invoice_id) . "</title>";
    echo "<style>";
    echo "body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: #f8f9fa; color: #333; }";
    echo ".invoice-box { background: #fff; padding: 20px 40px; margin: 40px auto; max-width: 800px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }";
    echo "h1 { font-size: 24px; color: #4A90E2; text-align: center; margin: 20px 0; }";
    echo "h2 { font-size: 20px; color: #333; margin: 20px 0 10px; }";
    echo "p { font-size: 16px; margin: 5px 0; }";
    echo "table { width: 100%; border-collapse: collapse; margin-top: 20px; }";
    echo "th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }";
    echo "th { background: #f4f4f4; font-weight: bold; }";
    echo ".total-container { text-align: right; margin-top: 20px; font-size: 18px; font-weight: bold; }";
    echo ".header-logo { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }";
    echo ".header-logo img { height: 80px; }";
    echo ".payment-details { margin-top: 20px; }";
    echo "</style>";
    echo "</head><body>";

    echo "<div class='invoice-box'>";
    echo "<div class='header-logo'>";
    echo "<img src='logo.png' alt='SINHE Auto Zone Logo'>";
    echo "<img src='invoice.png' alt='Invoice'>";
    echo "</div>";
    echo "<h1>Invoice #" . htmlspecialchars($invoice_id) . "</h1>";
    echo "<p>Date & Time: " . htmlspecialchars($invoice['invoice_date'] ?? date('Y-m-d H:i:s')) . "</p>";
    echo "<p>Customer Name: " . htmlspecialchars($invoice['customer_name'] ?? 'N/A') . "</p>";
    echo "<p>Vehicle No: " . htmlspecialchars($invoice['vehicle_no'] ?? 'N/A') . "</p>";
    echo "<p>Mobile No: " . htmlspecialchars($invoice['mobile_no'] ?? 'N/A') . "</p>";
    echo "<p>Payment Method: " . htmlspecialchars(ucfirst(str_replace('_', ' ', $invoice['payment_method'] ?? 'N/A'))) . "</p>";
    
   

    echo "<h2>Items and Services</h2>";
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Type</th>";
    echo "<th>Name</th>";
    echo "<th>Made</th>";
    echo "<th>Model</th>";
    echo "<th>Price</th>";
    echo "<th>Quantity</th>";
    echo "<th>Total</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($items as $item) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars(ucfirst($item['type'])) . "</td>";
        echo "<td>" . htmlspecialchars($item['name']) . "</td>";
        echo "<td>" . htmlspecialchars($item['made'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($item['model'] ?? 'N/A') . "</td>";
        echo "<td>Rs." . number_format($item['price'], 2) . "</td>";
        echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
        echo "<td>Rs." . number_format($item['total'], 2) . "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";

   

    

    // Total, Paid Amount, and Balance
    echo "<div class='total-container'>";
    echo "<p>Total Amount: Rs." . number_format($invoice['total_amount'] ?? 0, 2) . "</p>";

    echo "</div>";

    
    echo "</div>";
    echo "</div>";
    echo "</body></html>";

    $htmlContent = ob_get_clean();
    $directory = 'invoices';
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }
    $filePath = $directory . '/Invoice_' . $invoice_id . '.html';

    if (file_put_contents($filePath, $htmlContent) === false) {
        die("Failed to save the invoice file.");
    }

    echo $htmlContent;
} else {
    echo "Invoice not found.";
}

$stmt->close();
$stmtItems->close();
$conn->close();
?>