<div class="w-full mt-2 flex flex-col sm:flex-row items-center justify-between border-gray-200 dark:border-gray-700">

  <!-- Pagination Info -->
  <div class="text-sm sm:text-sm text-gray-600 dark:text-gray-300 font-medium">
    Page <span class="font-semibold text-gray-900 dark:text-white"><?= $page; ?></span> of
    <span class="font-semibold text-gray-900 dark:text-white"><?= $totalPages; ?></span>
    — <span class="text-gray-500"><?= $count; ?> total records</span>
  </div>

  <!-- Pagination Controls -->
  <div class="flex items-center gap-2">

    <!-- Per Page Selector -->
    <select
      id="per-page"
      class="bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm rounded-md border border-gray-300 dark:border-gray-600 px-2 py-1 focus:ring-2 focus:ring-[#003470] transition"
      onchange="window.location.href='?page=1&pageSize=' + this.value + '&search=<?= htmlspecialchars($searchQuery); ?>'">
      <option value="12" <?= $pageSize == 12 ? 'selected' : ''; ?>>12</option>
      <option value="16" <?= $pageSize == 16 ? 'selected' : ''; ?>>16</option>
      <option value="20" <?= $pageSize == 20 ? 'selected' : ''; ?>>20</option>
      <option value="30" <?= $pageSize == 30 ? 'selected' : ''; ?>>30</option>
      <option value="50" <?= $pageSize == 50 ? 'selected' : ''; ?>>50</option>
      <option value="100" <?= $pageSize == 100 ? 'selected' : ''; ?>>100</option>
    </select>

    <!-- Navigation Arrows -->
    <div class="flex items-center gap-1 text-sm">

      <!-- First -->
      <a href="?page=1&pageSize=<?= $pageSize; ?>&search=<?= $searchQuery; ?>"
        class="flex items-center justify-center w-8 h-8 border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 rounded-md hover:bg-[#003470] hover:text-white transition <?= $page > 1 ? '' : 'opacity-40 pointer-events-none'; ?>">
        <i data-lucide="chevrons-left" class="w-4 text-gray-700 dark:text-white"></i>
      </a>

      <!-- Prev -->
      <a href="?page=<?= $page - 1; ?>&pageSize=<?= $pageSize; ?>&search=<?= $searchQuery; ?>"
        class="flex items-center justify-center w-8 h-8 border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 rounded-md hover:bg-[#003470] hover:text-white transition <?= $page > 1 ? '' : 'opacity-40 pointer-events-none'; ?>">
        <i data-lucide="chevron-left" class="w-4 text-gray-700 dark:text-white"></i>
      </a>

      <!-- Next -->
      <a href="?page=<?= $page + 1; ?>&pageSize=<?= $pageSize; ?>&search=<?= $searchQuery; ?>"
        class="flex items-center justify-center w-8 h-8 border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 rounded-md hover:bg-[#003470] hover:text-white transition <?= $page < $totalPages ? '' : 'opacity-40 pointer-events-none'; ?>">
        <i data-lucide="chevron-right" class="w-4 text-gray-700 dark:text-white"></i>
      </a>

      <!-- Last -->
      <a href="?page=<?= $totalPages; ?>&pageSize=<?= $pageSize; ?>&search=<?= $searchQuery; ?>"
        class="flex items-center justify-center w-8 h-8 border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 rounded-md hover:bg-[#003470] hover:text-white transition <?= $page < $totalPages ? '' : 'opacity-40 pointer-events-none'; ?>">
        <i data-lucide="chevrons-right" class="w-4 text-gray-700 dark:text-white"></i>
      </a>
    </div>
  </div>
</div>

<script>
  lucide.createIcons();
</script>