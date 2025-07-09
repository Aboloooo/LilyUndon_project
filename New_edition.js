document.addEventListener("DOMContentLoaded", function () {
  const steps = document.querySelectorAll(".step");
  const nextBtn = document.getElementById("next");
  const prevBtn = document.getElementById("previous");

  const testBtn = document.getElementById("testBtn");

  let currentStep = 0;

  for (let i = 0; i < steps.length; i++) {
    steps[i].style.display = "none";
  }

  if (currentStep == 0) {
    prevBtn.style.display = "none";
    steps[currentStep].style.display = "block";
  }
  testBtn.style.display = "none";

  prevBtn.addEventListener("click", function (e) {
    currentStep--;
    if (currentStep < 0) {
      prevBtn.style.display = "none";
    }
    if (currentStep <= steps.length - 1) {
      nextBtn.style.display = "block";
    }
    if (currentStep > 0) {
      prevBtn.style.display = "block";
    } else {
      prevBtn.style.display = "none";
    }
    showSteps(currentStep);
  });

  nextBtn.addEventListener("click", function (e) {
    if (currentStep < steps.length - 1) {
      currentStep++;
      showSteps(currentStep);
    }
    if (currentStep == steps.length - 1) {
      e.preventDefault();
      nextBtn.style.display = "none";
      testBtn.style.display = "block";
    }
    if (currentStep > 0) {
      prevBtn.style.display = "block";
    }
    console.log(currentStep);
    showSteps(currentStep);
  });

  function showSteps(currentStep) {
    for (let i = 0; i < steps.length; i++) {
      steps[i].style.display = "none";
    }
    steps[currentStep].style.display = "block";
  }
});
