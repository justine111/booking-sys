<?php
$getHotel = $roomManage->getListOfHotels($searchQuery, $pageSize, $offset, $userRole, $userId);
foreach ($getHotel as $index => $row) :
?>

  <tr class="bg-white dark:bg-gray-800">
    <td scope="row" class="px-6 py-3">
      <?= (int)$offset + $index + 1; ?>
    </td>
    <td class="px-6 py-3">
      <div class="flex items-center">
        <img class="w-8 h-8 rounded-md cursor-pointer mr-2"
          src="/booking-sys/src/repositories/uploads/<?= htmlspecialchars($row['img1']) ?: 'avatar.png'; ?>"
          alt="image"
          lazy="loading"
          onclick="openImagePreview(this.src)" />
        <div>
          <span class="font-semibold text-gray-900 dark:text-gray-100 block leading-none"><?= htmlspecialchars($row['title']); ?></span>
          <span class="text-xs text-gray-600 dark:text-gray-300">Unit ID: <?= htmlspecialchars($row['property_id']) ?></span>
        </div>
      </div>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= htmlspecialchars($row['address']) ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?php
      $description = $row['description'] ?? 'N/A';
      if ($description !== 'N/A') {
        $words = explode(' ', $description);
        if (count($words) > 60) {
          $description = implode(' ', array_slice($words, 0, 60)) . '...';
        }
      }
      echo htmlspecialchars($description);
      ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= htmlspecialchars($row['amenities'] ?? 'N/A') ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= number_format($row['price_per_night'], 2) ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= date('M j, Y', strtotime($row['created_at'])) ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= htmlspecialchars($row['name'] ?? 'N/A') ?>
    </td>
    <td class="px-6 py-3">
      <?php
      $status = $row['status'] ?? 'N/A';
      $badgeClass = '';
      switch ($status) {
        case 'pending':
          $badgeClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
          break;
        case 'confirmed':
        case 'complete':
        case 'available':
          $badgeClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
          break;
        case 'cancelled':
          $badgeClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
          break;
        case 'booked':
          $badgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
          break;
        default:
          $badgeClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
          break;
      }
      ?>
      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $badgeClass; ?>">
        <?= htmlspecialchars(ucfirst($status)); ?>
      </span>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <div class="flex items-end justify-end">
        <button data-popover-target="popover-<?= htmlspecialchars($row['property_id']); ?>" data-popover-trigger="click" data-popover-placement="bottom" data-popover-offset="6" type="button" class="rounded-md text-sm px-2">
          <i data-lucide="ellipsis" class="w-[18px] text-gray-900 dark:text-gray-200"></i>
        </button>
        <div data-popover id="popover-<?= htmlspecialchars($row['property_id']); ?>"
          role="tooltip"
          class="absolute z-10 invisible inline-block w-40 text-sm text-gray-900 transition-opacity duration-300 bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 rounded-lg shadow-sm opacity-0">
          <div class="px-3 text-gray-900 text-left rounded-t-lg border-b border-gray-200 dark:text-white dark:border-gray-700 py-2">
            <h3 class="font-semibold">Actions</h3>
          </div>
          <div class="flex flex-col gap-y-2 py-2">
            <?php if ($userRole == 1 || $userRole == 2): ?>
              <?php if (isset($row['is_active']) && $row['is_active'] == 1): ?>
                <!-- Approval Actions for Pending Properties -->
                <button onclick="approveProperty(<?= $row['property_id'] ?>)"
                  class="btn btn-ghost btn-sm px-3 flex items-center justify-start gap-x-1 py-1 text-green-600 dark:text-green-400 hover:text-white hover:bg-green-600 dark:hover:bg-green-600 transition-all">
                  <i data-lucide="check-circle" class="w-4"></i>
                  <span>Approve</span>
                </button>
                <button onclick="rejectProperty(<?= $row['property_id'] ?>)"
                  class="btn btn-ghost btn-sm px-3 flex items-center justify-start gap-x-1 py-1 text-red-600 dark:text-red-400 hover:text-white hover:bg-red-600 dark:hover:bg-red-600 transition-all">
                  <i data-lucide="x-circle" class="w-4"></i>
                  <span>Reject</span>
                </button>
                <hr class="border-gray-200 dark:border-gray-600 my-1" />
              <?php endif; ?>
            <?php endif; ?>

            <button onclick="editRoom(<?= $row['property_id'] ?>)" data-modal-target="edit-hotel-modal" data-modal-toggle="edit-hotel-modal" class="btn btn-ghost btn-sm px-3 flex items-center justify-start gap-x-1 py-1 text-gray-800 dark:text-white hover:text-gray-800 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
              <i data-lucide="edit" class="w-4"></i>
              <span>Edit</span>
            </button>
            <a class="btn btn-ghost btn-sm px-3 flex items-center justify-start gap-x-1 py-1 text-gray-800 dark:text-white hover:text-gray-800 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all"
              href="<?= $basePath ?>/details?member_id=<?= $row['property_id'] ?>">
              <i data-lucide="eye" class="w-4"></i>
              <span>View Details</span>
            </a>
          </div>
        </div>
      </div>
    </td>
  </tr>
<?php endforeach; ?>

<?php if (empty($getHotel)): ?>
  <tr>
    <td colspan="12" class="px-6 py-12 text-center">
      <div class="flex flex-col items-center justify-center space-y-4">
        <div class="relative">
          <div class="w-20 h-20 bg-gradient-to-r from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center">
            <i data-lucide="user-search" class="w-10 h-10 text-gray-400"></i>
          </div>
          <div class="absolute -top-1 -right-1 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center animate-pulse">
            <i data-lucide="search" class="w-3 h-3 text-white"></i>
          </div>
        </div>
        <div class="text-center">
          <h3 class="text-lg font-semibold text-gray-700 mb-1">No properties found</h3>
          <p class="text-sm text-gray-500 max-w-sm">
            <?php if ($searchQuery): ?>
              No properties match your search criteria "<?= htmlspecialchars($searchQuery) ?>"
            <?php else: ?>
              No properties found in the system. Start by adding your first.
            <?php endif; ?>
          </p>
        </div>
        <button
          data-modal-target="addHotelModal"
          data-modal-toggle="addHotelModal"
          class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200">
          <i data-lucide="plus" class="w-4 h-4"></i>
          Add First Property
        </button>
      </div>
    </td>
  </tr>
<?php endif; ?>

<script>
  lucide.createIcons();
</script>