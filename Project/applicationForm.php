<?php
session_start();
include('dbconnect.php');

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['id'];

// Get teacher info
$stmt = $conn->prepare("SELECT * FROM teacher WHERE id = ?");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();
$stmt->close();

// Ensure the teacher has an active register entry
$register_check_query = "SELECT id FROM register WHERE is_active = 1 AND id = ?";
$register_check_stmt = $conn->prepare($register_check_query);
$register_check_stmt->bind_param("i", $teacher['RegisterID']);
$register_check_stmt->execute();
$register_check_result = $register_check_stmt->get_result();
$register_check_stmt->close();

if ($register_check_result->num_rows === 0) {
    echo "<script>alert('You do not have an active register entry. Please contact the administrator.');</script>";
    exit();
}

// Fetch available rooms ONLY
$rooms_query = "SELECT * FROM room WHERE status = 'Available'";
$rooms_result = $conn->query($rooms_query);

// Handle form submission via POST (AJAX or standard)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_type = $_POST['booking_type'];
    $room_id = $_POST['room'] ?? [];
    $checkin_date = $_POST['checkin_date'];
    $checkout_date = $_POST['checkout_date'];
    $guest_information = $_POST['guest_information'] ?? NULL;  // Nullable guest information

    if (empty($room_id)) {
        echo "<script>alert('Please select at least one room.');</script>";
        exit;
    }

    // Insert teacher application into teacher_application table
    // Assuming ApplicationID is auto-increment, so we don't need to insert it manually
    $stmt = $conn->prepare("INSERT INTO teacher_application (TeacherID, guest_information, RegisterID, status, submission_date, room_id, checkin_date, checkout_date) VALUES (?, ?, ?, 'Pending', NOW(), ?, ?, ?)");

    // Assuming room_id is an integer, we don't need to encode as JSON
    $room_id = $room_id[0];  // If you select only one room, take the first one from the array

    // Bind parameters and execute
    // Use 'i' for integers (TeacherID, RegisterID, room_id), 's' for string (guest_information), and 's' for the dates
    $stmt->bind_param("isssss", $teacher_id, $guest_information, $teacher['RegisterID'], $room_id, $checkin_date, $checkout_date);
    $stmt->execute();

    // Check if the insertion was successful
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to insert application']);
    }

    $stmt->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Application Form</title>
    <link rel="stylesheet" href="teacher.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>
  <body>
    <!-- Sidebar -->
    <nav id="sidebar" class="bg-dark text-white p-3">
      <h4 class="text-center">Khulna University</h4>
      <ul class="nav flex-column">
        <li id="nav-id"><a>Dashboard</a></li>
        <li class="nav-item">
          <a href="applicationform.php" class="nav-link">Send Application</a>
        </li>
        <li class="nav-item">
          <a href="status.php" class="nav-link">Status</a>
        </li>
        <li class="nav-item">
          <a href="booking_myself.php" class="nav-link">Booking Myself</a>
        </li>
        <li class="nav-item">
          <a href="booking_guest.php" class="nav-link">Booking for Guest</a>
        </li>
        <li class="nav-item">
          <a href="booking_history.php" class="nav-link">Booking History</a>
        </li>
        <li class="nav-item">
          <a href="payment_history.php" class="nav-link">Payment History</a>
        </li>
      </ul>
    </nav>

    <!-- Main Content -->
    <div id="content" class="p-4">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div
          class="container-fluid d-flex justify-content-between align-items-center"
        >
          <div class="navbar-text">
            Welcome,
            <span id="teacher-name"><?php echo $teacher['name']; ?></span>!
          </div>
          <div class="dropdown">
            <img
              src="profile_pics/<?php echo $teacher['profile_pic'] ?: 'profile.jpg'; ?>"
              class="rounded-circle"
              width="40"
              height="40"
              id="profile-btn"
              data-bs-toggle="dropdown"
            />
            <ul class="dropdown-menu dropdown-menu-end" id="view-profile">
              <li>
                <a class="dropdown-item" href="teacherProfile.php">Profile</a>
              </li>
              <li><a class="dropdown-item" href="login.php">Logout</a></li>
            </ul>
          </div>
        </div>
      </nav>

      <div class="container mt-4">
        <h3>Send Application for Room Booking</h3>
        <form action="applicationform.php" method="POST" id="applicationForm">
          <div class="form-group mt-3">
            <label for="booking_type">Booking Type</label>
            <select
              id="booking_type"
              name="booking_type"
              class="form-control"
              required
            >
              <option value="myself">For Myself</option>
              <option value="guest">For Guest</option>
            </select>
          </div>

          <div class="form-group mt-3">
            <label for="room">Room Selection</label>
            <select
              id="room"
              name="room[]"
              class="form-control"
              multiple
              size="5"
              required
            >
              <?php while ($room = $rooms_result->fetch_assoc()): ?>
              <option value="<?php echo $room['RoomID']; ?>">
                <?php echo $room['RoomNo']; ?>
                (<?php echo $room['room_type']; ?>)
              </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-group mt-3">
            <label for="checkin_date">Check-in Date</label>
            <input
              type="date"
              id="checkin_date"
              name="checkin_date"
              class="form-control"
              required
            />
          </div>

          <div class="form-group mt-3">
            <label for="checkout_date">Check-out Date</label>
            <input
              type="date"
              id="checkout_date"
              name="checkout_date"
              class="form-control"
              required
            />
          </div>

          <div class="form-group mt-3" id="guest_info" style="display: none">
            <label for="guest_information">Guest Information</label>
            <textarea
              id="guest_information"
              name="guest_information"
              class="form-control"
              placeholder="Enter guest information (optional)"
            ></textarea>
          </div>

          <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">
              Submit Application
            </button>
          </div>
        </form>
      </div>
    </div>

    <script>
      // Toggle guest info visibility based on booking type
      document
        .getElementById("booking_type")
        .addEventListener("change", function () {
          const guestDiv = document.getElementById("guest_info");
          guestDiv.style.display = this.value === "guest" ? "block" : "none";
        });

      // Submit the form using AJAX
      $("#applicationForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
          url: "applicationform.php",
          type: "POST",
          data: $(this).serialize(),
          success: function (response) {
            alert("Application submitted successfully!");
            window.location.href = "applicationform.php"; // Redirect to the same page
          },
          error: function (xhr, status, error) {
            alert("An error occurred while submitting the application.");
          },
        });
      });
    </script>
  </body>
</html>
