
async function loadGoogleReviews() {
  const response = await fetch('/get_reviews.php');
  const reviews = await response.json();

  const slider = document.getElementById('reviews-slider');
  slider.innerHTML = '';

  reviews.forEach(r => {
    const initials = r.author_name.split(' ')
      .map(w => w[0])
      .join('')
      .slice(0, 2)
      .toUpperCase();

    const stars = '★'.repeat(r.rating) + '☆'.repeat(5 - r.rating);

    const card = document.createElement('div');
    card.className = 'review-card';
    card.innerHTML = `
      <div class="review-header">
        <div class="user-icon">${initials}</div>
        <div>
          <h4>${r.author_name}</h4>
          <div class="stars">${stars}</div>
        </div>
      </div>
      <p>${r.text}</p>
      <div class="source">— Google Review</div>
    `;
    slider.appendChild(card);
  });
}

loadGoogleReviews();

