<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include database connection
include('dbconnect.php');  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Content-Type: application/json"); // Return JSON response

    // Get form values
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone_no = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    
    // Default password (hashed)
    $default_password = "guesthouse@ku";
    $hashed_password = password_hash($default_password, PASSWORD_DEFAULT); 

    // Validate input
    if (empty($name) || empty($email) || empty($phone_no) || empty($address)) {
        echo json_encode(["status" => "error", "message" => "All fields are required!"]);
        exit;
    }

    // Validate phone number format for Bangladesh (+8801XXXXXXXXX)
    $phone_no = trim($phone_no);  // Remove leading/trailing spaces
    $phone_no = preg_replace("/[^0-9+]/", "", $phone_no); 

    if (!preg_match("/^\+8801\d{9}$/", $phone_no) || strlen($phone_no) != 14) {
        echo json_encode(["status" => "error", "message" => "Invalid phone number format! Please use the Bangladesh format: +8801XXXXXXXXX."]);
        exit;
    }

    // Check if email or phone already exists
    $checkStmt = $conn->prepare("SELECT id FROM guest WHERE email = ? OR phone_no = ?");
    if ($checkStmt === false) {
        echo json_encode(["status" => "error", "message" => "Failed to prepare statement"]);
        exit;
    }
    $checkStmt->bind_param("ss", $email, $phone_no);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email or phone number already registered!"]);
        exit;
    }
    $checkStmt->close();

    // Insert guest record
    $stmt = $conn->prepare("INSERT INTO guest (name, email, phone_no, address, password) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Failed to prepare insert statement"]);
        exit;
    }
    $stmt->bind_param("sssss", $name, $email, $phone_no, $address, $hashed_password);

    if ($stmt->execute()) {
        $guest_id = $stmt->insert_id; // Get the generated id

        // Send SMS
        $message = "Dear $name, your Guest House account has been created. Your default password is: $default_password.";
        $sms_sent = sendSMS($phone_no, $message); 

        // Determine response message
        if ($sms_sent) {
            echo json_encode([
                "status" => "success",
                "message" => "✅ Registration successful! Your Guest ID: $guest_id. SMS sent successfully!",
                "guest_id" => $guest_id
            ]);
        } else {
            echo json_encode([
                "status" => "success",
                "message" => "✅ Registration successful! Your Guest ID: $guest_id. ❌ But SMS sending failed!",
                "guest_id" => $guest_id
            ]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
    }
    $stmt->close();
    $conn->close();
    exit;
}

// Function to send SMS using Twilio
function sendSMS($phone, $message) {
    $account_sid = "YOUR_TWILIO_SID"; 
    $auth_token = "YOUR_TWILIO_AUTH_TOKEN";
    $twilio_number = "YOUR_TWILIO_PHONE"; 

    $url = "https://api.twilio.com/2010-04-01/Accounts/$account_sid/Messages.json";
    $data = [
        "From" => $twilio_number,
        "To" => $phone,
        "Body" => $message
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_USERPWD, "$account_sid:$auth_token");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    // Enable detailed cURL debugging
    curl_setopt($ch, CURLOPT_VERBOSE, true); 

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    // Log the full Twilio response and any cURL errors
    if (curl_errno($ch)) {
        error_log("cURL Error: " . curl_error($ch));
    }

    curl_close($ch);

    // Log detailed Twilio response
    error_log("Twilio API Response: $response, HTTP Code: $http_code, Error: $error");

    if ($http_code == 201) {
        return true; // SMS sent successfully
    } else {
        return false; // SMS failed
    }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Guest Registration - Khulna University Guest House</title>
    <link rel="stylesheet" href="guest_register.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>
  <body>
    <header>
      <div class="logo-container">
        <img src="logo.png" alt="Logo" />
        <h1>Khulna University Guest House</h1>
      </div>
      <div class="menu-icon" id="menu-icon">
        <span>&#9776;</span>
      </div>

      <!-- Navigation Menu -->
      <nav id="navbar">
        <ul>
          <li><a href="index.php" class="nav-link" data-page="home">Home</a></li>
          <li><a href="about.php" class="nav-link" data-page="about">About</a></li>
          <li><a href="rooms.php" class="nav-link" data-page="rooms">Rooms</a></li>
          <li><a href="feedback.php" class="nav-link" data-page="feedback">Feedback</a></li>
          <li><a href="login.php" class="nav-link" data-page="login">Login</a></li>
          <li><a href="guest_register.php" class="nav-link" data-page="guest-register">Guest Register</a></li>
        </ul>
      </nav>
    </header>

    <div class="form-container">
      <h2>Guest Registration</h2>
      <form id="guestForm">
        <input type="text" name="name" placeholder="Name" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="text" name="phone" placeholder="Phone Number" required />
        <textarea name="address" placeholder="Address" required></textarea>
        <div class="button-group">
          <button type="submit">Register</button>
        </div>
      </form>
      <div id="message"></div>
    </div>

    <footer>
      <p>&copy; 2025 Khulna University Guest House. All rights reserved.</p>
    </footer>

    <script src="guest_register.js"></script>
    <script src="script.js"></script>
  </body>
</html>
