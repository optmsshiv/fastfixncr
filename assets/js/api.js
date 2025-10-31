document.getElementById('inquiryForm').addEventListener('submit', async function (e) {
  e.preventDefault();
  
  const btn = document.getElementById('submitBtn');
  const msg = document.getElementById('formMessage');
  
  btn.disabled = true;
  btn.textContent = 'Sending...';
  msg.textContent = '';
  msg.style.color = '#333';

  // Basic validation
  const formData = new FormData(this);
  const name = formData.get("name")?.trim();
  const phone = formData.get("phone")?.trim();
  
  if (!name || !phone) {
    msg.textContent = 'Please enter your name and phone number.';
    msg.style.color = 'red';
    btn.disabled = false;
    btn.textContent = 'Send Inquiry';
    return;
  }

  // Prepare form data
 // const formData = new FormData(this);

  try {
    const res = await fetch('send_inquiry.php', {
      method: 'POST',
      body: formData
    });

    const text = await res.text();

    if (res.ok) {
      msg.innerHTML = `<span style="color:green;">✅ Thank you, ${name}! Your inquiry has been submitted successfully. We'll contact you shortly.</span>`;
      this.reset();
    } else {
      msg.innerHTML = `<span style="color:red;">❌ There was a problem: ${text}</span>`;
    }
  } catch (err) {
    msg.innerHTML = `<span style="color:red;">⚠️ Network error. Please try again later.</span>`;
  } finally {
    btn.disabled = false;
    btn.textContent = 'Send Inquiry';
  }
});
