<?php
session_start();
include('dbconnect.php');

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Fetch attendant details from the database using the session ID
$attendant_id = $_SESSION['id'];
$stmt = $conn->prepare("SELECT * FROM attendant WHERE id = ?");
$stmt->bind_param("i", $attendant_id);
$stmt->execute();
$result = $stmt->get_result();
$attendant = $result->fetch_assoc();  
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendant Dashboard</title>
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
                <a href="rooms.php" class="nav-link">Rooms Information</a>
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
                    Welcome, <span id="attendant-name"><?php echo $attendant['name']; ?></span>!
                </div>
                <div class="dropdown">
                    <img src="profile_pics/<?php echo $attendant['profile_pic'] ?: 'profile.jpg'; ?>" class="rounded-circle" width="40" height="40" id="profile-btn" data-bs-toggle="dropdown">
                    <ul class="dropdown-menu dropdown-menu-end" id="view-profile">
                        <li><a class="dropdown-item" href="attendantProfile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="login.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container mt-4">
            <h3>Attendant Dashboard</h3>
            <div class="sitemap-box">
                <h5 class="text-white p-2">Sitemap of an Attendant</h5>
                <ol>
                    <li><a href="attendant.php">Dashboard</a>: Access your personal dashboard.</li>
                    <li><a href="rooms.php">Rooms Information</a>: View and manage room information.</li>
                    <li><a href="booking_history.php">Booking History</a>: View all booking records.</li>
                    <li><a href="payment_history.php">Payment History</a>: View all payment records.</li>
                </ol>
            </div>
        </div>
    </div>
</body>
</html>
