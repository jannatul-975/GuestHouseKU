<?php
session_start();
include('db_connect.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the form data
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Prepare and execute a query to fetch the user's hashed password from the database
  $stmt = $conn->prepare("SELECT GuestID, password FROM guest WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  // Check if the email exists in the database
  if ($stmt->num_rows > 0) {
    $stmt->bind_result($guest_id, $stored_hashed_password);
    $stmt->fetch();

    // Verify the password using password_verify
    if (password_verify($password, $stored_hashed_password)) {
      // Successful login, store guest data in session
      $_SESSION['guest_id'] = $guest_id;
      echo "success";  // Send success response to AJAX
    } else {
      echo "invalid_password";  // Incorrect password
    }
  } else {
    echo "invalid_email";  // Email not found
  }

  $stmt->close();
  $conn->close();
}
?>
