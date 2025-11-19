<?php
require_once __DIR__ . '/../../controller/room-controller.php';

$roomController = new room_controller();
$searchQuery = $_GET['search'] ?? null;
$pageSize = isset($_GET['pageSize']) && is_numeric($_GET['pageSize']) ? (int)$_GET['pageSize'] : 12;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $pageSize;

$count = $roomController->countHotels($searchQuery);
$totalPages = ceil($count / $pageSize);

$rooms = $roomController->getHotels($searchQuery, $pageSize, $offset);
$resultCount = 0;

$basePath = '/booking-sys/src/pages/main';
require_once __DIR__ . '/components/header.php';
?>

<section class="py-10 mt-16 mb-16">
  <div class="mx-auto max-w-screen-2xl px-4 2xl:px-0">
    <!-- Search -->
    <form class="max-w-lg mx-auto sticky top-4 z-20" method="GET">
      <div class="relative">
        <input
          type="search"
          name="search"
          value="<?= htmlspecialchars($searchQuery) ?? ''; ?>"
          placeholder="Search for homes, villas, and unique stays..."
          class="w-full rounded-full border border-gray-200 bg-white p-4 pe-12 text-sm shadow-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-300 transition-all duration-200" />
        <button
          type="submit"
          class="absolute bottom-2.5 end-2.5 rounded-full bg-orange-500 p-2 hover:bg-orange-600 focus:ring-4 focus:ring-orange-300 transition-all duration-200">
          <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
          </svg>
        </button>
      </div>
    </form>

    <!-- Section Title + Tabs -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-10 mb-8 gap-4">
      <h2 class="text-2xl font-semibold text-gray-800">
        <span class="text-orange-500">Popular Spaces</span> for Your Next Stay
      </h2>

      <ul class="flex flex-wrap gap-2 text-sm font-medium" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
        <?php
        $tabs = [
          'star-hotel' => 'Star Hotels',
          'deluxe-hotel' => 'Deluxe Hotels',
          'luxury-hotel' => 'Luxury Hotels',
          'budget-hotel' => 'Budget Hotels'
        ];
        $first = true;
        foreach ($tabs as $id => $label): ?>
          <li role="presentation">
            <button
              id="<?= $id ?>-tab"
              data-tabs-target="#<?= $id ?>"
              type="button"
              role="tab"
              class="px-5 py-2 rounded-full transition-all duration-200 <?= $first ? 'bg-orange-500 text-white' : 'border border-gray-300 hover:bg-gray-100 text-gray-700' ?>"
              aria-controls="<?= $id ?>"
              aria-selected="<?= $first ? 'true' : 'false' ?>">
              <?= $label ?>
            </button>
          </li>
        <?php $first = false;
        endforeach; ?>
      </ul>
    </div>

    <!-- Tab Content -->
    <div id="default-tab-content" class="mt-6">
      <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" id="star-hotel" role="tabpanel">
        <?php
        foreach ($rooms as $room):
          $resultCount++;
        ?>
          <div class="group relative overflow-hidden rounded-2xl bg-white shadow-md hover:shadow-lg transition-all duration-300">
            <div class="absolute top-3 left-3 z-10">
              <?php
              $status = $room['status'];
              $statusText = '';
              $statusColorClass = '';

              if ($status >= 1 && $status <= 5) {
                $statusText = 'Available';
                $statusColorClass = 'bg-green-500';
              } elseif ($status == 6) {
                $statusText = 'Booked';
                $statusColorClass = 'bg-gray-500';
              }

              if (!empty($statusText)) {
              ?>
                <span class="inline-flex items-center rounded-full <?= $statusColorClass ?> px-3 py-1 text-xs font-semibold text-white shadow">
                  <?= htmlentities($statusText) ?>
                </span>
              <?php
              }
              ?>
            </div>

            <a href="details.php?id=<?= $room['property_id'] ?>" class="block overflow-hidden">
              <img
                src="/booking-sys/src/repositories/uploads/<?= htmlspecialchars($room['img1']); ?>"
                loading="lazy"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
            </a>

            <div class="p-4 space-y-1">
              <div class="flex justify-between items-start">
                <a href="details.php?id=<?= $room['property_id'] ?>" class="text-lg font-semibold text-orange-500 transition-colors duration-200">
                  <?= htmlspecialchars($room['title']); ?>
                </a>
                <div class="flex items-center text-yellow-400">
                  <i data-lucide="star" class="w-[16px] mr-1"></i>
                  <span class="text-sm font-medium text-gray-600">4.8</span>
                </div>
              </div>

              <p class="flex items-center text-sm text-gray-500">
                <i data-lucide="map-pin" class="w-[16px] mr-1"></i>
                <?= htmlspecialchars($room['address'] ?? 'City Center') ?>
              </p>

              <div class="flex items-center justify-between pt-2">
                <div>
                  <p class="text-sm text-gray-500">Starting from</p>
                  <p class="text-lg font-bold text-orange-500">
                    ₱<?= number_format($room['price_per_night']) ?>
                    <span class="text-sm font-normal text-gray-500">/night</span>
                  </p>
                </div>
                <a href="details.php?id=<?= $room['property_id'] ?>"
                  class="rounded-full bg-orange-500 px-4 py-2 text-sm text-white font-medium hover:bg-orange-600 transition-colors">
                  View
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <?php if (empty($rooms)): ?>
          <div class="col-span-full flex flex-col items-center justify-center py-12">
            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.75 17L9 21m5.25-4l.75 4M4 10V7a4 4 0 014-4h8a4 4 0 014 4v3m-2 0h2a2 2 0 012 2v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5a2 2 0 012-2h2m10 0V7a2 2 0 00-2-2H8a2 2 0 00-2 2v3" />
            </svg>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">No rooms found</h3>
            <p class="text-gray-500 mb-4">We couldn't find any rooms matching your search criteria.</p>
            <a href="<?= $basePath ?>/main.php" class="inline-block px-6 py-2 bg-orange-500 text-white rounded-full hover:bg-orange-600 transition-colors">
              Reset Search
            </a>
          </div>
        <?php endif; ?>
      </div>

      <!-- Other Tabs -->
      <?php
      $placeholders = [
        'deluxe-hotel' => 'Premium accommodations with extra amenities.',
        'luxury-hotel' => 'Exclusive and high-end accommodations.',
        'budget-hotel' => 'Affordable stays for smart travelers.'
      ];
      foreach ($placeholders as $id => $desc): ?>
        <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" id="<?= $id ?>" role="tabpanel">
          <div class="p-6 bg-white rounded-xl shadow text-center">
            <h3 class="text-xl font-semibold text-gray-800"><?= ucfirst(str_replace('-', ' ', $id)) ?></h3>
            <p class="mt-2 text-gray-500"><?= $desc ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="flex flex-col items-center mt-12 text-center">
      <p class="text-sm text-gray-600">
        Showing <span class="font-semibold text-gray-900"><?= $page ?></span> –
        <span class="font-semibold text-gray-900"><?= $resultCount; ?></span> of
        <span class="font-semibold text-gray-900"><?= $count ?></span> entries
      </p>

      <div class="flex mt-3">
        <a href="<?= $basePath ?>/main.php?page=<?= max(1, $page - 1) ?><?= $searchQuery ? '&search=' . urlencode($searchQuery) : '' ?>"
          class="px-4 py-2 text-sm rounded-s bg-gray-800 text-white hover:bg-gray-900 transition-all <?= $page > 1 ? '' : 'opacity-40 pointer-events-none' ?>">Prev</a>
        <a href="<?= $basePath ?>/main.php?page=<?= min($totalPages, $page + 1) ?><?= $searchQuery ? '&search=' . urlencode($searchQuery) : '' ?>"
          class="px-4 py-2 text-sm rounded-e bg-gray-800 text-white hover:bg-gray-900 transition-all <?= $page < $totalPages ? '' : 'opacity-40 pointer-events-none' ?>">Next</a>
      </div>
    </div>
  </div>
</section>

<?php
require_once __DIR__ . '/components/footer.php';
require_once __DIR__ . '/../ai/index.php';
?>
<script>
  lucide.createIcons();
</script>