<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include('dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Content-Type: application/json");

    // Debugging output (log to a file)
    error_log("Received POST data: " . print_r($_POST, true));

    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Table => [id column, redirect file, active column]
    $userTables = [
        "guest" => ["id_col" => "id", "redirect" => "guest.php", "active_col" => null],  // No active check
        "teacher" => ["id_col" => "id", "redirect" => "teacher.php", "active_col" => null], // No active check
        "attendant" => ["id_col" => "id", "redirect" => "attendant.php", "active_col" => "is_active"],  // Check active
        "administrative" => ["id_col" => "id", "redirect" => "administrative.php", "active_col" => "is_active"], // Check active
        "officestaff" => ["id_col" => "id", "redirect" => "officeStaff.php", "active_col" => null], // No active check
        "register" => ["id_col" => "id", "redirect" => "register.php", "active_col" => "is_active"] // Check active
    ];

    // Loop through each table to check for matching email
    foreach ($userTables as $table => $info) {
        $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            // Check if the user is active, but only for specific roles
            if ($info['active_col'] !== null && $user[$info['active_col']] == 0) {
                echo json_encode(["status" => "error", "message" => "Your account is inactive."]);
                exit;
            }

            // Check password
            if (password_verify($password, $user['password'])) {
                $_SESSION['id'] = $user[$info['id_col']];
                $_SESSION['role'] = $table;
                echo json_encode(["status" => "success", "redirect" => $info['redirect']]);
                exit;
            } else {
                echo json_encode(["status" => "error", "message" => "Incorrect password. Try again."]);
                exit;
            }
        }

        $stmt->close();
    }

    // If no match was found
    echo json_encode(["status" => "error", "message" => "Email not found. Try again."]);
    $conn->close();
    exit;
}
?>




<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Khulna University Guest House</title>
    <link rel="stylesheet" href="login.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>
  <body>
    <!-- Header Section -->
    <header>
      <div class="logo-container">
        <img src="logo.png" alt="Logo" />
        <h1>Khulna University Guest House</h1>
      </div>

      <!-- Mobile Menu Icon -->
      <div class="menu-icon" id="menu-icon">
        <span>&#9776;</span>
      </div>

      <!-- Navigation Menu -->
      <nav id="navbar">
        <ul>
          <li>
            <a href="index.php" class="nav-link" data-page="home">Home</a>
          </li>
          <li>
            <a href="about.php" class="nav-link" data-page="about">About</a>
          </li>
          <li>
            <a href="rooms.php" class="nav-link" data-page="rooms">Rooms</a>
          </li>
          <li>
            <a href="feedback.php" class="nav-link" data-page="feedback"
              >Feedback</a
            >
          </li>
          <li>
            <a href="login.php" class="nav-link" data-page="login">Login</a>
          </li>
          <li>
            <a
              href="guest_register.php"
              class="nav-link"
              data-page="guest-register"
              >Guest Register</a
            >
          </li>
        </ul>
      </nav>
    </header>

    <!-- Form Section -->
    <div class="form-container">
      <h2>Login</h2>
      <form id="loginForm">
        <input type="email" name="email" placeholder="Email" required />
        <input
          type="password"
          name="password"
          placeholder="Password"
          required
        />
        <div class="button-group">
          <button type="submit">Login</button>
        </div>
      </form>
      <div id="loginMessage"></div>
    </div>

    <!-- Footer Section -->
    <footer>
      <p>&copy; 2025 Khulna University Guest House. All rights reserved.</p>
    </footer>

    <script src="login.js"></script>
    <script src="script.js"></script>
  </body>
</html>

