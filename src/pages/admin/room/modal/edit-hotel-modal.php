<div id="edit-hotel-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
  <div class="relative p-4 w-full max-w-4xl max-h-full">
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
      <!-- Modal header -->
      <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
        <div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Edit Hotel Room
          </h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">Update hotel room details and images</p>
        </div>
        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="edit-hotel-modal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <!-- Modal body -->
      <form action="#" method="post" id="edit-hotel-form" enctype="multipart/form-data">
        <input type="hidden" name="property-id" id="edit-property-id">
        <div class="grid gap-4 mb-4 grid-cols-2 p-4 max-h-[70vh] overflow-y-auto">
          <div class="col-span-2">
            <label for="edit-hotel-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hotel Name <span class="text-red-600">*</span></label>
            <input type="text" name="hotel-name" id="edit-hotel-name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type hotel name" required="">
            <p id="hotel-name-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>
          <div class="col-span-2 sm:col-span-1">
            <label for="edit-address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address <span class="text-red-600">*</span></label>
            <input type="text" name="address" id="edit-address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter address" required="">
            <p id="address-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>
          <div class="col-span-2 sm:col-span-1">
            <label for="edit-city" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">City <span class="text-red-600">*</span></label>
            <input type="text" name="city" id="edit-city" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter city" required="">
            <p id="city-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>
          <div class="col-span-2 sm:col-span-1">
            <label for="edit-price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price per Night <span class="text-red-600">*</span></label>
            <input type="number" name="price" id="edit-price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0.00" step="0.01" required="">
            <p id="price-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>
          <div class="col-span-2 sm:col-span-1">
            <label for="edit-host" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Host <span class="text-red-600">*</span></label>
            <select id="edit-host" name="host" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
              <option value="">Select host</option>
              <?php
              require_once __DIR__ . '/../../../../controller/host-controller.php';
              $hostController = new host_controller();
              $hosts = $hostController->getAllHosts('', 1000, 0);
              foreach ($hosts as $host) {
                echo '<option value="' . htmlspecialchars($host['host_id']) . '">' . htmlspecialchars($host['name']) . '</option>';
              }
              ?>
            </select>
            <p id="host-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>
          <div class="col-span-2">
            <label for="edit-description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
            <textarea id="edit-description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write hotel description here"></textarea>
          </div>
          <div class="col-span-2">
            <label for="edit-amenities" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amenities</label>
            <textarea id="edit-amenities" name="amenities" rows="2" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="WiFi, Pool, Parking, etc."></textarea>
          </div>

          <!-- Image Section -->
          <div class="col-span-2">
            <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3">Property Images</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Upload new images to replace current ones (leave blank to keep existing)</p>
          </div>

          <!-- Image 1 -->
          <div class="col-span-2 sm:col-span-1">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current Image 1</label>
            <img id="current-img1" src="" alt="Image 1" class="w-full h-32 object-cover rounded-lg mb-2">
            <label for="edit-image_1" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Replace Image 1</label>
            <input type="file" name="image_1" id="edit-image_1" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
          </div>

          <!-- Image 2 -->
          <div class="col-span-2 sm:col-span-1">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current Image 2</label>
            <img id="current-img2" src="" alt="Image 2" class="w-full h-32 object-cover rounded-lg mb-2">
            <label for="edit-image_2" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Replace Image 2</label>
            <input type="file" name="image_2" id="edit-image_2" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
          </div>

          <!-- Image 3 -->
          <div class="col-span-2 sm:col-span-1">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current Image 3</label>
            <img id="current-img3" src="" alt="Image 3" class="w-full h-32 object-cover rounded-lg mb-2">
            <label for="edit-image_3" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Replace Image 3</label>
            <input type="file" name="image_3" id="edit-image_3" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
          </div>

          <!-- Image 4 -->
          <div class="col-span-2 sm:col-span-1">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current Image 4</label>
            <img id="current-img4" src="" alt="Image 4" class="w-full h-32 object-cover rounded-lg mb-2">
            <label for="edit-image_4" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Replace Image 4</label>
            <input type="file" name="image_4" id="edit-image_4" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
          </div>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
          <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Update Hotel
          </button>
          <button data-modal-hide="edit-hotel-modal" type="button" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>