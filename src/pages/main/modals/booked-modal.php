<div id="rate-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
  <div class="relative p-4 w-full max-w-md max-h-full">
    <div class="relative bg-white rounded-xl shadow-sm dark:bg-gray-700">
      <!-- Modal header -->
      <div class="flex items-start justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
        <div>
          <h3 class="text-md font-semibold text-gray-900 flex items-start gap-1">
            <i data-lucide="house-plus" class="w-5 h-5 text-orange-600"></i>
            Ask for room reservation
          </h3>
          <p class="text-gray-600 text-sm">Fill in the details to reserve a the room</p>
        </div>
        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="rate-modal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>

      <!-- Modal body -->
      <form class="p-4" method="post" id="reservation-form">
        <?php
        $unitId = $_GET['id'];
        ?>
        <input type="hidden" name="unit" value="<?= $unitId ?>">

        <div class="grid gap-4 mb-4 grid-cols-2">
          <div class="col-span-2">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name <span class="text-gray-500 text-xs">(required)</span></label>
            <input
              type="text"
              name="name"
              id="name"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
              placeholder="">
            <p id="name-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2 sm:col-span-1">
            <label for="phoneno" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact no. <span class="text-gray-500 text-xs">(required)</span></label>
            <input
              type="number"
              name="phoneno"
              id="phoneno"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
              placeholder="">
            <p id="phoneno-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2 sm:col-span-1">
            <label for="stay-duration" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stay Duration <span class="text-gray-500 text-xs">(required)</span></label>
            <select id="stay-duration" name="stay-duration" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
              <option selected disabled>Select stay duration</option>
              <option value="2 Days 1 Night">2 Days / 1 Night</option>
              <option value="3 Days 2 Nights">3 Days / 2 Nights</option>
              <option value="7 Days 6 Nights">7 Days / 6 Nights</option>
            </select>
            <p id="stay-duration-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2 sm:col-span-1">
            <label for="check-in-date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Check-in Date <span class="text-gray-500 text-xs">(required)</span></label>
            <input
              type="date"
              name="check_in_date"
              id="check-in-date"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <p id="check-in-date-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2 sm:col-span-1">
            <label for="check-out-date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Check-out Date <span class="text-gray-500 text-xs">(required)</span></label>
            <input
              type="date"
              name="check_out_date"
              id="check-out-date"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <p id="check-out-date-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2">
            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Message</label>
            <textarea
              id="description"
              name="description"
              rows="4"
              class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Leave a message..."></textarea>
          </div>
        </div>

        <div class="flex items-center p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800" role="alert">
          <i data-lucide="info" class="mr-1"></i>
          <span class="sr-only">Info</span>
          <div>
            <span class="font-medium">Note: </span> After receiving your reservation, our staff will contact you through your phone number to continue the booking process.
          </div>
        </div>

        <button type="submit" class="text-white inline-flex items-center bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 text-center">
          <i data-lucide="plus" class="w-[18px] mr-1"></i>
          Submit Reservation
        </button>
      </form>
    </div>
  </div>
</div>

<script>
  lucide.createIcons();

  <?php
  require_once __DIR__ . '/../../../assets/js/utils.js';
  require_once __DIR__ . '/../js/main.js';
  ?>
</script>