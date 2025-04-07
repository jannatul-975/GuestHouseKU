<?php
include('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure required fields are set
    if (!isset($_POST['name'], $_POST['email'], $_POST['phone'])) {
        echo "❌ Missing required fields.";
        exit;
    }

    // Sanitize input
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);

    // Set default values
    $defaultPassword = password_hash("guesthouse@!", PASSWORD_DEFAULT);
    $designation = 'Attendant';
    $table = strtolower($designation); // becomes 'attendant'

    // Deactivate previously active attendant, if any
    $conn->query("UPDATE `$table` SET is_active = 0");

    // Prepare insert for new active attendant
    $stmt = $conn->prepare("INSERT INTO `$table` (name, email, phone_no, designation, password, is_active) VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("sssss", $name, $email, $phone, $designation, $defaultPassword);

    if ($stmt->execute()) {
        echo "✅ Attendant profile created and set as active.";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
