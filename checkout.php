<?php
require_once 'includes/functions.php';
require_once 'includes/mpesa.php';

$cart_items = getCartItems();
$cart_total = getCartTotal();

if (empty($cart_items)) {
    header('Location: cart.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $payment_method = $_POST['payment_method'];
    
    // Create order
    $order_number = generateOrderNumber();
    $stmt = $pdo->prepare("INSERT INTO orders (order_number, customer_name, customer_phone, customer_email, total_amount, payment_method) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$order_number, $name, $phone, $email, $cart_total, $payment_method]);
    $order_id = $pdo->lastInsertId();
    
    // Add order items
    foreach ($cart_items as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
    }
    
    // Process M-Pesa payment
    if ($payment_method == 'mpesa') {
        $mpesa = new Mpesa();
        $response = $mpesa->stkPush($phone, $cart_total, $order_number);
        
        if ($response && isset($response->CheckoutRequestID)) {
            $_SESSION['pending_order'] = [
                'order_id' => $order_id,
                'order_number' => $order_number,
                'checkout_id' => $response->CheckoutRequestID
            ];
            
            header('Location: payment-pending.php');
            exit;
        } else {
            $error = "Failed to initiate M-Pesa payment. Please try again.";
        }
    } else {
        // Cash on delivery
        $_SESSION['cart'] = [];
        header('Location: order-success.php?order=' . $order_number);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Yetu</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <div class="container">
            <h1>Checkout</h1>
            
            <div class="checkout-container">
                <div class="checkout-form">
                    <h2>Shipping Information</h2>
                    <?php if(isset($error)): ?>
                        <div class="error-message"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Phone Number (for M-Pesa)</label>
                            <input type="tel" name="phone" pattern="[0-9]{10,12}" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Payment Method</label>
                            <select name="payment_method" required>
                                <option value="mpesa">M-Pesa (Lipa Na M-Pesa)</option>
                                <option value="cod">Cash on Delivery</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn-place-order">Place Order</button>
                    </form>
                </div>
                
                <div class="order-summary">
                    <h2>Order Summary</h2>
                    <?php foreach($cart_items as $item): ?>
                        <div class="summary-item">
                            <span><?php echo $item['name']; ?> x <?php echo $item['quantity']; ?></span>
                            <span><?php echo formatPrice($item['subtotal']); ?></span>
                        </div>
                    <?php endforeach; ?>
                    <div class="summary-total">
                        <strong>Total</strong>
                        <strong><?php echo formatPrice($cart_total); ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <style>
        .checkout-container {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
            margin-top: 30px;
        }
        
        .checkout-form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .order-summary {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            height: fit-content;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .summary-total {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f57224;
            font-size: 18px;
        }
        
        .btn-place-order {
            width: 100%;
            padding: 15px;
            background: #f57224;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }
        
        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
