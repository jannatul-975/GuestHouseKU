<?php
include('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['name'], $_POST['email'], $_POST['phone'])) {
        echo "Missing required fields.";
        exit;
    }

    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);

    $defaultPassword = password_hash("register@!", PASSWORD_DEFAULT);
    $designation = 'Register';
    $table = strtolower($designation); // 'register'

    // Deactivate previous Register
    $conn->query("UPDATE `$table` SET is_active = 0");

    // Insert new Register as active
    $insert = $conn->prepare("INSERT INTO `$table` (name, email, phone_no, designation, password, is_active) VALUES (?, ?, ?, ?, ?, 1)");
    $insert->bind_param("sssss", $name, $email, $phone, $designation, $defaultPassword);

    if ($insert->execute()) {
        echo "✅ Register profile created and set as active.";
    } else {
        echo "❌ Error: " . $insert->error;
    }

    $insert->close();
    $conn->close();
}
?>
