document.addEventListener("DOMContentLoaded", function () {
  const steps = document.querySelectorAll(".step");
  const nextBtn = document.getElementById("next");
  const prevBtn = document.getElementById("previous");
  let currentStep = 0;

  if (currentStep == 0) {
    prevBtn.style.display = "none";
  }

  prevBtn.addEventListener("click", function (e) {
    currentStep--;
    if (currentStep > 0) {
      prevBtn.style.display = "block";
      /* steps[currentStep].style.display = "none"; */
    } else {
      prevBtn.style.display = "none";
    }
    console.log(currentStep + " reduced");
  });

  nextBtn.addEventListener("click", function (e) {
    if (currentStep >= 6) {
      e.preventDefault();
    } else {
      currentStep++;
      /* steps[currentStep].style.display = "block"; */
    }
    if (currentStep > 0) {
      prevBtn.style.display = "block";
    }
    console.log(currentStep + " increamented");
  });
});
