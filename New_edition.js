document.addEventListener("DOMContentLoaded", () => {
  const nextBtn = document.getElementById("next");
  nextBtn.addEventListener("click", () => {
    alert("next");
  });

  const previousBtn = document.getElementById("previous");
  previousBtn.addEventListener("click", () => {
    alert("Privous");
  });
});
