<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

$data = file_get_contents('php://input');
$response = json_decode($data, true);

if ($response && isset($response['Body']['stkCallback'])) {
    $callback = $response['Body']['stkCallback'];
    $resultCode = $callback['ResultCode'];
    $checkoutRequestID = $callback['CheckoutRequestID'];
    $merchantRequestID = $callback['MerchantRequestID'];
    
    if ($resultCode == 0) {
        // Payment successful
        $callbackMetadata = $callback['CallbackMetadata']['Item'];
        $amount = 0;
        $mpesaReceiptNumber = '';
        
        foreach ($callbackMetadata as $item) {
            if ($item['Name'] == 'Amount') {
                $amount = $item['Value'];
            }
            if ($item['Name'] == 'MpesaReceiptNumber') {
                $mpesaReceiptNumber = $item['Value'];
            }
        }
        
        // Update order
        $stmt = $pdo->prepare("UPDATE orders 
                               SET payment_status = 'completed', 
                                   mpesa_transaction_id = ?,
                                   status = 'processing'
                               WHERE order_number = (
                                   SELECT order_number FROM orders 
                                   WHERE mpesa_transaction_id IS NULL 
                                   ORDER BY id DESC LIMIT 1
                               )");
        $stmt->execute([$mpesaReceiptNumber]);
        
        // Clear cart
        session_start();
        unset($_SESSION['cart']);
        
        // Log success
        file_put_contents('mpesa_log.txt', date('Y-m-d H:i:s') . " - Payment successful: $mpesaReceiptNumber\n", FILE_APPEND);
    } else {
        // Payment failed
        $stmt = $pdo->prepare("UPDATE orders 
                               SET payment_status = 'failed'
                               WHERE order_number = (
                                   SELECT order_number FROM orders 
                                   WHERE payment_status = 'pending' 
                                   ORDER BY id DESC LIMIT 1
                               )");
        $stmt->execute();
        
        // Log failure
        file_put_contents('mpesa_log.txt', date('Y-m-d H:i:s') . " - Payment failed: ResultCode $resultCode\n", FILE_APPEND);
    }
}

echo json_encode(['ResultCode' => 0, 'ResultDesc' => 'Success']);
?>
