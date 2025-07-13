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

      <div class="mb-4 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8">
        <div>
          <h2 class="mt-3 text-lg font-semibold text-orange-500 dark:text-white">
            Popular Space  for Your Needs.
          </h2>
        </div>
        <div class="flex items-center space-x-4">
          <button id="sortDropdownButton1" data-dropdown-toggle="dropdownSort1" type="button" class="flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 focus:z-10 focus:outline-none sm:w-auto">
            <svg class="-ms-0.5 me-2 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M7 4l3 3M7 4 4 7m9-3h6l-6 6h6m-6.5 10 3.5-7 3.5 7M14 18h4" />
            </svg>
            Sort
            <svg class="-me-0.5 ms-2 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
            </svg>
          </button>
          <div id="dropdownSort1" class="z-50 hidden w-40 divide-y divide-gray-100 rounded-lg bg-white shadow border" data-popper-placement="bottom">
            <ul class="p-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400" aria-labelledby="sortDropdownButton">
              <li>
                <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"> The most popular </a>
              </li>
              <li>
                <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"> Newest </a>
              </li>
              <li>
                <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"> Increasing price </a>
              </li>
              <li>
                <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"> Decreasing price </a>
              </li>
              <li>
                <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"> No. reviews </a>
              </li>
              <li>
                <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"> Discount % </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-4 xl:grid-cols-4">

        <?php
        $rooms = $roomController->getHotels($searchQuery, $pageSize, $offset);
        $resultCount = 0;
        foreach ($rooms as $index => $room):
            $resultCount++;
        ?>
          <div class="relative rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
            <!-- Badge/Seal -->
            <div class="absolute top-1 left-2 z-10">
              <span class="inline-block rounded-full <?= $room['status'] == 'available' ? 'bg-orange-500' : 'bg-green-600' ?> px-2 py-1 text-xs font-medium text-white shadow">
                <?= $room['status'] ?>
              </span>
            </div>
            <div class="h-auto w-full">
              <a href="#">
                <img class="mx-auto h-full rounded-t-lg" src="/booking-sys/src/repositories/uploads/<?= $room['filename']; ?>" loading="lazy" decoding="async" />
              </a>
            </div>
            <div class="pt-2 p-2">
              <div class="flex items-center justify-between gap-4">
                <div class="flex items-center justify-end gap-1">
                  <a href="#"
                    class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">
                    <?= $room['title']; ?>
                  </a>
                </div>
              </div>

              <div class="mt-1 flex items-center justify-between">
                <p class="text-xs font-medium text-gray-500 dark:text-white">
                  ₱<?= $room['price_per_night'] ?> for 2 nights
                </p>
                <a href="details.php?id=<?= $room['property_id'] ?>"
                  class="flex items-center gap-1 text-xs font-medium text-gray-500 hover:text-orange-500">
                  <i class="fas fa-eye"></i>
                  View
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
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

