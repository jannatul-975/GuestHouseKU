<?php
// Include the database connection
include('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $designation = ucfirst(strtolower(trim($conn->real_escape_string($_POST['designation'])))); 
    $dept_name = $conn->real_escape_string($_POST['dept_name']); // Updated field

    // Default password for office staff
    $defaultPassword = password_hash("guesthouse@!", PASSWORD_DEFAULT);
    $table = 'officestaff';

    // Insert new office staff user
    $insert = $conn->prepare("INSERT INTO `$table` (name, email, phone_no, designation, dept_name, password) VALUES (?, ?, ?, ?, ?, ?)");
    $insert->bind_param("ssssss", $name, $email, $phone, $designation, $dept_name, $defaultPassword);

    if ($insert->execute()) {
        echo "✅ Office staff profile created successfully.";
    } else {
        echo "❌ Error: " . $insert->error;
    }

    $insert->close();
    $conn->close();
}
?>
