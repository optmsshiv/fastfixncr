document.getElementById("year").textContent = new Date().getFullYear();

function validateForm() {
  const name = document.getElementById("name").value.trim();
  const phone = document.getElementById("phone").value.trim();
  if (!name || !phone) {
    alert("Please enter your name and phone number.");
    return false;
  }
  // basic phone length check
  if (phone.replace(/[^0-9]/g, "").length < 7) {
    alert("Please enter a valid phone number.");
    return false;
  }
  // allow form to submit to backend
  return true;
}

// optional: smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach((a) => {
  a.addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    if (target) target.scrollIntoView({ behavior: "smooth", block: "start" });
  });
});

// optional: smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach((a) => {
  a.addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    if (target) target.scrollIntoView({ behavior: "smooth", block: "start" });
  });
});

// === Testimonials Slider with 3D Animation ===
const slider = document.querySelector(".slider");
const slides = document.querySelectorAll(".review-card");
const prev = document.querySelector(".prev");
const next = document.querySelector(".next");
const dotsContainer = document.querySelector(".slider-dots");

let index = 0;
const visibleCards = 3;
const total = slides.length;

// Create dots
for (let i = 0; i < Math.ceil(total / visibleCards); i++) {
  const dot = document.createElement("span");
  dot.classList.add("dot");
  if (i === 0) dot.classList.add("active");
  dotsContainer.appendChild(dot);
}

const dots = document.querySelectorAll(".dot");

function updateSlider() {
  const cardWidth = slides[0].offsetWidth + 25;
  slider.style.transform = `translateX(-${index * cardWidth}px)`;

  dots.forEach((dot) => dot.classList.remove("active"));
  const activeDot = Math.floor(index / visibleCards);
  if (dots[activeDot]) dots[activeDot].classList.add("active");
}

next.addEventListener("click", () => {
  index = (index + 1) % total;
  updateSlider();
});

prev.addEventListener("click", () => {
  index = (index - 1 + total) % total;
  updateSlider();
});

dots.forEach((dot, i) => {
  dot.addEventListener("click", () => {
    index = i * visibleCards;
    updateSlider();
  });
});

// Auto scroll one-by-one
setInterval(() => {
  index = (index + 1) % total;
  updateSlider();
}, 4000);

// time and date
document.addEventListener("DOMContentLoaded", () => {
  const today = new Date().toISOString().split("T")[0];
  document.getElementById("preferred_date").setAttribute("min", today);
});