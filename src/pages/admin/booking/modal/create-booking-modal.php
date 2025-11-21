<?php
require_once __DIR__ . '/../../../../controller/room-controller.php';

$roomController = new room_controller();
$hotels = $roomController->getHotelListAvailable();
?>

<div id="create-booking-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
  <div class="relative p-4 w-full max-w-xl max-h-full">
    <!-- Modal content -->
    <div class="relative bg-white rounded-xl shadow dark:bg-gray-700">
      <div class="flex items-center justify-between p-4">
        <div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Create new booking</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">Fill out all required details to create new booking</p>
        </div>
        <button type="button" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center" data-modal-hide="create-booking-modal">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <!-- Modal body -->
      <form action="#" method="post" id="create-booking-form">
        <div class="grid gap-4 grid-cols-2 py-4 p-4">
          <div class="col-span-2">
            <label for="title" class="block mb-2 text-sm font-medium text-heading">Unit name</label>
            <select name="title" id="title" class="bg-white border text-sm rounded-lg focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body">
              <option value="" selected disabled>Choose unit</option>
              <?php foreach ($hotels as $hotel) { ?>
                <option value="<?= $hotel['property_id'] ?>"><?= $hotel['title'] ?></option>
              <?php } ?>
            </select>
            <p id="title-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2 sm:col-span-1">
            <label for="client_name" class="block mb-2 text-sm font-medium text-heading">Client name</label>
            <input
              type="text"
              name="client_name"
              id="client_name"
              class="bg-white border text-sm rounded-lg focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body">
            <p id="client_name-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2 sm:col-span-1">
            <label for="contact_no" class="block mb-2 text-sm font-medium text-heading">Contact no</label>
            <input
              type="number"
              name="contact_no"
              id="contact_no"
              class="bg-white border text-sm rounded-lg focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body">
            <p id="contact_no-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2 sm:col-span-1">
            <label for="duration" class="block mb-2 text-sm font-medium text-heading">Duration</label>
            <input
              type="text"
              name="duration"
              id="duration"
              class="bg-white border text-sm rounded-lg focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body">
          </div>

          <div class="col-span-2 sm:col-span-1">
            <label for="payment" class="block mb-2 text-sm font-medium text-heading">Payment</label>
            <input
              type="number"
              name="payment"
              id="payment"
              class="bg-white border text-sm rounded-lg focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body">
            <p id="payment-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2 sm:col-span-1">
            <label for="status" class="block mb-2 text-sm font-medium text-heading">Status</label>
            <select name="status" id="status" class="bg-white border text-sm rounded-lg focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body">
              <option value="1">Pending</option>
              <option value="5">Available</option>
              <option value="6">Booked</option>
            </select>
            <p id="status-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2 sm:col-span-1">
            <label for="request_date" class="block mb-2 text-sm font-medium text-heading">Request date</label>
            <input
              type="text"
              name="request_date"
              id="request_date"
              value="<?= date('Y-m-d') ?>"
              class="bg-white border text-sm rounded-lg focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
              readonly>
          </div>

          <div class="col-span-2 sm:col-span-1">
            <label for="check_in_date" class="block mb-2 text-sm font-medium text-heading">Check In Date <span class="text-gray-600 text-xs">(required)</span></label>
            <input
              type="datetime-local"
              name="check_in_date"
              id="check_in_date"
              class="bg-white border text-sm rounded-lg focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
              placeholder="">
            <p id="check_in_date-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <div class="col-span-2 sm:col-span-1">
            <label for="check_out_date" class="block mb-2 text-sm font-medium text-heading">Check Out Date <span class="text-gray-600 text-xs">(required)</span></label>
            <input
              type="datetime-local"
              name="check_out_date"
              id="check_out_date"
              class="bg-white border text-sm rounded-lg focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
              placeholder="">
            <p id="check_out_date-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>
        </div>

        <!-- Modal footer -->
        <div class="flex items-center px-4 py-4 border-t border-gray-200 rounded-b dark:border-gray-600">
          <button
            type="submit"
            class="py-2.5 px-5 text-sm font-medium text-white focus:outline-none bg-blue-600 rounded-xl border border-gray-200 focus:z-10 focus:ring-4 focus:ring-gray-100">
            Create Booking
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