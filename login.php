<?php
include 'includes/db_connect.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a new session
            session_start();
            
            // Store data in session variables
            $_SESSION["user_id"] = $user['id'];
            $_SESSION["username"] = $user['username'];                            
            
            // Redirect user to home page
            header("location: index.php");
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "No account found with that username.";
    }
    $stmt->close();
}
$conn->close();

include 'includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h2>Login</h2>
        <?php if(!empty($message)): ?>
            <p style="color:red;"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>