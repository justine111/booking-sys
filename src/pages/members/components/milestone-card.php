<?php
$memberCode = $details['member_code'];
$milestones = $memberDetails->milestoneListPerMember($memberCode);
?>

<div class="space-y-4 max-h-[650px] overflow-y-auto custom-scrollbar">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 overflow-y-auto custom-scrollbar p-1">

    <?php foreach ($milestones as $milestone): ?>

      <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-lg transition-all duration-300 group">
        <div class="flex items-start justify-between mb-3">
          <div class="flex items-center gap-2">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-800 flex items-center justify-center">
              <i data-lucide="award" class="w-6 h-6 text-white"></i>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 dark:text-white">
                <?= htmlspecialchars($milestone['description'] ?? 'Achievement') ?>
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                <?= date('F j, Y', strtotime($milestone['date_finished'])) ?>
              </p>
            </div>
          </div>
          <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
            <i data-lucide="badge-check" class="w-4 h-4 text-green-600 dark:text-green-400"></i>
          </div>
        </div>

        <!-- Progress/Status -->
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-green-500"></div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Completed</span>
          </div>
          <span class="text-xs text-gray-500 dark:text-gray-400">
            <?= $milestone['pathway_name'] ?? 'TLH Pathway' ?>
          </span>
        </div>

        <!-- Notes -->
        <?php if (!empty($milestone['note'])): ?>
          <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">
            <?= htmlspecialchars($milestone['note']) ?>
          </p>
        <?php endif; ?>

        <!-- Actions -->
        <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
          <div class="flex items-center gap-2">
            <?php if (!empty($milestone['certificate_file_path'])): ?>
              <button class="inline-flex items-center gap-1 text-xs text-gray-900 dark:text-white hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                <i data-lucide="file" class="w-3 h-3"></i>
                Document
              </button>
            <?php endif; ?>
          </div>
          <button class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
            View Details
            <i data-lucide="arrow-right" class="w-3 h-3"></i>
          </button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <?php
  if (empty($milestones)): ?>

    <div class="flex flex-col items-center justify-center py-12 text-center border border-gray-200 dark:border-gray-700 rounded-md">
      <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-4">
        <i data-lucide="award" class="w-10 h-10 text-gray-400"></i>
      </div>
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Milestones Yet</h3>
      <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-4">
        This member hasn't completed any milestones yet. Add their first achievement to get started!
      </p>
      <buttona
        type="button"
        data-modal-target="add-milestone"
        data-modal-toggle="add-milestone"
        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Add First Milestone
      </buttona>
    </div>
  <?php endif; ?>
</div>