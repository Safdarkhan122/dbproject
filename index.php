<?php
include 'includes/db_connect.php';
include 'includes/header.php';
?>

<div class="container">
    <h1>Our Products</h1>
    <div class="product-grid">
        <?php
        $sql = "SELECT id, name, price, image FROM products";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                echo "<div class='product-card'>";
                echo "<a href='product.php?id=" . $row["id"] . "'>";
                echo "<img src='images/" . htmlspecialchars($row["image"]) . "' alt='" . htmlspecialchars($row["name"]) . "'>";
                echo "<h3>" . htmlspecialchars($row["name"]) . "</h3>";
                echo "<p>$" . htmlspecialchars($row["price"]) . "</p>";
                echo "</a>";
                echo "<a href='cart.php?action=add&id=" . $row["id"] . "' class='btn'>Add to Cart</a>";
                echo "</div>";
            }
        } else {
            echo "0 results";
        }
        mysqli_close($conn);
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>