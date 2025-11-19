<?php
$getBooking = $bookingManage->getAllBookings($searchQuery, $pageSize, $offset);
foreach ($getBooking as $index => $row) :
?>

  <tr class="bg-white dark:bg-gray-800">
    <td scope="row" class="px-6 py-3">
      <?= $offset + $index + 1; ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= htmlspecialchars($row['title']) ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= htmlspecialchars($row['client_name'] ?? 'N/A') ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= htmlspecialchars($row['contact_no'] ?? 'N/A') ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= htmlspecialchars($row['duration'] ?? 'N/A') ?> Night(s)
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= htmlspecialchars($row['message'] ?? 'N/A') ?>
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
      <?= date('M j, Y', strtotime($row['created_at'])) ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <div class="flex items-end justify-end">
        <button data-modal-target="booking-details-modal-<?= htmlspecialchars($row['booking_id']); ?>" data-modal-toggle="booking-details-modal-<?= htmlspecialchars($row['booking_id']); ?>" type="button" class="rounded-md text-sm px-2">
          <i data-lucide="ellipsis" class="w-[18px] text-gray-900 dark:text-gray-200"></i>
        </button>

        <?php require __DIR__ . '/../modal/booking-modal.php'; ?>
      </div>
    </td>
  </tr>
<?php endforeach; ?>

<?php if (empty($getBooking)): ?>
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
          <h3 class="text-lg font-semibold text-gray-700 mb-1">No member found</h3>
          <p class="text-sm text-gray-500 max-w-sm">
            <?php if ($searchQuery): ?>
              No member match your search criteria "<?= htmlspecialchars($searchQuery) ?>"
            <?php else: ?>
              No member found in the system. Start by adding your first.
            <?php endif; ?>
          </p>
        </div>
        <button
          data-modal-target="add-member-modal"
          data-modal-toggle="add-member-modal"
          class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200">
          <i data-lucide="plus" class="w-4 h-4"></i>
          Add First Member
        </button>
      </div>
    </td>
  </tr>
<?php endif; ?>

<script>
  lucide.createIcons();
</script>