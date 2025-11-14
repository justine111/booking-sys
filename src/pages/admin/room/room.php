<?php
require_once __DIR__ . '/../../../controller/room-controller.php';
$roomManage = new room_controller();

$searchQuery = $_GET['search'] ?? null;
$pageSize = isset($_GET['pageSize']) && is_numeric($_GET['pageSize']) ? (int)$_GET['pageSize'] : 12;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $pageSize;

// $count = $ManageMembers->countAllMemberPerChurch($searchQuery, $churchId);
// $totalPages = ceil($count / $pageSize);

?>

<main class="content-wrapper">
  <div class="content">
    <div class="flex items-center justify-between">
      <button type="button"
        data-modal-show="addHotelModal"
        data-modal-target="addHotelModal"
        class="mb-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-sm px-3 py-2 text-center">
        Add New Hotel
      </button>
    </div>

    <?php require_once __DIR__ . '/./modal/add-new-hotel.php'; ?>

    <div class="relative">
      <div class="overflow-x-auto border rounded-lg border-gray-200 dark:border-gray-700">
        <table class="w-full text-sm text-left rtl:text-right text-gray-600 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-white border-b border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th class="px-6 py-3">No</th>
              <th class="px-6 py-3">Name</th>
              <th class="px-6 py-3">Gender</th>
              <th class="px-6 py-3">Birthday</th>
              <th class="px-6 py-3">Blood type</th>
              <th class="px-6 py-3">Civil Status</th>
              <th class="px-6 py-3">Complate Address</th>
              <th class="px-6 py-3">LinC Status</th>
              <th class="px-6 py-3">Member Status</th>
              <th class="px-6 py-3 text-end">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <?php require_once __DIR__ . '/./components/room-table.php'; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<script>
  <?php 
    require_once __DIR__ . '/../../../assets/js/utils.js'; 
    require_once __DIR__ . '/./js/script.js'; 
  ?>
</script>