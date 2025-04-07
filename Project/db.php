<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "guest_house";

// Create Connection
$conn = new mysqli($servername, $username, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
