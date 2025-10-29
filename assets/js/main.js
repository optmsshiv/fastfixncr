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