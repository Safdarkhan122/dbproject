<?php
include 'includes/db_connect.php';
include 'includes/header.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart action
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    // If product is already in cart, increment quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        // Add product to cart with quantity 1
        $_SESSION['cart'][$product_id] = 1;
    }
    // Redirect to cart page to show the updated cart
    header('Location: cart.php');
    exit;
}

// Handle Remove from Cart action
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    header('Location: cart.php');
    exit;
}
?>

<div class="container">
    <h1>Your Shopping Cart</h1>
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <?php
        $total = 0;
        $product_ids = array_keys($_SESSION['cart']);
        $sql = "SELECT * FROM products WHERE id IN (" . implode(',', $product_ids) . ")";
        $result = mysqli_query($conn, $sql);

        while ($product = mysqli_fetch_assoc($result)) {
            $quantity = $_SESSION['cart'][$product['id']];
            $subtotal = $product['price'] * $quantity;
            $total += $subtotal;
            ?>
            <div class="cart-item">
                <span><?php echo htmlspecialchars($product['name']); ?></span>
                <span>Quantity: <?php echo $quantity; ?></span>
                <span>Price: $<?php echo number_format($product['price'], 2); ?></span>
                <span>Subtotal: $<?php echo number_format($subtotal, 2); ?></span>
                <a href="cart.php?action=remove&id=<?php echo $product['id']; ?>" class="btn">Remove</a>
            </div>
            <?php
        }
        ?>
        <div class="cart-total">
            Total: $<?php echo number_format($total, 2); ?>
        </div>
    <?php endif; ?>
</div>

<?php 
mysqli_close($conn);
include 'includes/footer.php'; 
?>