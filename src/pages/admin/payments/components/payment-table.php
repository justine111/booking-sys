<?php
$getPayment = $paymentManage->getAllPayments($searchQuery, $pageSize, $offset, $userRole, $userId);
foreach ($getPayment as $index => $row) :
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
      <?= htmlspecialchars($row['amount_paid'] ?? 'N/A') ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= date('M j, Y', strtotime($row['payment_date'])) ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= date('M j, Y H:i', strtotime($row['check_in_date'])) ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= date('M j, Y H:i', strtotime($row['check_out_date'])) ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= htmlspecialchars($row['client_token'] ?? 'N/A') ?>
    </td>
  </tr>
<?php endforeach; ?>

<?php if (empty($getPayment)): ?>
  <tr>
    <td colspan="12" class="px-6 py-12 text-center">
      <div class="flex flex-col items-center justify-center space-y-4">
        <div class="relative">
          <div class="w-20 h-20 bg-gradient-to-r from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center">
            <i data-lucide="coins" class="w-10 h-10 text-gray-400"></i>
          </div>
          <div class="absolute -top-1 -right-1 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center animate-pulse">
            <i data-lucide="search" class="w-3 h-3 text-white"></i>
          </div>
        </div>
        <div class="text-center">
          <h3 class="text-lg font-semibold text-gray-700 mb-1">No payments found</h3>
          <p class="text-sm text-gray-500 max-w-sm">
            <?php if ($searchQuery): ?>
              No payments match your search criteria "<?= htmlspecialchars($searchQuery) ?>"
            <?php else: ?>
              No payments found in the system. Start by adding your first.
            <?php endif; ?>
          </p>
        </div>
      </div>
    </td>
  </tr>
<?php endif; ?>

<script>
  lucide.createIcons();
</script>