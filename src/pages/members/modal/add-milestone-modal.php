<div id="add-milestone" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 overflow-y-auto">
  <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

    <!-- Modal panel -->
    <div class="relative inline-block w-full max-w-md px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-xl sm:my-8 sm:align-middle sm:p-6 border border-gray-300 dark:bg-gray-800 dark:border-gray-700">

      <!-- Header -->
      <div class="flex items-start justify-between pb-4 dark:border-gray-700">
        <div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add Milestone</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">Fill out all required details to add a milestone</p>
        </div>
        <button type="button" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors" data-modal-toggle="add-milestone">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>

      <!-- Form -->
      <form method="post" id="addMilestone" class="mt-6 space-y-6">
        <!-- File Upload Section -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Upload Certificate
          </label>
          <div class="flex items-center justify-center w-full">
            <label for="file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
              <div class="flex flex-col items-center justify-center">
                <svg class="w-8 h-8 mb-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <p class="mb-1 text-sm text-gray-500 dark:text-gray-400">
                  <span class="font-medium">Click to upload</span>
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">PDF, Excel, Word, Text files</p>
              </div>
              <input id="file" name="file1" type="file" multiple class="hidden" />
            </label>
          </div>

          <!-- File preview area -->
          <div id="filePreview" class="mt-3 space-y-2"></div>
        </div>

        <input type="hidden" name="membercode" value="<?= $details['member_code']; ?>">

        <!-- Form Fields -->
        <div class="grid grid-cols-1 gap-4">
          <!-- Description -->
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Description <span class="text-gray-500 text-xs ml-1">(required)</span>
            </label>
            <input
              type="text"
              name="title"
              id="title"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-gray-900 transition-colors"
              placeholder="Enter lesson name">
            <p id="title-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <!-- Pathway Lesson -->
          <div>
            <label for="pathway" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Pathway Lesson
            </label>
            <select
              id="pathway"
              name="pathway"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-gray-900 transition-colors">
              <option value="" selected disabled>Select pathway</option>
              <?php
              $pathway = $memberDetails->milestoneTag();
              foreach ($pathway as $row):
              ?>
                <option value="<?= $row['id'] ?>"><?= $row['abbreviation'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <!-- Certificate Code -->
            <div>
              <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Certificate Code
              </label>
              <input
                type="text"
                name="code"
                id="code"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-gray-900 transition-colors"
                placeholder="Enter certificate code">
            </div>

            <!-- Date Finished -->
            <div>
              <label for="datefinished" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Date Finished <span class="text-gray-500 text-xs ml-1">(required)</span>
              </label>
              <input
                type="date"
                name="datefinished"
                id="datefinished"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-gray-900 transition-colors">
              <p id="datefinished-error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>
          </div>

          <!-- Notes -->
          <div>
            <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Notes
            </label>
            <textarea
              id="note"
              name="note"
              rows="3"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-gray-900 transition-colors resize-none"
              placeholder="Add any additional notes..."></textarea>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4">
          <button
            type="button"
            class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors"
            data-modal-toggle="add-milestone">
            Cancel
          </button>
          <button
            type="submit"
            class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Add Milestone
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.getElementById('file').addEventListener('change', function(event) {
    const previewContainer = document.getElementById('filePreview');
    previewContainer.innerHTML = ''; // Clear previous previews

    Array.from(event.target.files).forEach(file => {
      const fileType = file.type;
      let icon = 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';

      if (fileType.includes('pdf')) {
        icon = 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
      } else if (fileType.includes('word') || fileType.includes('text')) {
        icon = 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
      } else if (fileType.includes('excel') || fileType.includes('spreadsheet')) {
        icon = 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
      }

      const fileItem = document.createElement('div');
      fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600';
      fileItem.innerHTML = `
        <div class="flex items-center space-x-3">
          <svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${icon}"></path>
          </svg>
          <div class="min-w-0 flex-1">
            <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">${file.name}</p>
            <p class="text-xs text-gray-500">${(file.size / 1024).toFixed(1)} KB</p>
          </div>
        </div>
        <button type="button" class="text-gray-400 hover:text-red-500 transition-colors remove-file ml-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      `;

      previewContainer.appendChild(fileItem);
    });

    // Handle remove buttons
    document.querySelectorAll('.remove-file').forEach(btn => {
      btn.addEventListener('click', e => {
        e.target.closest('div').remove();
      });
    });
  });
</script>