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
});
