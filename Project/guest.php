<?php
session_start();
include('dbconnect.php');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$guest_id = $_SESSION['id'];
$stmt = $conn->prepare("SELECT * FROM guest WHERE id = ?");
$stmt->bind_param("i", $guest_id);
$stmt->execute();
$result = $stmt->get_result();
$guest = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Dashboard</title>
    <link rel="stylesheet" href="teacher.css">
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
                <a href="guest_booking.php" class="nav-link">Booking</a>
            </li>
            <li class="nav-item">
                <a href="guest_booking_history.php" class="nav-link">Booking History</a>
            </li>
            <li class="nav-item">
                <a href="guest_payment_history.php" class="nav-link">Payment History</a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div id="content" class="p-4">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div class="navbar-text">
                    Welcome, <span id="teacher-name"><?php echo $guest['name']; ?></span>!
                </div>
                <div class="dropdown">
                    <img src="profile_pics/<?php echo $guest['profile_pic'] ?: 'profile.jpg'; ?>" class="rounded-circle" width="40" height="40" id="profile-btn" data-bs-toggle="dropdown">
                    <ul class="dropdown-menu dropdown-menu-end" id="view-profile">
                        <li><a class="dropdown-item" href="guestProfile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container mt-4">
            <h3>Guest Dashboard</h3>
            <div class="sitemap-box">
                <h5 class="text-white p-2">Sitemap of a Guest</h5>
                <ol>
                    <li><a href="guest.php">Dashboard</a>: Access your personal dashboard.</li>
                    <li><a href="guest_booking.php">Booking</a>: Book a guest house for yourself.</li>
                    <li><a href="guest_booking_history.php">Booking History</a>: View past bookings.</li>
                    <li><a href="guest_payment_history.php">Payment History</a>: View your payment records.</li>
                </ol>
            </div>
        </div>
    </div>
</body>
</html>
