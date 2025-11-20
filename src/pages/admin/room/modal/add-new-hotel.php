<div id="addHotelModal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 items-center justify-center hidden w-full p-4 overflow-x-hidden overflow-y-auto">
  <div class="relative w-full max-w-4xl max-h-full bg-white rounded-lg overflow-hidden shadow-2xl modal-enter">

    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
      <div class="flex items-start justify-between">
        <div>
          <h3 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
            <i data-lucide="house-plus" class="w-6 h-6 text-blue-600"></i>
            Add New Hotel Room
          </h3>
          <p class="text-gray-600 text-sm">Fill in the details to create a new hotel room listing</p>
        </div>
        <button type="button"
          class="text-gray-500 hover:text-gray-700 transition-colors duration-200 p-2 rounded-lg hover:bg-gray-100"
          data-modal-hide="addHotelModal">
          <i data-lucide="x" class="w-5 h-5"></i>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
    </div>

    <!-- Modal body -->
    <form class="" method="post" enctype="multipart/form-data" id="add-hotel">
      <div class="p-6 max-h-[70vh] overflow-y-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Left Column -->
          <div class="space-y-5">
            <div class="form-group">
              <label for="hotel-name" class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center gap-2">
                  <i data-lucide="bed-single" class="w-4 h-4 text-blue-500"></i>
                  Hotel Name
                </span>
              </label>
              <input
                type="text"
                name="hotel-name"
                id="hotel-name"
                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 transition-all duration-200 form-input"
                placeholder="Enter hotel name">
              <p id="hotel-name-error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Address -->
            <div class="form-group">
              <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center gap-2">
                  <i data-lucide="map-pin-house" class="w-4 h-4 text-blue-500"></i>
                  Address
                </span>
              </label>
              <input
                type="text"
                name="address"
                id="address"
                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 transition-all duration-200 form-input"
                placeholder="Enter full address">
              <p id="address-error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- City & Price -->
            <div class="grid grid-cols-2 gap-4">
              <div class="form-group">
                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                <input
                  type="text"
                  name="city"
                  id="city"
                  class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 transition-all duration-200 form-input"
                  placeholder="City">
                <p id="city-error" class="mt-1 text-sm text-red-600 hidden"></p>
              </div>
              <div class="form-group">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price/Night</label>
                <div class="relative">
                  <i data-lucide="philippine-peso" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                  <input
                    type="number"
                    name="price"
                    id="price"
                    class="w-full pl-8 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 transition-all duration-200 form-input"
                    placeholder="0.00" min="0" step="0.01">
                  <p id="price-error" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>
              </div>
            </div>

            <!-- Host -->
            <div class="form-group">
              <label for="host" class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center gap-2">
                  <i data-lucide="user" class="w-4 h-4 text-blue-500"></i>
                  Unit Host
                </span>
              </label>
              <select name="host" id="host"
                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 appearance-none cursor-pointer transition-all duration-200 form-input">
                <option value="1" class="text-green-600">Available</option>
                <option value="2" class="text-red-600">Unavailable</option>
              </select>
              <p id="host-error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>
          </div>

          <!-- Right Column -->
          <div class="space-y-5">
            <!-- Description -->
            <div class="form-group">
              <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center gap-2">
                  <i data-lucide="text-wrap" class="w-4 h-4 text-blue-500"></i>
                  Description
                </span>
              </label>
              <textarea
                name="description"
                id="description"
                rows="4"
                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 resize-none transition-all duration-200 form-input"
                placeholder="Describe the hotel room features, view, and amenities..."></textarea>
            </div>

            <!-- Amenities -->
            <div class="form-group">
              <label for="amenities" class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center gap-2">
                  <i data-lucide="tv" class="w-4 h-4 text-blue-500"></i>
                  Amenities
                </span>
              </label>
              <textarea
                name="amenities"
                id="amenities"
                rows="3"
                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 resize-none transition-all duration-200 form-input"
                placeholder="WiFi, Pool, Gym, Breakfast, Parking..."></textarea>
            </div>

            <!-- Image Upload -->
            <div class="form-group">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center gap-2">
                  <i data-lucide="image" class="w-4 h-4 text-blue-500"></i>
                  Room Images (Max 4)
                </span>
              </label>

              <div class="space-y-4">
                <div id="imagePreviewGrid" class="grid grid-cols-4 gap-3">
                  <!-- Image Slot 1 -->
                  <div class="image-slot-container relative">
                    <input type="file" name="image_1" id="image_1" accept="image/*"
                      class="hidden image-input" data-slot="1">
                    <div class="image-preview-slot border-2 border-dashed border-gray-300 rounded-lg aspect-square flex items-center justify-center bg-gray-50 relative overflow-hidden group cursor-pointer transition-all duration-200 hover:border-blue-400"
                      data-slot="1">
                      <div class="text-center p-2">
                        <i data-lucide="plus" class="w-6 h-6 text-gray-400 mx-auto mb-1 group-hover:text-blue-400 transition-colors"></i>
                        <p class="text-xs text-gray-500 group-hover:text-blue-400 transition-colors">Image 1</p>
                      </div>
                    </div>
                    <input type="hidden" name="image_1_filename" id="image_1_filename" value="">
                  </div>

                  <!-- Image Slot 2 -->
                  <div class="image-slot-container relative">
                    <input type="file" name="image_2" id="image_2" accept="image/*"
                      class="hidden image-input" data-slot="2">
                    <div class="image-preview-slot border-2 border-dashed border-gray-300 rounded-lg aspect-square flex items-center justify-center bg-gray-50 relative overflow-hidden group cursor-pointer transition-all duration-200 hover:border-blue-400"
                      data-slot="2">
                      <div class="text-center p-2">
                        <i data-lucide="plus" class="w-6 h-6 text-gray-400 mx-auto mb-1 group-hover:text-blue-400 transition-colors"></i>
                        <p class="text-xs text-gray-500 group-hover:text-blue-400 transition-colors">Image 2</p>
                      </div>
                    </div>
                    <input type="hidden" name="image_2_filename" id="image_2_filename" value="">
                  </div>

                  <!-- Image Slot 3 -->
                  <div class="image-slot-container relative">
                    <input type="file" name="image_3" id="image_3" accept="image/*"
                      class="hidden image-input" data-slot="3">
                    <div class="image-preview-slot border-2 border-dashed border-gray-300 rounded-lg aspect-square flex items-center justify-center bg-gray-50 relative overflow-hidden group cursor-pointer transition-all duration-200 hover:border-blue-400"
                      data-slot="3">
                      <div class="text-center p-2">
                        <i data-lucide="plus" class="w-6 h-6 text-gray-400 mx-auto mb-1 group-hover:text-blue-400 transition-colors"></i>
                        <p class="text-xs text-gray-500 group-hover:text-blue-400 transition-colors">Image 3</p>
                      </div>
                    </div>
                    <input type="hidden" name="image_3_filename" id="image_3_filename" value="">
                  </div>

                  <!-- Image Slot 4 -->
                  <div class="image-slot-container relative">
                    <input type="file" name="image_4" id="image_4" accept="image/*"
                      class="hidden image-input" data-slot="4">
                    <div class="image-preview-slot border-2 border-dashed border-gray-300 rounded-lg aspect-square flex items-center justify-center bg-gray-50 relative overflow-hidden group cursor-pointer transition-all duration-200 hover:border-blue-400"
                      data-slot="4">
                      <div class="text-center p-2">
                        <i data-lucide="plus" class="w-6 h-6 text-gray-400 mx-auto mb-1 group-hover:text-blue-400 transition-colors"></i>
                        <p class="text-xs text-gray-500 group-hover:text-blue-400 transition-colors">Image 4</p>
                      </div>
                    </div>
                    <input type="hidden" name="image_4_filename" id="image_4_filename" value="">
                  </div>
                </div>

                <!-- Upload Info -->
                <div class="text-center">
                  <p class="text-xs text-gray-500">
                    Click on any slot to upload individual images
                  </p>
                  <p id="imageCount" class="text-xs font-medium text-green-600 mt-1">0/4 images selected</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="flex items-center justify-between p-6 border-t border-gray-200 bg-gray-50">
        <div class="text-sm text-gray-500">
          All fields are required unless marked optional
        </div>
        <div class="flex gap-3">
          <button type="button"
            class="px-5 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200">
            Cancel
          </button>
          <button type="submit"
            class="px-5 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg btn-submit shadow-md">
            <span class="flex items-center gap-2">
              <i data-lucide="house-plus" class="w-4 h-4"></i>
              Create Hotel Room
            </span>
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  <?php
  require_once __DIR__ . '/./js/script.js';

  require_once __DIR__ . '/../../../../assets/js/utils.js';
  require_once __DIR__ . '/../js/script.js';
  ?>
</script>