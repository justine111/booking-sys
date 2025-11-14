<div id="add-member-modal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 overflow-y-auto">
  <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

    <!-- Modal panel -->
    <div class="relative inline-block w-full max-w-2xl px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-xl sm:my-8 sm:align-middle sm:p-6 border border-gray-300 dark:bg-gray-800 dark:border-gray-700">

      <!-- Header -->
      <div class="flex items-start justify-between pb-4 dark:border-gray-700">
        <div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add New Member</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">Fill out all required details to add a new member</p>
        </div>
        <button type="button" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors" data-modal-toggle="add-member-modal">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>

      <!-- Form -->
      <form id="add-new-member" class="mt-6 space-y-6">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <!-- Lastname -->
          <div>
            <label for="lastname" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Last Name <span class="text-gray-500 text-xs ml-1">(required)</span>
            </label>
            <input type="text" name="lastname" id="lastname"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
            <p id="lastname-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <!-- Firstname -->
          <div>
            <label for="firstname" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              First Name <span class="text-gray-500 text-xs ml-1">(required)</span>
            </label>
            <input type="text" name="firstname" id="firstname"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
            <p id="firstname-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <!-- Middlename -->
          <div>
            <label for="middlename" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Middle Name
            </label>
            <input type="text" name="middlename" id="middlename"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
          </div>

          <!-- Birthday -->
          <div>
            <label for="birthday" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Birthday <span class="text-gray-500 text-xs ml-1">(required)</span>
            </label>
            <input type="date" name="birthday" id="birthday"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
            <p id="birthday-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <!-- Civil Status -->
          <div>
            <label for="civilstatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Civil Status <span class="text-gray-500 text-xs ml-1">(required)</span>
            </label>
            <select name="civilstatus" id="civilstatus"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
              <option value="" disabled selected>Select status</option>
              <option value="Single">Single</option>
              <option value="Married">Married</option>
              <option value="Widowed">Widowed</option>
              <option value="Divorced">Divorced</option>
              <option value="Separated">Separated</option>
              <option value="Cohabiting">Live-in / Cohabiting</option>
            </select>
            <p id="civilstatus-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <!-- Gender -->
          <div>
            <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Gender <span class="text-gray-500 text-xs ml-1">(required)</span>
            </label>
            <select name="gender" id="gender"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
              <option value="" disabled selected>Select gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
            <p id="gender-error" class="mt-1 text-sm text-red-600 hidden"></p>
          </div>

          <!-- Address -->
          <div class="sm:col-span-2">
            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Complete Address
            </label>
            <textarea name="address" id="address" rows="3"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-gray-900 transition-colors resize-none"
              placeholder="Brgy, Lukay Babatngon, Leyte"></textarea>
          </div>
        </div>

        <!-- Submit button -->
        <div class="flex justify-end pt-4">
          <button type="button" class="px-3 py-2 mr-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors" data-modal-toggle="add-member-modal">
            Cancel
          </button>
          <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New Member
          </button>
        </div>
      </form>
    </div>
  </div>
</div>