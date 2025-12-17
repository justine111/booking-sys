<div id="register-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
  class="hidden fixed inset-0 z-50 items-center justify-center w-full h-full bg-gray-900/70 backdrop-blur-sm">

  <div class="relative w-full max-w-md p-0 rounded-xl shadow-2xl overflow-hidden">
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 p-6 text-white">
      <div class="flex justify-between items-start">
        <div>
          <h3 class="text-xl font-semibold">Join StaySmart</h3>
          <p class="text-blue-100 text-sm">Create your host account</p>
        </div>
        <!-- Close Button -->
        <button type="button"
          class="text-white/80 hover:text-white transition-colors duration-200 p-1 rounded-lg hover:bg-white/10"
          data-modal-hide="register-modal" aria-label="Close modal">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Form Content -->
    <div class="p-6 bg-white">
      <form class="space-y-5" action="#" method="post" id="register-form">
        <!-- Error Message -->
        <div id="register-form-error" class="hidden p-4 rounded-lg bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800">
          <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
              <div class="w-5 h-5 bg-red-100 rounded-full flex items-center justify-center dark:bg-red-800/30">
                <svg class="w-3 h-3 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
            <div class="flex-1">
              <h3 class="text-sm font-semibold text-red-800 dark:text-red-200">Registration failed</h3>
              <p id="register-error-message" class="text-red-700 dark:text-red-300 text-sm mt-1"></p>
            </div>
          </div>
        </div>

        <!-- Name -->
        <div>
          <label for="reg-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
          <div class="relative">
            <input type="text" name="name" id="reg-name" placeholder="John Doe" required
              class="w-full px-4 py-3 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg 
              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 
              dark:text-white dark:placeholder-gray-400 dark:focus:ring-blue-400 transition-colors duration-200" />
          </div>
        </div>

        <!-- Email -->
        <div>
          <label for="reg-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email address</label>
          <div class="relative">
            <input type="email" name="email" id="reg-email" placeholder="name@company.com" required
              class="w-full px-4 py-3 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg 
              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 
              dark:text-white dark:placeholder-gray-400 dark:focus:ring-blue-400 transition-colors duration-200" />
          </div>
        </div>

        <!-- Phone -->
        <div>
          <label for="reg-phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
          <div class="relative">
            <input type="tel" name="phone" id="reg-phone" placeholder="09123456789"
              class="w-full px-4 py-3 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg 
              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 
              dark:text-white dark:placeholder-gray-400 dark:focus:ring-blue-400 transition-colors duration-200" />
          </div>
        </div>

        <!-- Password -->
        <div>
          <label for="reg-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
          <div class="relative">
            <input type="password" name="password" id="reg-password" placeholder="••••••••" required
              class="w-full px-4 py-3 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg 
              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 
              dark:text-white dark:placeholder-gray-400 dark:focus:ring-blue-400 transition-colors duration-200" />
          </div>
        </div>

        <!-- User Type (Hidden or Select) -->
        <!-- For now, we default to Host (3) but we can show it if needed. 
             Since the request is "registration for host", we'll make it explicit or hidden.
             Let's add a hidden input for now, or a disabled select to show they are registering as Host. -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Account Type</label>
          <select name="user_type" class="w-full px-4 py-3 text-sm text-gray-900 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed" readonly>
            <option value="3" selected>Host</option>
          </select>
        </div>

        <!-- Submit Button -->
        <button type="submit"
          class="w-full py-3 px-4 text-sm font-medium text-white bg-gradient-to-r from-orange-600 to-orange-700 
          rounded-lg hover:from-orange-700 hover:to-orange-800 focus:outline-none focus:ring-2 
          focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-all 
          duration-200">
          Create Host Account
        </button>

        <!-- Footer Links -->
        <div class="text-center pt-4 border-t border-gray-200 dark:border-gray-700">
          <p class="text-xs text-gray-500 dark:text-gray-400">
            Already have an account? 
            <a href="#" data-modal-hide="register-modal" data-modal-target="static-modal" data-modal-toggle="static-modal" 
               class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium transition-colors duration-200">
              Sign in
            </a>
          </p>
        </div>
      </form>
    </div>
  </div>
</div>
