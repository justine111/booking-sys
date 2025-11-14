<?php
require_once __DIR__ . '/../../controllers/members-controller.php';
$ManageMembers = new member_repository();

$searchQuery = $_GET['search'] ?? null;
$categorySearch = $_GET['category'] ?? null;
$pageSize = isset($_GET['pageSize']) && is_numeric($_GET['pageSize']) ? (int)$_GET['pageSize'] : 12;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $pageSize;

$count = $ManageMembers->countAllMemberPerChurch($searchQuery, $churchId);
$totalPages = ceil($count / $pageSize);

?>

<main id="mainPage" class="content-wrapper">
  <div class="content">

    <div class="relative">
      <div class="flex flex-col gap-4 pb-2">
        <div class="flex items-center justify-between w-full">
          <div>
            <button
              type="button"
              data-modal-target="add-member-modal"
              data-modal-toggle="add-member-modal"
              class="flex items-center text-gray-700 border bg-gray-50 border-gray-300 rounded-lg text-sm px-3 p-2 text-center dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
              <i data-lucide="user-plus" class="w-[18px] mr-1"></i>
              Create New Member
            </button>
          </div>
          <?php require_once __DIR__ . '/./modal/create-new-member-modal.php'; ?>

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
                placeholder="Search Member..."
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
            <?php require_once __DIR__ . '/./components/members-table.php'; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php require_once __DIR__ . '/../../components/pagination.php'; ?>
  </div>
</main>

<script>
  <?php
  require_once __DIR__ . '/../../assets/js/util.js';
  require_once __DIR__ . '/./js/script.js';
  ?>
</script>