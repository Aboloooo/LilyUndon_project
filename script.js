document.addEventListener("DOMContentLoaded", () => {
  const burger = document.querySelector(".burger");
  const nav = document.querySelector(".nav-links");
  const body = document.body;

  burger.addEventListener("click", () => {
    nav.classList.toggle("nav-active");
    burger.classList.toggle("toggle");

    // Remove overflow-x hidden when menu is open
    if (nav.classList.contains("nav-active")) {
      body.style.overflowX = "visible";
    } else {
      body.style.overflowX = "hidden";
    }
  });

  /* reveal pass toggle */
  document.querySelectorAll(".revealPassToggle").forEach(function (icon) {
    icon.addEventListener("click", function () {
      const input = this.previousElementSibling; // finds the input before the image
      if (input.type === "password") {
        input.type = "text";
        this.src = "../img/close-eye.png"; // Optional: change icon to eye-off
      } else {
        input.type = "password";
        this.src = "../img/open-eye.png"; // Optional: change icon back to eye
      }
    });
  });
});
