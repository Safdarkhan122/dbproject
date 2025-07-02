<?php
// ALWAYS start the session first
// We do this by including the header
include 'includes/db_connect.php';
include 'includes/header.php'; // header.php contains session_start()

// NOW we can safely process cart actions because the session is active.

// Handle Add to Cart action
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    // Initialize cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If product is already in cart, increment quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        // Add product to cart with quantity 1
        $_SESSION['cart'][$product_id] = 1;
    }
    // Redirect to cart page to show the updated cart without the GET parameters in the URL
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
        <p>Your cart is empty. <a href="index.php">Continue shopping!</a></p>
    <?php else: ?>
        <?php
        $total = 0;
        // Get product IDs from cart to fetch from DB
        $product_ids = array_keys($_SESSION['cart']);
        
        // Make sure we have product IDs to query, otherwise SQL will error
        if (!empty($product_ids)) {
            $ids_string = implode(',', $product_ids);
            $sql = "SELECT * FROM products WHERE id IN ($ids_string)";
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
        }
        ?>
        <div class="cart-total">
            Total: $<?php echo number_format($total, 2); ?>
        </div>
        <br>
        <a href="checkout.php" class="btn">Proceed to Checkout</a>
    <?php endif; ?>
</div>

<?php 
mysqli_close($conn);
include 'includes/footer.php'; 
?>