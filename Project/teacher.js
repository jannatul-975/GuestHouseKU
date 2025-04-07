// Profile dropdown toggle
document.getElementById("profile-btn").addEventListener("click", function () {
  const dropdownMenu = document.querySelector(".dropdown-menu");
  dropdownMenu.classList.toggle("show-dropdown");
});

// Close dropdown if clicked outside
document.addEventListener("click", function (event) {
  const dropdownMenu = document.querySelector(".dropdown-menu");
  if (
    !dropdownMenu.contains(event.target) &&
    event.target !== document.getElementById("profile-btn")
  ) {
    dropdownMenu.classList.remove("show-dropdown");
  }
});

// Handle Send Application button click with AJAX
document
  .getElementById("send-application")
  .addEventListener("click", function () {
    const statusElement = document.getElementById("application-status");
    statusElement.innerHTML = "Processing...";

    // Simulating an AJAX request with setTimeout (replace this with your actual AJAX call)
    setTimeout(function () {
      // Simulating a successful response from AJAX
      statusElement.innerHTML = "Approved"; // Change to "Pending" or other status based on response
      document
        .getElementById("verification-code-section")
        .classList.remove("d-none");
      document.getElementById("verification-code-text").innerText = "12345"; // Simulated verification code
    }, 2000); // Simulate 2-second delay
  });

// Handle booking for yourself (AJAX)
document.getElementById("check-rooms").addEventListener("click", function () {
  const verificationCode = document.getElementById("verification-code").value;
  const roomsElement = document.getElementById("rooms");

  if (!verificationCode) {
    alert("Please enter the verification code.");
    return;
  }

  roomsElement.innerHTML = "Loading available rooms..."; // Loading state

  // Simulating an AJAX request for available rooms
  setTimeout(function () {
    roomsElement.innerHTML = `
      <div>Room 101: Available</div>
      <div>Room 102: Available</div>
      <div>Room 103: Booked</div>
      <button class="btn btn-success">Book Now</button>
    `;
  }, 2000); // Simulate 2-second delay
});

// Handle guest booking (AJAX)
document
  .getElementById("check-guest-rooms")
  .addEventListener("click", function () {
    const guestId = document.getElementById("guest-id").value;
    const guestVerificationCode = document.getElementById(
      "guest-verification-code"
    ).value;
    const guestRoomsElement = document.getElementById("guest-rooms");

    if (!guestId || !guestVerificationCode) {
      alert("Please enter both Guest ID and Verification Code.");
      return;
    }

    guestRoomsElement.innerHTML = "Loading available rooms..."; // Loading state

    // Simulating an AJAX request for available rooms
    setTimeout(function () {
      guestRoomsElement.innerHTML = `
      <div>Guest Room 201: Available</div>
      <div>Guest Room 202: Available</div>
      <div>Guest Room 203: Booked</div>
      <button class="btn btn-success">Book Now</button>
    `;
    }, 2000); // Simulate 2-second delay
  });
