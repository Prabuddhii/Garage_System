<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "garage";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Read the JSON POST input
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Validate required fields
if (empty($data['invoice_date']) || empty($data['customer_name']) || empty($data['vehicle_no']) || 
    !isset($data['total_amount']) || empty($data['mobile_no']) || empty($data['items']) || 
    empty($data['payment_method']) || !isset($data['paid_amount'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Parse and validate the invoice date
$invoiceDate = $data['invoice_date'];
try {
    $dateObject = new DateTime($invoiceDate);
    $formattedDate = $dateObject->format('Y-m-d H:i:s');
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Invalid date format']);
    exit;
}

$customerName = $data['customer_name'];
$vehicleNo = $data['vehicle_no'];
$mobileNo = $data['mobile_no'];
$paymentMethod = $data['payment_method'];
$totalAmount = floatval($data['total_amount']);
$paidAmount = floatval($data['paid_amount']);
$cardNumber = $data['card_number'] ?? null; // Optional
$accountNumber = $data['account_number'] ?? null; // Optional
$transactionId = $data['transaction_id'] ?? null; // Optional
$items = $data['items'];

// Validate payment method
$validPaymentMethods = ['cash', 'credit_card', 'bank_transfer', 'online_payment'];
if (!in_array($paymentMethod, $validPaymentMethods)) {
    echo json_encode(['success' => false, 'message' => 'Invalid payment method']);
    exit;
}

// Debug: Log received data
file_put_contents('debug.log', "Received Data: " . print_r($data, true) . "\n", FILE_APPEND);

try {
    $pdo->beginTransaction();

    // Insert the invoice with additional payment details
    $stmt = $pdo->prepare("INSERT INTO invoice (invoice_date, customer_name, vehicle_no, mobile_no, payment_method, total_amount, paid_amount, card_number, account_number, transaction_id) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$formattedDate, $customerName, $vehicleNo, $mobileNo, $paymentMethod, $totalAmount, $paidAmount, $cardNumber, $accountNumber, $transactionId]);

    $invoiceId = $pdo->lastInsertId();

    foreach ($items as $item) {
        $type = $item['type'];
        $name = $item['name'];
        $made = $item['made'] ?? null;
        $model = $item['model'] ?? null;
        $price = floatval($item['price']);
        $quantity = intval($item['quantity']);
        $total = $price * $quantity;

        $itemId = null;
        if ($type === 'item') {
            $stmt = $pdo->prepare("SELECT id FROM inventory WHERE item_name = ? AND made_by = ? AND model = ?");
            $stmt->execute([$name, $made, $model]);
            $itemId = $stmt->fetchColumn();

            if (!$itemId) {
                throw new Exception("Inventory item not found for name: $name, made: $made, model: $model");
            }
        }

        $stmt = $pdo->prepare("INSERT INTO invoice_items (invoice_id, item_id, type, name, made, model, price, quantity, total) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$invoiceId, $itemId, $type, $name, $made, $model, $price, $quantity, $total]);

        if ($type === 'item') {
            $updateStmt = $pdo->prepare("UPDATE inventory SET stock = stock - ? WHERE id = ?");
            $updateStmt->execute([$quantity, $itemId]);
        }
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'invoice_id' => $invoiceId, 'message' => 'Invoice saved successfully.']);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>