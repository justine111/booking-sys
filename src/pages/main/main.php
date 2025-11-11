<?php
require_once __DIR__ . '/../../controller/room-controller.php';

$roomController = new RoomsController();
$searchQuery = $_GET['search'] ?? null;
$pageSize = isset($_GET['pageSize']) && is_numeric($_GET['pageSize']) ? (int)$_GET['pageSize'] : 12;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $pageSize;

$count = $roomController->countHotels($searchQuery);
$totalPages = ceil($count / $pageSize);

$basePath = '/booking-sys/src/pages/main';
require_once __DIR__ . '/components/header.php'; // header

?>

<section class="py-8 antialiased mt-16 mb-12">
  <div class="mx-auto max-w-screen-2xl px-4 2xl:px-0">
    <form class="max-w-lg mx-auto sticky top-0 z-10" method="GET">
      <label for="default-search" class="text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
      <div class="relative">
        <input
          type="search"
          name="search"
          id="default-search"
          value="<?= htmlspecialchars($searchQuery) ?? ''; ?>"
          class="block w-full p-4 text-sm text-gray-900 border border-gray-300 rounded-full bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search for homes, villas, and unique stays ..." />
        <button
          type="submit"
          class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 rounded-full focus:ring-4 focus:outline-none focus:ring-blue-300 p-2">
          <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
          </svg>
        </button>
      </div>
    </form>

    <div class="mb-4 mt-4 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8">
      <div class="flex items-center space-x-2">
        <h2 class="mt-3 text-lg font-bold text-orange-500">
          Popular Space for Your Needs.
        </h2>

        <div class="">
          <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
            <li class="me-2" role="presentation">
              <button class="inline-block p-4 border rounded-full active" id="star-hotel-tab" data-tabs-target="#star-hotel" type="button" role="tab" aria-controls="star-hotel" aria-selected="true">Star Hotels</button>
            </li>
            <li class="me-2" role="presentation">
              <button class="inline-block p-4 border rounded-full hover:text-gray-600 hover:border-gray-300" id="deluxe-hotel-tab" data-tabs-target="#deluxe-hotel" type="button" role="tab" aria-controls="deluxe-hotel" aria-selected="false">Deluxe Hotels</button>
            </li>
            <li class="me-2" role="presentation">
              <button class="inline-block p-4 border rounded-full hover:text-gray-600 hover:border-gray-300" id="luxury-hotel-tab" data-tabs-target="#luxury-hotel" type="button" role="tab" aria-controls="luxury-hotel" aria-selected="false">Luxury Hotels</button>
            </li>
            <li role="presentation">
              <button class="inline-block p-4 border rounded-full hover:text-gray-600 hover:border-gray-300" id="budget-hotel-tab" data-tabs-target="#budget-hotel" type="button" role="tab" aria-controls="budget-hotel" aria-selected="false">Budget Hotels</button>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div id="default-tab-content">
      <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" id="star-hotel" role="tabpanel" aria-labelledby="star-hotel-tab">
        <?php $rooms = $roomController->getHotels($searchQuery, $pageSize, $offset);
          $resultCount = 0;
          foreach ($rooms as $index => $room):
          $resultCount++;
        ?>

        <div class="group relative overflow-hidden rounded-xl bg-white shadow-md transition-all duration-300 hover:shadow-xl dark:bg-gray-800">
          <div class="absolute top-3 left-3 z-10">
            <span class="inline-flex items-center rounded-full <?= $room['status'] == 'available' ? 'bg-orange-500' : 'bg-emerald-600' ?> px-3 py-1 text-xs font-semibold text-white shadow-md">
              <?= ucfirst($room['status']) ?>
            </span>
          </div>
        
          <div class="aspect-[4/3] w-full overflow-hidden">
            <a href="details.php?id=<?= $room['property_id'] ?>">
              <img class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" 
                src="/booking-sys/src/repositories/uploads/<?= $room['filename']; ?>" 
                alt="<?= $room['title']; ?>"/>
            </a>
          </div>

          <div class="p-4">
            <div class="mb-2 flex items-start justify-between">
              <a href="details.php?id=<?= $room['property_id'] ?>"
                class="text-lg font-semibold text-gray-800 hover:text-orange-500 dark:text-white dark:hover:text-orange-400">
                <?= $room['title']; ?>
              </a>
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-600 dark:text-gray-300">4.8</span>
              </div>
            </div>
          
            <p class="mb-3 flex items-center text-sm text-gray-500 dark:text-gray-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <?= $room['location'] ?? 'City Center' ?>
            </p>
          
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Starting from</p>
                <p class="text-lg font-bold text-orange-500">₱<?= number_format($room['price_per_night']) ?><span class="text-sm font-normal text-gray-500 dark:text-gray-400">/night</span></p>
              </div>
              <a href="details.php?id=<?= $room['property_id'] ?>"
                class="rounded-lg bg-orange-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2">
                View Details
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

        <!-- Deluxe Hotels Tab -->
        <div class="hidden grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" id="deluxe-hotel" role="tabpanel" aria-labelledby="deluxe-hotel-tab">
            <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Deluxe Hotels</h3>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Premium accommodations with extra amenities.</p>
            </div>
        </div>

        <!-- Luxury Hotels Tab -->
        <div class="hidden grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" id="luxury-hotel" role="tabpanel" aria-labelledby="luxury-hotel-tab">
            <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Luxury Hotels</h3>
                <p class="mt-2 text-gray-600 dark:text-gray-400">The most exclusive and high-end accommodations.</p>
            </div>
        </div>

        <!-- Budget Hotels Tab -->
        <div class="hidden grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" id="budget-hotel" role="tabpanel" aria-labelledby="budget-hotel-tab">
            <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Budget Hotels</h3>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Affordable options for cost-conscious travelers.</p>
            </div>
        </div>
    </div>

    <div class="w-full text-center">
      <div class="flex flex-col items-center">
      <span class="text-sm text-gray-700 dark:text-gray-400">
        Showing <span class="font-semibold text-gray-900 dark:text-white"><?= $page ?></span>
        to <span class="font-semibold text-gray-900 dark:text-white"><?= $resultCount; ?></span>
        of <span class="font-semibold text-gray-900 dark:text-white"><?= $count ?></span> Entries
      </span>
      <!-- Buttons -->
      <div class="inline-flex mt-2 xs:mt-0">
        <a href="<?= $basePath ?>/main.php?page=<?= $page - 1; ?><?= $searchQuery ? '&search=' . urlencode($searchQuery) : '' ?>" 
        class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white bg-gray-800 rounded-s hover:bg-gray-900 <?= $page > 1 ? '' : 'pointer-events-none uppercase opacity-50'; ?>">
        Prev
        </a>
        <a href="<?= $basePath ?>/main.php?page=<?= $page + 1; ?><?= $searchQuery ? '&search=' . urlencode($searchQuery) : '' ?>"
        class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white bg-gray-800 border-0 border-s border-gray-700 rounded-e hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white <?= $page < $totalPages ? '' : 'pointer-events-none uppercase opacity-50'; ?>">
        Next
        </a>
      </div>
      </div>
    </div>
  </div>
</section>

<?php 
  require_once __DIR__ . '/components/footer.php';
  require_once __DIR__ . '/../ai/index.php'; 
?>

