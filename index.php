<?php
require_once 'includes/functions.php';

$featured_products = getProducts(null, 8);
$new_products = getProducts(null, 8);
$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Yetu - Your one-stop shop for quality audio, TV, accessories, and utensils in Kenya">
    <meta name="keywords" content="yetu, ecommerce, kenya, audio, tv, accessories, utensils">
    <meta name="author" content="Yetu">
    <title>Yetu - Quality Products for Every Home</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header-top">
            <div class="container">
                <div class="logo">
                    <h1><a href="/yetu/">Yetu</a></h1>
                </div>
                <div class="search-bar">
                    <form action="search.php" method="GET">
                        <input type="text" name="q" placeholder="What are you looking for?">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="header-actions">
                    <a href="cart.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count"><?php echo count($_SESSION['cart'] ?? []); ?></span>
                    </a>
                </div>
            </div>
        </div>
        <nav class="main-nav">
            <div class="container">
                <ul class="nav-menu">
                    <li><a href="/yetu/">Home</a></li>
                    <?php foreach($categories as $cat): ?>
                    <li class="dropdown">
                        <a href="category.php?id=<?php echo $cat['id']; ?>">
                            <?php echo $cat['name']; ?>
                        </a>
                        <?php 
                        $subcats = getCategories($cat['id']);
                        if($subcats): 
                        ?>
                        <ul class="dropdown-menu">
                            <?php foreach($subcats as $subcat): ?>
                            <li><a href="category.php?id=<?php echo $subcat['id']; ?>"><?php echo $subcat['name']; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <!-- Hero Banner -->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h2>Excellent Quality Only</h2>
                    <p>Nothing else - Quality products at the best prices</p>
                    <a href="category.php" class="btn-primary">Shop Now</a>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="products-section">
            <div class="container">
                <h2 class="section-title">Featured Products</h2>
                <div class="products-grid">
                    <?php foreach($featured_products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                        </div>
                        <div class="product-info">
                            <h3><a href="product.php?id=<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a></h3>
                            <p class="price"><?php echo formatPrice($product['price']); ?></p>
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" name="add_to_cart" class="btn-add-to-cart">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Categories Showcase -->
        <section class="categories-showcase">
            <div class="container">
                <h2 class="section-title">Shop by Category</h2>
                <div class="categories-grid">
                    <div class="category-item" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://via.placeholder.com/400x300?text=Audio');">
                        <h3>Audio</h3>
                        <a href="category.php?id=1" class="btn-category">Shop Now</a>
                    </div>
                    <div class="category-item" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://via.placeholder.com/400x300?text=TV');">
                        <h3>TV</h3>
                        <a href="category.php?id=2" class="btn-category">Shop Now</a>
                    </div>
                    <div class="category-item" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://via.placeholder.com/400x300?text=Accessories');">
                        <h3>Accessories</h3>
                        <a href="category.php?id=3" class="btn-category">Shop Now</a>
                    </div>
                    <div class="category-item" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://via.placeholder.com/400x300?text=Utensils');">
                        <h3>Utensils</h3>
                        <a href="category.php?id=4" class="btn-category">Shop Now</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3>About Yetu</h3>
                    <p>Your trusted partner for quality products in Kenya. We offer the best selection of audio, TV, accessories, and utensils.</p>
                </div>
                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="/yetu/">Home</a></li>
                        <li><a href="category.php">Shop</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="shipping.php">Shipping Info</a></li>
                        <li><a href="returns.php">Returns Policy</a></li>
                        <li><a href="faq.php">FAQ</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Contact Us</h3>
                    <p><i class="fas fa-phone"></i> +254 700 000 000</p>
                    <p><i class="fas fa-envelope"></i> info@yetu.com</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Yetu. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
