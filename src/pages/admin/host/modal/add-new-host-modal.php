<div id="add-new-host" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
  <div class="relative p-4 w-full max-w-xl max-h-full">
    <!-- Modal content -->
    <div class="relative bg-white rounded-xl shadow dark:bg-gray-700">
      <div class="flex items-center justify-between p-4">
        <div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add new host</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">Fill out all required details to add new host</p>
        </div>
        <button type="button" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center" data-modal-hide="add-new-host">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <!-- Modal body -->
      <form action="#" method="post" id="add-new-host-form">
        <div class="grid gap-4 py-4 p-4">
          <div class="col-span-2">
            <label for="name" class="block mb-2 text-sm font-medium text-heading">Host Name <span class="text-gray-600 text-xs">(required)</span></label>
            <input
              type="text"
              name="name"
              id="name"
              placeholder="Enter host name"
              class="bg-white border text-sm rounded-lg focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body">
            <p id="name-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>
        </div>

        <!-- Modal footer -->
        <div class="flex items-center px-4 py-4 border-t border-gray-200 rounded-b dark:border-gray-600">
          <button
            type="submit"
            class="py-2.5 px-5 text-sm font-medium text-white focus:outline-none bg-blue-600 rounded-xl border border-gray-200 focus:z-10 focus:ring-4 focus:ring-gray-100">
            Add new host
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  <?php
  require_once __DIR__ . '/../../../../assets/js/utils.js';
  require_once __DIR__ . '/../js/script.js';
  ?>
</script>