<?php
// Get cart count
$cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
$categories = getCategories();
?>
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
                    <span class="cart-count"><?php echo $cart_count; ?></span>
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
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                    <?php 
                    $subcats = getCategories($cat['id']);
                    if($subcats): 
                    ?>
                    <ul class="dropdown-menu">
                        <?php foreach($subcats as $subcat): ?>
                        <li><a href="category.php?id=<?php echo $subcat['id']; ?>"><?php echo htmlspecialchars($subcat['name']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>
</header>
