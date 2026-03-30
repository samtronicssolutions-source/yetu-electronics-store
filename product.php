
<?php
require_once 'includes/functions.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = getProduct($id);

if (!$product) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>Product not found</h1>';
    exit;
}

// Get related products from same category
$related_products = getProducts($product['category_id'], 4);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($product['name']); ?> - <?php echo htmlspecialchars(substr($product['description'], 0, 150)); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($product['name']); ?>, yetu, <?php echo htmlspecialchars($product['category_name']); ?>">
    <title><?php echo htmlspecialchars($product['name']); ?> - Yetu</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin: 40px 0;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .product-gallery {
            position: relative;
        }
        .product-main-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
            cursor: zoom-in;
        }
        .product-info h1 {
            font-size: 28px;
            margin-bottom: 15px;
            color: #333;
        }
        .product-price {
            font-size: 32px;
            color: #f57224;
            font-weight: bold;
            margin: 20px 0;
        }
        .product-description {
            margin: 20px 0;
            line-height: 1.6;
            color: #666;
        }
        .product-meta {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .stock-status {
            color: #27ae60;
            font-weight: bold;
        }
        .out-of-stock {
            color: #e74c3c;
        }
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 20px 0;
        }
        .quantity-selector input {
            width: 80px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }
        .btn-add-to-cart-large {
            width: 100%;
            padding: 15px;
            background: #f57224;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-add-to-cart-large:hover {
            background: #e05e1a;
        }
        .related-products {
            margin-top: 50px;
        }
        @media (max-width: 768px) {
            .product-detail {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <div class="container">
            <div class="product-detail">
                <div class="product-gallery">
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-main-image" id="mainImage">
                </div>
                
                <div class="product-info">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    <div class="product-meta">
                        <span>Category: <a href="category.php?id=<?php echo $product['category_id']; ?>"><?php echo htmlspecialchars($product['category_name']); ?></a></span>
                    </div>
                    <div class="product-price">
                        <?php echo formatPrice($product['price']); ?>
                    </div>
                    <div class="product-description">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </div>
                    <div class="stock-status <?php echo $product['stock'] > 0 ? '' : 'out-of-stock'; ?>">
                        <?php if($product['stock'] > 0): ?>
                            <i class="fas fa-check-circle"></i> In Stock (<?php echo $product['stock']; ?> available)
                        <?php else: ?>
                            <i class="fas fa-times-circle"></i> Out of Stock
                        <?php endif; ?>
                    </div>
                    
                    <?php if($product['stock'] > 0): ?>
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <div class="quantity-selector">
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>">
                        </div>
                        <button type="submit" name="add_to_cart" class="btn-add-to-cart-large">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if(!empty($related_products)): ?>
            <div class="related-products">
                <h2 class="section-title">Related Products</h2>
                <div class="products-grid">
                    <?php foreach($related_products as $related): ?>
                        <?php if($related['id'] != $product['id']): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <a href="product.php?id=<?php echo $related['id']; ?>">
                                    <img src="<?php echo $related['image']; ?>" alt="<?php echo htmlspecialchars($related['name']); ?>">
                                </a>
                            </div>
                            <div class="product-info">
                                <h3><a href="product.php?id=<?php echo $related['id']; ?>"><?php echo htmlspecialchars($related['name']); ?></a></h3>
                                <p class="price"><?php echo formatPrice($related['price']); ?></p>
                                <form action="cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $related['id']; ?>">
                                    <button type="submit" name="add_to_cart" class="btn-add-to-cart">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
    
    <script>
        // Image zoom functionality
        const mainImage = document.getElementById('mainImage');
        if(mainImage) {
            mainImage.addEventListener('mousemove', function(e) {
                const { left, top, width, height } = this.getBoundingClientRect();
                const x = (e.clientX - left) / width;
                const y = (e.clientY - top) / height;
                
                this.style.transformOrigin = `${x * 100}% ${y * 100}%`;
                this.style.transform = 'scale(1.5)';
            });
            
            mainImage.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        }
        
        // Quantity validation
        const quantityInput = document.getElementById('quantity');
        if(quantityInput) {
            quantityInput.addEventListener('change', function() {
                const max = parseInt(this.getAttribute('max'));
                let value = parseInt(this.value);
                if(value > max) this.value = max;
                if(value < 1) this.value = 1;
            });
        }
    </script>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
