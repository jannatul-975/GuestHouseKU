<?php
  // Include any necessary PHP files (for database connection, etc.)
  // Example: include('header.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us - Khulna University Guest House</title>
    <link rel="stylesheet" href="style.css" />
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
          <li><a href="index.php" class="nav-link" data-page="home">Home</a></li>
          <li><a href="about.php" class="nav-link" data-page="about">About</a></li>
          <li><a href="rooms.php" class="nav-link" data-page="rooms">Rooms</a></li>
          <li><a href="feedback.php" class="nav-link" data-page="feedback">Feedback</a></li>
          <li><a href="login.php" class="nav-link" data-page="login">Login</a></li>
          <li><a href="guest_register.php" class="nav-link" data-page="guest-register">Guest Register</a></li>
        </ul>
      </nav>
    </header>

    <!-- Hero Section -->
    <section id="hero">
      <div class="hero-overlay">
        <h2>Contact Us</h2>
        <p>Get in touch with us for inquiries and assistance.</p>
      </div>
    </section>

    <!-- Main Content -->
    <main>
      <div class="content">
        <h2>Contact Information</h2>
        <p>If you have any questions or need assistance, feel free to reach out to us:</p>
        
        <div class="contact-info">
          <p><strong>Email:</strong> contact@khulnauguesthouse.edu</p>
          <p><strong>Phone:</strong> +8801234567890</p>
          <p><strong>Address:</strong> Khulna University, Khulna, Bangladesh</p>
        </div>
        
        <h3>Contact Form</h3>
        <form action="submit_contact.php" method="POST">
          <label for="name">Your Name:</label>
          <input type="text" id="name" name="name" required>

          <label for="email">Your Email:</label>
          <input type="email" id="email" name="email" required>

          <label for="message">Your Message:</label>
          <textarea id="message" name="message" required></textarea>

          <button type="submit">Submit</button>
        </form>
      </div>
    </main>

    <!-- Footer -->
    <footer>
      <p>&copy; 2025 Khulna University Guest House. All rights reserved.</p>
    </footer>

    <!-- External JS File -->
    <script src="script.js"></script>
  </body>
</html>
