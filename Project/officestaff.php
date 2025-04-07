<?php
session_start();
include('dbconnect.php');

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Fetch office staff details from the database using the session ID
$officestaff_id = $_SESSION['id'];
$stmt = $conn->prepare("SELECT * FROM officestaff WHERE id = ?");
$stmt->bind_param("i", $officestaff_id);
$stmt->execute();
$result = $stmt->get_result();
$officestaff = $result->fetch_assoc();  
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Office Staff Dashboard</title>
    <link rel="stylesheet" href="teacher.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar" class="bg-dark text-white p-3">
        <h4 class="text-center">Khulna University</h4>
        <ul class="nav flex-column">
            <li id="nav-id">
                <a>Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="sendapplication.php" class="nav-link">Send Applications</a>
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
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div class="navbar-text">
                    Welcome, <span id="officestaff-name"><?php echo $officestaff['name']; ?></span>!
                </div>
                <div class="dropdown">
                    <img src="profile_pics/<?php echo $officestaff['profile_pic'] ?: 'profile.jpg'; ?>" class="rounded-circle" width="40" height="40" id="profile-btn" data-bs-toggle="dropdown">
                    <ul class="dropdown-menu dropdown-menu-end" id="view-profile">
                        <li><a class="dropdown-item" href="officestaffProfile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="login.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container mt-4">
            <h3>Office Staff Dashboard</h3>
            <div class="sitemap-box">
                <h5 class="text-white p-2">Sitemap of an Office Staff</h5>
                <ol>
                    <li><a href="officestaff.php">Dashboard</a>: Access your personal dashboard.</li>
                    <li><a href="sendapplication.php">Send Applications</a>: Submit a registration application.</li>
                    <li><a href="booking_myself.php">Booking for Myself</a>: Book a guest house for yourself.</li>
                    <li><a href="booking_guest.php">Booking for Guest</a>: Book a guest house for your guest.</li>
                    <li><a href="booking_history.php">Booking History</a>: View past bookings.</li>
                    <li><a href="payment_history.php">Payment History</a>: View your payment records.</li>
                </ol>
            </div>
        </div>
    </div>
</body>
</html>
