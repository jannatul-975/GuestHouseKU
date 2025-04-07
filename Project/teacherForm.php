<?php
include('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if required fields are set
    if (!isset($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['designation'], $_POST['dept_name'])) {
        echo "❌ Missing required fields.";
        exit;
    }

    // Sanitize inputs
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $designation = $conn->real_escape_string($_POST['designation']);
    $dept_name = $conn->real_escape_string($_POST['dept_name']); // Corrected field name

    // Default password for the teacher
    $defaultPassword = password_hash("guesthouse@!", PASSWORD_DEFAULT);

    // Fetch the RegisterID where is_active = 1 from the register table
    $register_query = "SELECT id FROM register WHERE is_active = 1 LIMIT 1";
    $register_result = $conn->query($register_query);

    if ($register_result && $register_result->num_rows > 0) {
        $register_id = $register_result->fetch_assoc()['id'];
    } else {
        // Handle the case where no active register is found
        echo "❌ No active registration found. Please ensure there is an active registration.";
        exit;
    }

    // Insert the new teacher record including RegisterID
    $stmt = $conn->prepare("INSERT INTO `teacher` (name, email, phone_no, designation, dept_name, password, RegisterID) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $name, $email, $phone, $designation, $dept_name, $defaultPassword, $register_id);

    if ($stmt->execute()) {
        echo "✅ Teacher profile created successfully.";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
