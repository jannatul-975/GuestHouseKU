<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "guesthousedb";

// Connect to DB
$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get and sanitize input
$roomNo = $_POST['roomNo'];
$roomType = $_POST['roomType'];
$rent = $_POST['rent'];
$status = $_POST['status'];

// Insert query
$sql = "INSERT INTO room (RoomNo, room_type, pricePerNight, status) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssds", $roomNo, $roomType, $rent, $status);

if ($stmt->execute()) {
    echo "Room added successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>