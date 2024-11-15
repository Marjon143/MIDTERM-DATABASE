<?php
$servername = "localhost";
$username = "root";
$password = ""; // Default password for MySQL in XAMPP is empty
$dbname = "marjon"; // The name of your database

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>



