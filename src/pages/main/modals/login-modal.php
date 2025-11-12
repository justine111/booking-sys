<!-- Modal Background -->
<div id="static-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" 
  class="hidden fixed inset-0 z-50 items-center justify-center w-full h-full bg-black/50 backdrop-blur-sm">

  <!-- Modal Container -->
  <div class="relative w-full max-w-sm p-6 bg-white border border-gray-200 rounded-2xl shadow-lg dark:bg-gray-800 dark:border-gray-700">
    
    <!-- Close Button -->
    <button type="button" 
      class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200" 
      data-modal-hide="static-modal" aria-label="Close modal">
      ✕
    </button>

    <!-- Form -->
    <form class="space-y-6" action="#">
      <h5 class="text-xl font-semibold text-gray-900 dark:text-white text-center">Sign in to your account</h5>

      <!-- Role Selection -->
      <div>
        <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Login as</label>
        <select id="role" name="role" 
          class="w-full p-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg 
          focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 
          dark:placeholder-gray-400 dark:text-white">
          <option value="" disabled selected>Select your role</option>
          <option value="1">Admin</option>
          <option value="2">Moderator</option>
        </select>
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
        <input type="email" name="email" id="email" 
          class="w-full p-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg 
          focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 
          dark:placeholder-gray-400 dark:text-white" 
          placeholder="name@company.com" required />
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your password</label>
        <input type="password" name="password" id="password" placeholder="••••••••" 
          class="w-full p-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg 
          focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 
          dark:placeholder-gray-400 dark:text-white" required />
      </div>

      <!-- Submit -->
      <button type="submit" 
        class="w-full py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 
        focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 
        dark:focus:ring-blue-800">
        Login
      </button>
    </form>
  </div>
</div>
