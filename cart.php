<?php
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    addToCart($_POST['product_id']);
    header('Location: cart.php');
    exit;
}

if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $id => $qty) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }
    header('Location: cart.php');
    exit;
}

$cart_items = getCartItems();
$cart_total = getCartTotal();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Yetu</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <div class="container">
            <h1>Shopping Cart</h1>
            
            <?php if(empty($cart_items)): ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart" style="font-size: 64px; color: #ccc;"></i>
                    <h2>Your cart is empty</h2>
                    <a href="category.php" class="btn-primary">Continue Shopping</a>
                </div>
            <?php else: ?>
                <form action="" method="POST">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($cart_items as $item): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" style="width: 80px; height: 80px; object-fit: cover;">
                                    <?php echo $item['name']; ?>
                                </td>
                                <td><?php echo formatPrice($item['price']); ?></td>
                                <td>
                                    <input type="number" name="quantities[<?php echo $item['id']; ?>]" 
                                           value="<?php echo $item['quantity']; ?>" min="0" style="width: 60px;">
                                </td>
                                <td><?php echo formatPrice($item['subtotal']); ?></td>
                                <td>
                                    <a href="cart.php?remove=<?php echo $item['id']; ?>" class="btn-remove">Remove</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                                <td><strong><?php echo formatPrice($cart_total); ?></strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <div class="cart-actions">
                        <button type="submit" name="update_cart" class="btn-update">Update Cart</button>
                        <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
