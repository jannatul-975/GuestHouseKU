$(document).ready(function () {
  $("#loginForm").submit(function (event) {
    event.preventDefault();

    // Clear previous messages
    $("#loginMessage").html("");

    // Get the form fields
    const email = $("input[name='email']").val().trim();
    const password = $("input[name='password']").val().trim();

    // Validate inputs
    if (email === "" || password === "") {
      $("#loginMessage").html(
        "<p class='error'>Please fill in both email and password.</p>"
      );
      return; // Don't submit the form if validation fails
    }

    // Disable button to prevent multiple clicks
    const $button = $(this).find("button[type='submit']");
    $button.prop("disabled", true).text("Logging in...");

    // Form data
    const formData = { email, password };

    $.ajax({
      url: "login.php",
      type: "POST",
      data: formData,
      dataType: "json", // We expect JSON
      success: function (response) {
        console.log("AJAX Response:", response); // Log the response

        if (response.status === "success") {
          window.location.href = response.redirect;
        } else {
          $("#loginMessage").html(`<p class='error'>${response.message}</p>`);
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", status, error);
        console.error("Response Text:", xhr.responseText); // Log the full response text
        $("#loginMessage").html(
          "<p class='error'>An error occurred. Please try again.</p>"
        );
      },
      complete: function () {
        // Re-enable button after request
        $button.prop("disabled", false).text("Login");
      },
    });
  });
});
