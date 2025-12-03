<?php
require_once __DIR__ . '/../../controller/room-controller.php';
require_once __DIR__ . '/../../controller/review-controller.php';

$roomController = new room_controller();
$reviewController = new ReviewController();
$roomId = $_GET['id'];

$roomDetails = $roomController->getHotelById($roomId);
$ratingStats = $reviewController->getPropertyRating($roomId);
?>

<?php require_once __DIR__ . '/components/header.php'; ?>

<section class="py-12 bg-white dark:bg-gray-900 antialiased mt-20">
  <div class="max-w-screen-xl px-4 mx-auto">
    <div class="lg:grid lg:grid-cols-2 lg:gap-12">
      <div class="space-y-4">
        <!-- Main Image -->
        <img
          id="mainPreviewImage"
          class="w-full h-[380px] object-cover rounded-xl shadow-md cursor-pointer hover:opacity-90 transition"
          src="/booking-sys/src/repositories/uploads/<?= $roomDetails['img1']; ?>"
          alt="Room Image"
          onclick="openImageModal(this.src)" />

        <!-- Thumbnail Grid -->
        <div class="grid grid-cols-3 gap-4">
          <img
            class="w-full h-28 object-cover rounded-lg shadow cursor-pointer hover:opacity-80 transition"
            src="/booking-sys/src/repositories/uploads/<?= $roomDetails['img2']; ?>"
            onclick="changeMainImage(this.src)" />
          <img
            class="w-full h-28 object-cover rounded-lg shadow cursor-pointer hover:opacity-80 transition"
            src="/booking-sys/src/repositories/uploads/<?= $roomDetails['img3']; ?>"
            onclick="changeMainImage(this.src)" />
          <img
            class="w-full h-28 object-cover rounded-lg shadow cursor-pointer hover:opacity-80 transition"
            src="/booking-sys/src/repositories/uploads/<?= $roomDetails['img4']; ?>"
            onclick="changeMainImage(this.src)" />
        </div>
      </div>

      <!-- DETAILS SECTION -->
      <div class="mt-8 lg:mt-0">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
          <?= $roomDetails['title']; ?>
        </h1>

        <div class="flex items-center justify-between mt-4">
          <p class="text-3xl font-extrabold text-gray-900 dark:text-white">
            ₱<?= number_format($roomDetails['price_per_night'], 2); ?>
            <span class="text-sm text-gray-500 font-normal">(Per Night)</span>
          </p>
        </div>

        <!-- Rating -->
        <div class="flex items-center gap-2 mt-3">
          <div class="flex gap-1 text-yellow-400">
            <?php
            $avgRating = $ratingStats['average_rating'] ?? 0;
            $fullStars = floor($avgRating);
            $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
            $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);

            // Full stars
            for ($i = 0; $i < $fullStars; $i++):
            ?>
              <i class="fa-solid fa-star text-lg"></i>
            <?php endfor;

            // Half star
            if ($hasHalfStar): ?>
              <i class="fa-solid fa-star-half-stroke text-lg"></i>
            <?php endif;

            // Empty stars
            for ($i = 0; $i < $emptyStars; $i++):
            ?>
              <i class="fa-regular fa-star text-lg"></i>
            <?php endfor; ?>
          </div>
          <p class="text-sm text-gray-500 dark:text-gray-400">
            (<?= number_format($avgRating, 1); ?>)
          </p>
          <a href="#reviews-section" class="text-sm text-blue-600 hover:underline">
            <?= $ratingStats['total_reviews'] ?? 0; ?> Review<?= ($ratingStats['total_reviews'] ?? 0) != 1 ? 's' : ''; ?>
          </a>
        </div>

        <!-- Buttons -->
        <div class="flex gap-4 mt-8">
          <button
            data-modal-target="book-modal"
            data-modal-toggle="book-modal"
            class="flex items-center justify-center py-2.5 px-5 text-sm font-medium 
            text-gray-900 bg-white border border-gray-300 rounded-lg 
            hover:bg-gray-200 transition">
            <i class="fa-regular fa-file pr-2"></i> Reserve Now
          </button>

          <button
            data-modal-target="rate-modal"
            data-modal-toggle="rate-modal"
            class="px-6 py-2.5 text-white text-sm rounded-lg shadow bg-orange-500 hover:bg-orange-600 transition">
            <i class="fa-regular fa-star pr-1"></i> Rate This Hotel
          </button>

          <?php require_once __DIR__ . '/./modals/rate-modal.php'; ?>
          <?php require_once __DIR__ . '/./modals/booked-modal.php'; ?>
        </div>

        <hr class="my-8 border-gray-300 dark:border-gray-700" />

        <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
          <?= $roomDetails['description']; ?>
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Image Preview Modal -->
<div id="imageModal"
  class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50 p-4">
  <span
    class="absolute top-6 right-6 text-white text-3xl cursor-pointer"
    onclick="closeImageModal()">&times;</span>

  <img id="modalImage" class="max-w-4xl max-h-[90vh] rounded-lg shadow-lg">
</div>


<?php require_once __DIR__ . '/components/footer.php'; ?>

<script>
  // Change main preview image
  function changeMainImage(src) {
    document.getElementById("mainPreviewImage").src = src;
  }

  // Open modal with the clicked image
  function openImageModal(src) {
    document.getElementById("modalImage").src = src;
    document.getElementById("imageModal").classList.remove("hidden");
    document.getElementById("imageModal").classList.add("flex");
  }

  // Close modal
  function closeImageModal() {
    document.getElementById("imageModal").classList.add("hidden");
    document.getElementById("imageModal").classList.remove("flex");
  }

  // Close modal when clicking outside the image
  document.getElementById("imageModal").addEventListener("click", function(e) {
    if (e.target === this) closeImageModal();
  });
</script>