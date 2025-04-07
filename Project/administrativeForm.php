<?php
// Include the database connection
include('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize the inputs
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $designation = ucfirst(strtolower(trim($conn->real_escape_string($_POST['designation'])))); // e.g., "VC"

    $defaultPassword = password_hash("guesthouse@!", PASSWORD_DEFAULT);
    $table = 'administrative';

    // ✅ Only deactivate previous active users with the same designation
    $conn->query("UPDATE `$table` SET is_active = 0 WHERE designation = '$designation'");

    // ✅ Insert new administrative user as active
    $insert = $conn->prepare("INSERT INTO `$table` (name, email, phone_no, designation, password, is_active) VALUES (?, ?, ?, ?, ?, 1)");
    $insert->bind_param("sssss", $name, $email, $phone, $designation, $defaultPassword);

    if ($insert->execute()) {
        echo "✅ $designation profile created and set as active.";
    } else {
        echo "❌ Error: " . $insert->error;
    }

    $insert->close();
    $conn->close();
}
?>
