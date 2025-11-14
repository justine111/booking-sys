<?php
$members = $ManageMembers->getAllMemberPerChurch($searchQuery, $churchId, $pageSize, $offset);
foreach ($members as $index => $member) :
?>

  <tr class="bg-white dark:bg-gray-800">
    <td scope="row" class="px-6 py-3">
      <?= $offset + $index + 1; ?>
    </td>
    <td class="px-6 py-3">
      <div class="flex items-center">
        <img class="w-8 h-8 rounded-md cursor-pointer mr-2"
          src="/tupas/src/repositories/upload/<?= htmlspecialchars($member['profile_path']) ?: 'avatar.png'; ?>"
          alt="image"
          lazy="loading"
          onclick="openImagePreview(this.src)" />
        <div>
          <span class="font-semibold text-gray-900 dark:text-gray-100 block leading-none"><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></span>
          <span class="text-xs text-gray-600 dark:text-gray-300"> <?= htmlspecialchars($member['member_code']) ?></span>
        </div>
      </div>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= htmlspecialchars($member['gender']) ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= date('M j, Y', strtotime($member['birthdate'])) ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= htmlspecialchars($member['blood_type'] ?? 'N/A') ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= htmlspecialchars($member['civil_status'] ?? 'N/A') ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= htmlspecialchars($member['address'] ?? 'N/A') ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= ($member['discipler_id'] && $member['discipler_id'] !== 0)
        ? '<span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-md">In LinC</span>'
        : '<span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-md">Not in LinC</span>';
      ?>
    </td>
    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <?= ($member['commitment_date'] && $member['commitment_date'] !== '0000-00-00')
        ? '<span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-md"> Member</span>'
        : '<span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-md"> Goer</span>';
      ?>
    </td>

    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
      <div class="flex items-end justify-end">
        <button data-popover-target="popover-<?= htmlspecialchars($member['member_id']); ?>" data-popover-trigger="click" data-popover-placement="bottom" data-popover-offset="6" type="button" class="rounded-md text-sm px-2">
          <i data-lucide="ellipsis" class="w-[18px] text-gray-900 dark:text-gray-200"></i>
        </button>
        <div data-popover id="popover-<?= htmlspecialchars($member['member_id']); ?>"
          role="tooltip"
          class="absolute z-10 invisible inline-block w-40 text-sm text-gray-900 transition-opacity duration-300 bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 rounded-lg shadow-sm opacity-0">
          <div class="px-3 text-gray-900 text-left rounded-t-lg border-b border-gray-200 dark:text-white dark:border-gray-700 py-2">
            <h3 class="font-semibold">Actions</h3>
          </div>
          <div class="flex flex-col gap-y-2 py-2">
            <?php if ($member['inactive'] == 0): ?>
              <a class="btn btn-ghost btn-sm px-3 flex items-center justify-start gap-x-1 py-1 text-gray-800 dark:text-white hover:text-gray-800 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all"
                href="<?= $basePath ?>/details?member_id=<?= $member['member_id'] ?>">
                <i data-lucide="eye" class="w-4"></i>
                <span>View Details</span>
              </a>
            <?php endif; ?>
            <a class="btn btn-ghost btn-sm px-3 flex items-center justify-start gap-x-1 py-1 text-gray-800 dark:text-white hover:text-gray-800 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all"
              href="profile-excel-sheet.php?id=<?= htmlspecialchars($member['member_id']); ?>">
              <i data-lucide="download" class="w-4"></i>
              <span>Download</span>
            </a>

            <form method="post" class="setInactiveForm">
              <input type="hidden" name="memberid" value="<?= htmlspecialchars($member['member_id']) ?>">
              <button
                type="submit"
                class="w-full px-3 flex items-center justify-start gap-x-2 py-1 text-gray-800 dark:text-white hover:text-gray-800 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                <i data-lucide="ban" class="w-4"></i>
                <?= ($member['inactive'] == 0) ? 'Set as Inactive' : 'Set as Active'; ?>
              </button>
            </form>
          </div>
        </div>
      </div>
    </td>
  </tr>
<?php endforeach; ?>

<?php if (empty($members)): ?>
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