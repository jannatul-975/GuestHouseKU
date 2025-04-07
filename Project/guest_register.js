$(document).ready(function () {
  $("#guestForm").submit(function (event) {
    event.preventDefault(); // Prevent default form submission

    let formData = {
      name: $("input[name='name']").val().trim(),
      email: $("input[name='email']").val().trim(),
      phone: $("input[name='phone']").val().trim(),
      address: $("textarea[name='address']").val().trim(),
    };

    // AJAX Request to process guest registration
    $.ajax({
      url: "guest_register.php", // PHP file for processing registration
      type: "POST",
      data: formData,
      dataType: "json", // Expect JSON response
      success: function (response) {
        if (response.status === "success") {
          // Show success message
          $("#message")
            .html("<p class='success'>" + response.message + "</p>")
            .show();

          // Redirect to login page after 3 seconds
          setTimeout(function () {
            window.location.href = "login.php"; // Redirect to login page
          }, 3000); // 3-second delay before redirect
        } else {
          // Show error message if registration fails
          $("#message")
            .html("<p class='error'>" + response.message + "</p>")
            .show();
        }
      },
      error: function (xhr) {
        // Handle AJAX errors and display appropriate message
        console.log("Raw response:", xhr.responseText); // Debugging

        let message = "⚠️ Unexpected error occurred!";
        try {
          let jsonResponse = JSON.parse(xhr.responseText); // Attempt to parse JSON response
          message = jsonResponse.message || "⚠️ Unknown server error!";
        } catch (e) {
          message = "⚠️ Server error! Check server logs.";
          console.error("JSON parsing error:", e);
        }

        // Display the error message to the user
        $("#message")
          .html("<p class='error'>" + message + "</p>")
          .show();
      },
    });
  });
});
