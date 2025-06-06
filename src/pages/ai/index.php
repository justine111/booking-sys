<section style="position: fixed; bottom: 1rem; right: 1rem; z-index: 50; font-family: Space Grotesk">
  <button id="chat-toggle" class="bg-gray-200 hover:bg-gray-400 text-white font-semibold rounded-full p-2 border shadow-lg mb-2">
    <img src="/booking-sys/src/assets/img/smart.gif" class="w-120 h-12">
  </button>

  <div id="chat-container" class="hidden">
    <div class="w-96 bg-white shadow-xl border rounded-lg">
      <div class="text-gray-800 p-2 rounded-t-lg border-b flex justify-between items-center">
        <h3 class="flex font-medium items-center text-orange-400">
          <img src="/booking-sys/src/assets/img/smart.gif" class="w-8 h-8">
          Smart Assistant
        </h3>
        <button onclick="toggleChat()" class="hover:text-gray-400">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <div id="chat-box" class="flex-1 p-4 h-80 overflow-y-auto space-y-2 scroll-smooth text-sm"></div>
      <!-- <div class="flex flex-col gap-2 mt-4">
        <div onclick="selectSuggestion('What is artificial intelligence?')" class="cursor-pointer p-4 bg-white shadow-md rounded-lg hover:bg-gray-50 inline-block">
          <p class="text-sm text-gray-600">What is artificial?</p>
        </div>
        <div onclick="selectSuggestion('How does machine learning work?')" class="cursor-pointer p-4 bg-white shadow-md rounded-lg hover:bg-gray-50">
          <p class="text-sm text-gray-600">How does machine learning work?</p>
        </div>
        <div onclick="selectSuggestion('Explain neural networks')" class="cursor-pointer p-4 bg-white shadow-md rounded-lg hover:bg-gray-50">
          <p class="text-sm text-gray-600">Explain neural networks</p>
        </div>
      </div> -->

      <!-- Chat Input -->
      <div class="p-2 border-t">
        <div class="relative flex">
          <input
            type="text"
            id="user-input"
            class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50"
            placeholder="Ask Smart">
          <!-- <button onclick="sendMessage()" class="ml-2 px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
            Send
          </button> -->
        </div>
      </div>
    </div>
  </div>
</section>
<script src="/booking-sys/src/pages/ai/js/script.js"></script>


<!-- Suggestion Cards -->
<!-- <div class="flex gap-4 mt-4">
        <div onclick="selectSuggestion('What is artificial intelligence?')" class="cursor-pointer p-4 bg-white shadow-md rounded-lg hover:bg-gray-50">
          <p class="text-sm text-gray-600">What is artificial intelligence?</p>
        </div>
        <div onclick="selectSuggestion('How does machine learning work?')" class="cursor-pointer p-4 bg-white shadow-md rounded-lg hover:bg-gray-50">
          <p class="text-sm text-gray-600">How does machine learning work?</p>
        </div>
        <div onclick="selectSuggestion('Explain neural networks')" class="cursor-pointer p-4 bg-white shadow-md rounded-lg hover:bg-gray-50">
          <p class="text-sm text-gray-600">Explain neural networks</p>
        </div>
      </div> -->
<!-- <script>
    function selectSuggestion(text) {
      document.getElementById('user-input').value = text;
      sendMessage();
    }
  </script> -->