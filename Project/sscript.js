document.addEventListener("DOMContentLoaded", function () {
  const navLinks = document.querySelectorAll(".nav-link");
  const menuIcon = document.querySelector("#menu-icon");
  const nav = document.querySelector("#navbar");

  function setActivePage() {
    const currentPage = window.location.pathname.split("/").pop();

    navLinks.forEach((link) => {
      const page = link.getAttribute("href");

      if (currentPage === page) {
        link.classList.add("active");
      } else {
        link.classList.remove("active");
      }
    });
  }

  // Call function to set active page on load
  setActivePage();

  // Add click event listener to update active state and navigate
  navLinks.forEach((link) => {
    link.addEventListener("click", function (event) {
      navLinks.forEach((link) => link.classList.remove("active"));
      this.classList.add("active");
    });
  });

  // Mobile menu toggle
  menuIcon.addEventListener("click", function () {
    nav.classList.toggle("active");
  });

  // Close menu on link click (for mobile view)
  navLinks.forEach((link) => {
    link.addEventListener("click", function () {
      if (window.innerWidth <= 768) {
        nav.classList.remove("active");
      }
    });
  });
});
