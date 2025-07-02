<?php
// Database credentials
$DB_SERVER = 'localhost';
$DB_USERNAME = 'root';
$DB_PASSWORD = ''; // Default XAMPP password is empty
$DB_NAME = 'ecommerce_db';

// Create a database connection
$conn = mysqli_connect($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>