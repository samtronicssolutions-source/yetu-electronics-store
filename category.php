<?php
require_once 'includes/functions.php';

$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$category = null;

if($category_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$category_id]);
    $category = $stmt->fetch();
}

$products = getProducts($category_id);
$categories = getCategories();
$subcategories = $category_id ? getCategories($category_id) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Shop <?php echo $category ? htmlspecialchars($category['name']) : 'All'; ?> products at Yetu - Quality products at best prices">
    <title><?php echo $category ? htmlspecialchars($category['name']) : 'All Products'; ?> - Yetu</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .category-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
            margin: 30px 0;
        }
        .sidebar {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        .sidebar h3 {
            margin-bottom: 15px;
            color: #333;
            border-bottom: 2px solid #f57224;
            padding-bottom: 10px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin-bottom: 10px;
        }
        .sidebar ul li a {
            color: #666;
            text-decoration: none;
            display: block;
            padding: 8px 10px;
            transition: all 0.3s;
        }
        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            color: #f57224;
            padding-left: 15px;
        }
        .filter-section {
            margin-bottom: 25px;
        }
        .price-range {
            margin-top: 10px;
        }
        .price-range input {
            width: 100%;
            margin: 10px 0;
        }
        .price-values {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #666;
        }
        .sort-bar {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .sort-select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .results-count {
            color: #666;
        }
        .no-products {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 10px;
        }
        @media (max-width: 768px) {
            .category-layout {
                grid-template-columns: 1fr;
            }
            .sidebar {
                position: static;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <div class="container">
            <div class="category-layout">
                <aside class="sidebar">
                    <div class="filter-section">
                        <h3>Categories</h3>
                        <ul>
                            <li><a href="category.php" <?php echo !$category_id ? 'class="active"' : ''; ?>>All Products</a></li>
                            <?php foreach($categories as $cat): ?>
                            <li>
                                <a href="category.php?id=<?php echo $cat['id']; ?>" 
                                   <?php echo $category_id == $cat['id'] ? 'class="active"' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </a>
                                <?php 
                                $subs = getCategories($cat['id']);
                                if($subs && $category_id == $cat['id']): 
                                ?>
                                <ul style="margin-left: 20px; margin-top: 5px;">
                                    <?php foreach($subs as $sub): ?>
                                    <li>
                                        <a href="category.php?id=<?php echo $sub['id']; ?>">
                                            <?php echo htmlspecialchars($sub['name']); ?>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <div class="filter-section">
                        <h3>Price Range</h3>
                        <div class="price-range">
                            <input type="range" id="priceMin" min="0" max="50000" step="1000" value="0">
                            <input type="range" id="priceMax" min="0" max="50000" step="1000" value="50000">
                            <div class="price-values">
                                <span>KSh 0</span>
                                <span>KSh 50,000+</span>
                            </div>
                        </div>
                    </div>
                </aside>
                
                <div class="main-content">
                    <div class="sort-bar">
                        <div class="results-count">
                            <?php echo count($products); ?> products found
                        </div>
                        <select class="sort-select" id="sortBy">
                            <option value="newest">Newest First</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="popular">Most Popular</option>
                        </select>
                    </div>
                    
                    <?php if(empty($products)): ?>
                        <div class="no-products">
                            <i class="fas fa-box-open" style="font-size: 64px; color: #ccc;"></i>
                            <h3>No products found in this category</h3>
                            <p>Check back later for new products!</p>
                            <a href="category.php" class="btn-primary" style="margin-top: 20px; display: inline-block;">Browse All Products</a>
                        </div>
                    <?php else: ?>
                        <div class="products-grid" id="productsGrid">
                            <?php foreach($products as $product): ?>
                            <div class="product-card" data-price="<?php echo $product['price']; ?>" data-date="<?php echo $product['created_at']; ?>">
                                <div class="product-image">
                                    <a href="product.php?id=<?php echo $product['id']; ?>">
                                        <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    </a>
                                </div>
                                <div class="product-info">
                                    <h3><a href="product.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a></h3>
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    
    <script>
        // Sorting functionality
        const sortSelect = document.getElementById('sortBy');
        const productsGrid = document.getElementById('productsGrid');
        
        if(sortSelect && productsGrid) {
            sortSelect.addEventListener('change', function() {
                const products = Array.from(productsGrid.children);
                const sortBy = this.value;
                
                products.sort((a, b) => {
                    if(sortBy === 'price_low') {
                        return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                    } else if(sortBy === 'price_high') {
                        return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                    } else if(sortBy === 'newest') {
                        return new Date(b.dataset.date) - new Date(a.dataset.date);
                    }
                    return 0;
                });
                
                products.forEach(product => productsGrid.appendChild(product));
            });
        }
        
        // Price range filter
        const priceMin = document.getElementById('priceMin');
        const priceMax = document.getElementById('priceMax');
        
        if(priceMin && priceMax) {
            function filterByPrice() {
                const min = parseFloat(priceMin.value);
                const max = parseFloat(priceMax.value);
                const products = document.querySelectorAll('.product-card');
                
                products.forEach(product => {
                    const price = parseFloat(product.dataset.price);
                    if(price >= min && price <= max) {
                        product.style.display = '';
                    } else {
                        product.style.display = 'none';
                    }
                });
            }
            
            priceMin.addEventListener('input', filterByPrice);
            priceMax.addEventListener('input', filterByPrice);
        }
    </script>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
