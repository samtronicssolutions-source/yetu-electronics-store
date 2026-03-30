<?php
require_once '../includes/functions.php';

if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Get product image to delete
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    
    // Delete product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    if ($stmt->execute([$id])) {
        // Delete image file
        if ($product && file_exists('../' . $product['image'])) {
            unlink('../' . $product['image']);
        }
        header('Location: index.php?deleted=1');
        exit;
    }
}

header('Location: index.php');
exit;
?>
