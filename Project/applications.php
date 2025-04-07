<?php
session_start();
include('dbconnect.php');

// Redirect to login if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Fetch register details
$register_id = $_SESSION['id'];
$stmt = $conn->prepare("SELECT * FROM register WHERE id = ?");
$stmt->bind_param("i", $register_id);
$stmt->execute();
$result = $stmt->get_result();
$register = $result->fetch_assoc();  
$stmt->close();

// Check if register data exists
if (!$register) {
    echo "<script>alert('Register data not found. Please contact support.'); window.location.href='login.php';</script>";
    exit();
}

// Function to generate verification code
function generateVerificationCode($length = 6) {
    return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, $length));
}

// Handle approve/reject
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = $_POST['application_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $code = generateVerificationCode();

        // Get teacher email and name before proceeding
        $stmt = $conn->prepare("
            SELECT t.email, t.name 
            FROM teacher_application ta 
            JOIN teacher t ON ta.TeacherID = t.id 
            WHERE ta.ApplicationID = ?
        ");
        $stmt->bind_param("i", $application_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $teacher = $result->fetch_assoc();
        $stmt->close();

        if ($teacher) {
            // Insert verification code
            $stmt = $conn->prepare("INSERT INTO verification_codes (application_id, code, is_used) VALUES (?, ?, 0)");
            $stmt->bind_param("is", $application_id, $code);
            $stmt->execute();
            $stmt->close();

            // Update application status
            $stmt = $conn->prepare("UPDATE teacher_application SET status = 'Approved' WHERE ApplicationID = ?");
            $stmt->bind_param("i", $application_id);
            $stmt->execute();
            $stmt->close();

            echo "<script>alert('Application approved. Verification code generated.'); window.location.href='applications.php';</script>";
            exit();
        } else {
            echo "<script>alert('Teacher not found for this application.'); window.location.href='applications.php';</script>";
            exit();
        }

    } elseif ($action === 'reject') {
        $stmt = $conn->prepare("UPDATE teacher_application SET status = 'Rejected' WHERE ApplicationID = ?");
        $stmt->bind_param("i", $application_id);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Application rejected.'); window.location.href='applications.php';</script>";
        exit();
    }
}

// Fetch pending applications from teacher_application table
$query = "
    SELECT ta.ApplicationID, ta.status, ta.submission_date, 
           t.name AS teacher_name, t.dept_name, t.phone_no, 
           ta.room_id, ta.checkin_date, ta.checkout_date, ta.guest_information
    FROM teacher_application ta
    JOIN teacher t ON ta.TeacherID = t.id
    WHERE ta.status = 'Pending'
    ORDER BY ta.submission_date DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Applications - Register</title>
    <link rel="stylesheet" href="teacher.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Sidebar -->
<nav id="sidebar" class="bg-dark text-white p-3" style="min-height: 100vh; position: fixed; width: 250px;">
    <h4 class="text-center">Khulna University</h4>
    <ul class="nav flex-column mt-4">
        <li class="nav-id"><a href="register.php" class="nav-link text-white">Dashboard</a></li>
        <li class="nav-item"><a href="applications.php" class="nav-link text-white">Applications</a></li>
        <li class="nav-item"><a href="booking_myself.php" class="nav-link text-white">Booking Myself</a></li>
        <li class="nav-item"><a href="booking_guest.php" class="nav-link text-white">Booking for Guest</a></li>
        <li class="nav-item"><a href="booking_history.php" class="nav-link text-white">Booking History</a></li>
        <li class="nav-item"><a href="payment_history.php" class="nav-link text-white">Payment History</a></li>
    </ul>
</nav>

<!-- Main Content -->
<div id="content" class="p-4" style="margin-left: 250px;">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="navbar-text">
                Welcome, <strong><?php echo htmlspecialchars($register['name']); ?></strong>!
            </div>
            <div class="dropdown">
                <img src="profile_pics/<?php echo $register['profile_pic'] ?: 'profile.jpg'; ?>" class="rounded-circle" width="40" height="40" id="profile-btn" data-bs-toggle="dropdown">
                <ul class="dropdown-menu dropdown-menu-end" id="view-profile">
                    <li><a class="dropdown-item" href="registerProfile.php">Profile</a></li>
                    <li><a class="dropdown-item" href="login.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h3 class="mb-4">Pending Applications</h3>

        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Application ID</th>
                            <th>Teacher</th>
                            <th>Discipline</th>
                            <th>Phone</th>
                            <th>Room</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Guest Information</th>
                            <th>Submitted On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['ApplicationID']; ?></td>
                            <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['dept_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['room_id']); ?></td>
                            <td><?php echo $row['checkin_date']; ?></td>
                            <td><?php echo $row['checkout_date']; ?></td>
                            <td><?php echo htmlspecialchars($row['guest_information']); ?></td>
                            <td><?php echo $row['submission_date']; ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="application_id" value="<?php echo $row['ApplicationID']; ?>">
                                    <button type="submit" name="action" value="approve" class="btn btn-success btn-sm mb-1">Approve</button>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to reject?');">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No pending applications found.</div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
