<?php
// Set the content type to JSON
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "garage";

try {
    // Create a PDO connection
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get invoice ID from query parameter
    $invoice_id = isset($_GET['invoice_id']) && is_numeric($_GET['invoice_id']) ? (int)$_GET['invoice_id'] : 0;

    if ($invoice_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid invoice ID']);
        exit;
    }

    // Fetch invoice details
    $stmt = $pdo->prepare("SELECT * FROM invoice WHERE id = ?");
    $stmt->execute([$invoice_id]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$invoice) {
        echo json_encode(['success' => false, 'message' => 'Invoice not found']);
        exit;
    }

    // Fetch invoice items
    $stmt = $pdo->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
    $stmt->execute([$invoice_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare response data
    $response = [
        'success' => true,
        'invoice' => [
            'invoice_id' => $invoice['id'],
            'date' => $invoice['invoice_date'],
            'customer_name' => $invoice['customer_name'],
            'vehicle_no' => $invoice['vehicle_no'],
            'mobile_no' => $invoice['mobile_no'],
            'payment_method' => $invoice['payment_method'],
            'card_number' => $invoice['card_number'],
            'account_number' => $invoice['account_number'],
            'transaction_id' => $invoice['transaction_id'],
            'total_amount' => (float)$invoice['total_amount'],
            'paid_amount' => (float)$invoice['paid_amount']
        ],
        'items' => $items
    ];

    // Output JSON response
    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>