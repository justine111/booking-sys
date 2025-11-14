<div id="addHotelModal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 items-center justify-center hidden w-full p-4 overflow-x-hidden overflow-y-auto bg-gray-900/70 backdrop-blur-sm">
  <div class="relative w-full max-w-4xl max-h-full bg-white rounded-xl overflow-hidden">

    <div class="p-4">
      <div class="flex items-start justify-between">
        <div>
          <h3 class="text-md font-medium text-gray-900">Add New Hotel Room</h3>
          <p class="text-gray-800 text-sm">Fill in the details to create a new hotel room listing</p>
        </div>
        <button type="button"
          class="text-gray-800 hover:text-gray-100 transition-colors duration-200 p-2 rounded-lg hover:bg-gray-600"
          data-modal-hide="addHotelModal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
    </div>

    <!-- Modal body -->
    <form class="">
      <div class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-4">

            <div>
              <label for="hotel-name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                <span class="flex items-center gap-1">
                  <i data-lucide="bed-single" class="w-[16px]"></i>
                  Room Name
                </span>
              </label>
              <input
                type="text"
                name="hotel-name"
                id="hotel-name"
                class="w-full px-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 transition-colors duration-200"
                placeholder="Enter apartment name">
            </div>

            <div>
              <label for="address" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                <span class="flex items-center gap-1">
                  <i data-lucide="map-pin-house" class="w-[16px]"></i>
                  Address
                </span>
              </label>
              <input
                type="text"
                name="address"
                id="address"
                class="w-full px-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 transition-colors duration-200"
                placeholder="Brgy victoria block 101">
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="city" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">City</label>
                <input
                  type="text"
                  name="city"
                  id="city"
                  class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 transition-colors duration-200"
                  placeholder="City">
              </div>
              <div>
                <label for="price" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Price/Night</label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"><i data-lucide="philippine-peso" class="w-[16px]"></i></span>
                  <input type="number" name="price" id="price"
                    class="w-full pl-8 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-colors duration-200"
                    placeholder="0.00" min="0" step="0.01" required>
                </div>
              </div>
            </div>

            <div>
              <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                <span class="flex items-center gap-1">
                  <i data-lucide="user" class="w-[16px]"></i>
                  Unit host
                </span>
              </label>
              <select name="status" id="status"
                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 appearance-none cursor-pointer transition-colors duration-200">
                <option value="available" class="text-green-600">Available</option>
                <option value="unavailable" class="text-red-600">Unavailable</option>
              </select>
            </div>
          </div>

          <!-- Right Column -->
          <div class="space-y-4">
            <div>
              <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                <span class="flex items-center gap-1">
                  <i data-lucide="text-wrap" class="w-[16px]"></i>
                  Description
                </span>
              </label>
              <textarea
                name="description"
                id="description"
                rows="4"
                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 resize-none transition-colors duration-200"
                placeholder="Describe the hotel room features, view, and amenities..."></textarea>
            </div>

            <div>
              <label for="amenities" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                <span class="flex items-center gap-1">
                  <i data-lucide="tv" class="w-[16px]"></i>
                  Amenities
                </span>
              </label>
              <textarea
                name="amenities"
                id="amenities"
                rows="3"
                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 resize-none transition-colors duration-200"
                placeholder="WiFi, Pool, Gym, Breakfast, Parking..."></textarea>
            </div>

            <!-- Image Upload - Compact Version -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                <span class="flex items-center gap-1">
                  <i data-lucide="image" class="w-[16px]"></i>
                  Room Images (Max 4)
                </span>
              </label>

              <!-- Compact Image Preview Grid -->
              <div class="space-y-4">
                <!-- Compact Preview Grid -->
                <div id="imagePreviewGrid" class="grid grid-cols-4 gap-3">
                  <!-- Preview slots - Compact size -->
                  <div class="image-preview-slot border-2 border-dashed border-gray-300 rounded-lg aspect-square flex items-center justify-center bg-gray-50 dark:bg-gray-700 dark:border-gray-600 relative overflow-hidden group cursor-pointer transition-all duration-200 hover:border-blue-400">
                    <div class="text-center p-2">
                      <svg class="w-6 h-6 text-gray-400 mx-auto mb-1 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                      </svg>
                      <p class="text-xs text-gray-500 group-hover:text-blue-400 transition-colors">Add Image</p>
                    </div>
                  </div>
                  <div class="image-preview-slot border-2 border-dashed border-gray-300 rounded-lg aspect-square flex items-center justify-center bg-gray-50 dark:bg-gray-700 dark:border-gray-600 relative overflow-hidden group cursor-pointer transition-all duration-200 hover:border-blue-400">
                    <div class="text-center p-2">
                      <svg class="w-6 h-6 text-gray-400 mx-auto mb-1 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                      </svg>
                      <p class="text-xs text-gray-500 group-hover:text-blue-400 transition-colors">Add Image</p>
                    </div>
                  </div>
                  <div class="image-preview-slot border-2 border-dashed border-gray-300 rounded-lg aspect-square flex items-center justify-center bg-gray-50 dark:bg-gray-700 dark:border-gray-600 relative overflow-hidden group cursor-pointer transition-all duration-200 hover:border-blue-400">
                    <div class="text-center p-2">
                      <svg class="w-6 h-6 text-gray-400 mx-auto mb-1 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                      </svg>
                      <p class="text-xs text-gray-500 group-hover:text-blue-400 transition-colors">Add Image</p>
                    </div>
                  </div>
                  <div class="image-preview-slot border-2 border-dashed border-gray-300 rounded-lg aspect-square flex items-center justify-center bg-gray-50 dark:bg-gray-700 dark:border-gray-600 relative overflow-hidden group cursor-pointer transition-all duration-200 hover:border-blue-400">
                    <div class="text-center p-2">
                      <svg class="w-6 h-6 text-gray-400 mx-auto mb-1 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                      </svg>
                      <p class="text-xs text-gray-500 group-hover:text-blue-400 transition-colors">Add Image</p>
                    </div>
                  </div>
                </div>

                <!-- File Input (Hidden) -->
                <input type="file" name="room-images" id="room-images" accept="image/*" multiple
                  class="hidden" max="4">

                <!-- Upload Info -->
                <div class="text-center">
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    Click on any slot to upload images
                  </p>
                  <p id="imageCount" class="text-xs font-medium text-green-600 mt-1">0/4 images selected</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="flex items-center justify-between p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700/50">
        <button type="button"
          class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-500 transition-colors duration-200">
          Cancel
        </button>
        <button type="submit"
          class="px-8 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg">
          <span class="flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Create Hotel Room
          </span>
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  <?php require_once __DIR__ . '/./js/script.js'; ?>
</script>