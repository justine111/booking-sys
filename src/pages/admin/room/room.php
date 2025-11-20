<?php
require_once __DIR__ . '/../../../controller/room-controller.php';
$roomManage = new room_controller();

$searchQuery = $_GET['search'] ?? null;
$pageSize = isset($_GET['pageSize']) && is_numeric($_GET['pageSize']) ? (int)$_GET['pageSize'] : 12;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $pageSize;

$count = $roomManage->getCountOfHotels($searchQuery);
$totalPages = ceil($count / $pageSize);
?>

<main class="content-wrapper">
  <div class="content">
    <div class="mb-4">
      <h2 class="uppercase font-bold text-blue-900 text-lg">Room Management</h2>
      <p class="text-gray-500 text-sm">Manage all rooms, including their details, availability, and pricing.</p>
    </div>

    <div class="relative">
      <div class="flex flex-col gap-4 pb-2">
        <div class="flex items-center justify-between w-full">
          <div>
            <button type="button"
              data-modal-show="addHotelModal"
              data-modal-target="addHotelModal"
              class="flex items-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-sm px-3 py-2 text-center">
              <i data-lucide="house-plus" class="w-[16px] mr-1"></i>
              Add New Hotel
            </button>
          </div>
          <?php require_once __DIR__ . '/./modal/add-new-hotel.php'; ?>

          <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
              <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
              </svg>
            </div>
            <form method="GET" action="#" class="">
              <input
                type="search"
                name="search"
                id="live-search"
                class="block w-64 sm:w-80 p-2 ps-10 text-sm text-gray-900 dark:text-gray-200 border border-gray-300 dark:bg-gray-800 dark:border-gray-600 rounded-lg bg-gray-50"
                placeholder="Search unit..."
                value="<?= htmlspecialchars($searchQuery) ?? ''; ?>" />
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="relative">
      <div class="overflow-x-auto border rounded-lg border-gray-200 dark:border-gray-700">
        <table class="w-full text-sm text-left rtl:text-right text-gray-600 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-white border-b border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th class="px-6 py-3">No</th>
              <th class="px-6 py-3">Unit name</th>
              <th class="px-6 py-3">Address</th>
              <th class="px-6 py-3">Description</th>
              <th class="px-6 py-3">Amenities</th>
              <th class="px-6 py-3">Unit rate</th>
              <th class="px-6 py-3">Date created</th>
              <th class="px-6 py-3">Unit host</th>
              <th class="px-6 py-3">Status</th>
              <th class="px-6 py-3 text-end">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <?php require_once __DIR__ . '/./components/room-table.php'; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php require_once __DIR__ . '/../../../components/pagination.php'; ?>
  </div>
</main>