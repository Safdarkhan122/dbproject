<?php
// ALWAYS start the session and include necessary files first
include 'includes/db_connect.php';
include 'includes/header.php'; // This includes session_start()

// ---- SECURITY CHECK ----
// 1. Check if the user is logged in. If not, redirect to the login page.
if (!isset($_SESSION['user_id'])) {
    // Optional: Store the page they were trying to access to redirect them back later
    $_SESSION['redirect_url'] = 'checkout.php';
    header('Location: login.php');
    exit;
}

// 2. Check if the cart is empty. If so, redirect to the homepage.
if (empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit;
}
// ---- END SECURITY CHECK ----

// Handle the "Place Order" form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // In a real application, you would:
    // 1. Save the order details to a new `orders` table in the database.
    // 2. Process payment through a gateway like Stripe or PayPal.
    // 3. Send a confirmation email.

    // For our simple site, we will just clear the cart and show a success message.
    unset($_SESSION['cart']); // or $_SESSION['cart'] = [];

    // Redirect to a success page
    header('Location: order_success.php');
    exit;
}

?>

<div class="container">
    <h1>Checkout</h1>
    <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>! Please review your order.</p>

    <div class="cart-summary">
        <h3>Order Summary</h3>
        <?php
        $total = 0;
        $product_ids = array_keys($_SESSION['cart']);
        $ids_string = implode(',', $product_ids);
        $sql = "SELECT * FROM products WHERE id IN ($ids_string)";
        $result = mysqli_query($conn, $sql);

        while ($product = mysqli_fetch_assoc($result)) {
            $quantity = $_SESSION['cart'][$product['id']];
            $subtotal = $product['price'] * $quantity;
            $total += $subtotal;
            ?>
            <div class="cart-item">
                <span><?php echo htmlspecialchars($product['name']); ?> (x<?php echo $quantity; ?>)</span>
                <span>$<?php echo number_format($subtotal, 2); ?></span>
            </div>
            <?php
        }
        ?>
        <div class="cart-total">
            Total: $<?php echo number_format($total, 2); ?>
        </div>
    </div>

    <form action="checkout.php" method="post" class="checkout-form">
        <p>Click the button below to simulate placing your order.</p>
        <!-- In a real site, you'd have fields for shipping address, etc. -->
        <button type="submit" class="btn">Place Order</button>
    </form>
</div>

<?php 
mysqli_close($conn);
include 'includes/footer.php'; 
?>