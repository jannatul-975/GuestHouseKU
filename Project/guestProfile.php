<?php
session_start();
include('dbconnect.php');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$guest_id = $_SESSION['id'];

// Fetch current data
$stmt = $conn->prepare("SELECT name, email, phone_no, address, profile_pic, registration_date FROM guest WHERE id = ?");
$stmt->bind_param("i", $guest_id);
$stmt->execute();
$stmt->bind_result($name, $email, $phone_no, $address, $profile_pic, $registration_date);
$stmt->fetch();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = htmlspecialchars($_POST['name']);
    $new_email = htmlspecialchars($_POST['email']);
    $new_phone = htmlspecialchars($_POST['phone']);
    $new_address = htmlspecialchars($_POST['address']);
    $new_password = $_POST['password'];
    $upload_profile_pic = $_FILES['profile_pic']['name'];

    $profile_pic_to_store = $profile_pic;

    // Upload new profile pic if provided
    if (!empty($upload_profile_pic)) {
        $target_dir = "profile_pics/";
        $profile_pic_to_store = uniqid() . "_" . basename($upload_profile_pic);
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_dir . $profile_pic_to_store);
    }

    // Update with or without password
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE guest SET name=?, email=?, phone_no=?, address=?, profile_pic=?, password=? WHERE id=?");
        $updateStmt->bind_param("ssssssi", $new_name, $new_email, $new_phone, $new_address, $profile_pic_to_store, $hashed_password, $guest_id);
    } else {
        $updateStmt = $conn->prepare("UPDATE guest SET name=?, email=?, phone_no=?, address=?, profile_pic=? WHERE id=?");
        $updateStmt->bind_param("sssssi", $new_name, $new_email, $new_phone, $new_address, $profile_pic_to_store, $guest_id);
    }

    if ($updateStmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); location.href='guestProfile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }

    $updateStmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Profile</title>
    <link rel="stylesheet" href="teacherProfile.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <nav id="sidebar" class="bg-dark text-white p-3">
        <h4 class="text-center">Khulna University</h4>
        <ul class="nav flex-column">
            <li><a href="guest.php">Dashboard</a></li>
            <li><a href="guest_booking.php">Booking</a></li>
            <li><a href="guest_booking_history.php">Booking History</a></li>
            <li><a href="guest_payment_history.php">Payment History</a></li>
        </ul>
    </nav>

    <div id="content" class="p-4">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div class="navbar-text">Welcome, <span id="guest-name"><?php echo $name; ?></span>!</div>
                <div class="dropdown">
                    <img src="profile_pics/<?php echo $profile_pic ?: 'profile.jpg'; ?>" class="rounded-circle" width="40" height="40" id="profile-btn" data-bs-toggle="dropdown">
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="guestProfile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container mt-4">
            <h3>Edit Profile</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="d-flex flex-column align-items-center mb-4">
                    <img id="profile-img" src="profile_pics/<?php echo $profile_pic ?: 'profile.jpg'; ?>" class="rounded-circle" width="100" height="100">
                    <h3><?php echo $name; ?></h3>
                    <h4>Guest Id: <?php echo $guest_id; ?></h4>
                    <button class="btn btn-primary mt-3" onclick="document.getElementById('profile-pic-input').click(); return false;">Change Photo</button>
                    <input type="file" id="profile-pic-input" name="profile_pic" style="display:none;">
                </div>

                <label>Name</label>
                <input type="text" name="name" value="<?php echo $name; ?>" required>
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $email; ?>" required>
                <label>Phone No</label>
                <input type="text" name="phone" value="<?php echo $phone_no; ?>" required>
                <label>Address</label>
                <input type="text" name="address" value="<?php echo $address; ?>">
                <label>Registration Date</label>
                <input type="text" value="<?php echo $registration_date; ?>" disabled>
                <label>Password</label>
                <input type="password" name="password" placeholder="Leave blank to keep unchanged">
                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>
    </div>
</body>
</html>
