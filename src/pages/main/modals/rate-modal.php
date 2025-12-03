<!-- Rating Modal -->
<div id="rate-modal" tabindex="-1" aria-hidden="true"
  class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
  <div class="relative p-4 w-full max-w-md max-h-full">
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
      <!-- Modal header -->
      <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
          <i class="fa-regular fa-star text-yellow-400"></i> Rate This Property
        </h3>
        <button type="button"
          class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          data-modal-hide="rate-modal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>

      <!-- Modal body -->
      <div class="p-4 md:p-5">
        <form id="ratingForm" class="space-y-4">
          <input type="hidden" id="property_id" name="property_id" value="<?= $roomId; ?>">

          <!-- Your Name (Optional) -->
          <div>
            <label for="client_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
              Your Name (Optional)
            </label>
            <input type="text"
              id="client_name"
              name="client_name"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
              placeholder="Enter your name (or leave blank for Anonymous)">
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
              Leave blank to post as "Anonymous"
            </p>
          </div>

          <!-- Star Rating -->
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
              Your Rating <span class="text-red-500">*</span>
            </label>
            <div class="flex items-center gap-1">
              <input type="hidden" id="rating" name="rating" value="0">
              <div id="star-rating" class="flex gap-1 cursor-pointer">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                  <i class="fa-regular fa-star text-3xl text-gray-300 hover:text-yellow-400 transition-colors"
                    data-rating="<?= $i; ?>"></i>
                <?php endfor; ?>
              </div>
              <span id="rating-text" class="ml-2 text-sm text-gray-600 dark:text-gray-400"></span>
            </div>
            <p id="rating_error" class="mt-1 text-xs text-red-600 hidden"></p>
          </div>

          <!-- Comment -->
          <div>
            <label for="comment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
              Your Review (Optional)
            </label>
            <textarea
              id="comment"
              name="comment"
              rows="4"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
              placeholder="Share your experience..."></textarea>
          </div>

          <!-- Error Message -->
          <div id="general_error" class="hidden p-3 text-sm text-red-800 bg-red-50 rounded-lg dark:bg-gray-800 dark:text-red-400">
          </div>

          <!-- Success Message -->
          <div id="success_message" class="hidden p-3 text-sm text-green-800 bg-green-50 rounded-lg dark:bg-gray-800 dark:text-green-400">
          </div>

          <!-- Submit Button -->
          <button type="submit"
            id="submitReviewBtn"
            class="w-full text-white bg-orange-500 hover:bg-orange-600 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-orange-600 dark:hover:bg-orange-700 dark:focus:ring-orange-800">
            <i class="fa-regular fa-paper-plane"></i> Submit Review
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const starRating = document.getElementById('star-rating');
    const ratingInput = document.getElementById('rating');
    const ratingText = document.getElementById('rating-text');
    const stars = starRating.querySelectorAll('i');
    const form = document.getElementById('ratingForm');
    const submitBtn = document.getElementById('submitReviewBtn');

    // Star rating interaction
    stars.forEach((star, index) => {
      star.addEventListener('click', function() {
        const rating = parseInt(this.getAttribute('data-rating'));
        ratingInput.value = rating;
        updateStars(rating);
        updateRatingText(rating);

        // Clear error
        document.getElementById('rating_error').classList.add('hidden');
      });

      star.addEventListener('mouseenter', function() {
        const rating = parseInt(this.getAttribute('data-rating'));
        highlightStars(rating);
      });
    });

    starRating.addEventListener('mouseleave', function() {
      const currentRating = parseInt(ratingInput.value) || 0;
      updateStars(currentRating);
    });

    function highlightStars(rating) {
      stars.forEach((star, index) => {
        if (index < rating) {
          star.classList.remove('fa-regular', 'text-gray-300');
          star.classList.add('fa-solid', 'text-yellow-400');
        } else {
          star.classList.remove('fa-solid', 'text-yellow-400');
          star.classList.add('fa-regular', 'text-gray-300');
        }
      });
    }

    function updateStars(rating) {
      stars.forEach((star, index) => {
        if (index < rating) {
          star.classList.remove('fa-regular', 'text-gray-300');
          star.classList.add('fa-solid', 'text-yellow-400');
        } else {
          star.classList.remove('fa-solid', 'text-yellow-400');
          star.classList.add('fa-regular', 'text-gray-300');
        }
      });
    }

    function updateRatingText(rating) {
      const texts = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
      ratingText.textContent = texts[rating] || '';
    }

    // Form submission
    form.addEventListener('submit', function(e) {
      e.preventDefault();

      // Hide previous messages
      document.getElementById('general_error').classList.add('hidden');
      document.getElementById('success_message').classList.add('hidden');
      document.getElementById('rating_error').classList.add('hidden');

      // Validate rating
      const rating = parseInt(ratingInput.value);

      if (!rating || rating < 1 || rating > 5) {
        document.getElementById('rating_error').textContent = 'Please select a rating';
        document.getElementById('rating_error').classList.remove('hidden');
        return;
      }

      // Disable button
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting...';

      // Submit form
      const formData = new FormData(form);

      fetch('/booking-sys/src/pages/main/actions/submit-review-action.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<i class="fa-regular fa-paper-plane"></i> Submit Review';

          if (data.error) {
            const errorDiv = document.getElementById('general_error');
            errorDiv.textContent = data.message;
            errorDiv.classList.remove('hidden');
          } else {
            const successDiv = document.getElementById('success_message');
            successDiv.textContent = data.message;
            successDiv.classList.remove('hidden');

            // Reset form after 2 seconds and close modal
            setTimeout(() => {
              form.reset();
              ratingInput.value = '0';
              updateStars(0);
              ratingText.textContent = '';
              successDiv.classList.add('hidden');

              // Close modal
              const modal = document.getElementById('rate-modal');
              modal.classList.add('hidden');

              // Reload page to show updated rating
              location.reload();
            }, 2000);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<i class="fa-regular fa-paper-plane"></i> Submit Review';

          const errorDiv = document.getElementById('general_error');
          errorDiv.textContent = 'An error occurred. Please try again.';
          errorDiv.classList.remove('hidden');
        });
    });
  });
</script>