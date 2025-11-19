<section style="position: fixed; bottom: 1rem; right: 1rem; z-index: 50; font-family: Geist">
  <!-- Enhanced Toggle Button -->
  <button
    id="chat-toggle"
    class="bg-gray-300 text-white font-semibold rounded-full p-3 shadow-lg mb-2 transition-all duration-300 ease-in-out hover:scale-110 hover:shadow-xl border-2 border-white">
    <img src="/booking-sys/src/assets/img/smart.gif" class="w-12 h-12 rounded-full">
    <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white animate-pulse"></div>
  </button>

  <!-- Enhanced Chat Container -->
  <div id="chat-container" class="hidden animate-fade-in-up">
    <div class="w-96 bg-white shadow-2xl border border-gray-200 rounded-xl overflow-hidden">
      <!-- Enhanced Header -->
      <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-4 rounded-t-lg border-b border-orange-400 flex justify-between items-center">
        <div class="flex items-center space-x-3">
          <img src="/booking-sys/src/assets/img/smart.gif" class="w-10 h-10 rounded-full border-2 border-white">
          <div>
            <h3 class="font-semibold text-white">Smart Assistant</h3>
            <div class="flex items-center space-x-1">
              <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
              <span class="text-xs text-orange-100">Online</span>
            </div>
          </div>
        </div>
        <div class="flex items-center space-x-2">
          <button onclick="clearChat()" class="p-1 hover:bg-orange-400 rounded transition-colors" title="Clear chat">
            <i data-lucide="rotate-ccw" class="w-[18px]"></i>
          </button>
          <button onclick="toggleChat()" class="p-1 hover:bg-orange-400 rounded transition-colors" title="Close chat">
            <i data-lucide="x" class="w-[18px]"></i>
        </div>
      </div>

      <!-- Enhanced Chat Messages Area -->
      <div id="chat-box" class="flex-1 p-4 h-80 overflow-y-auto space-y-3 scroll-smooth text-sm bg-gradient-to-b from-gray-50 to-white">
        <!-- Welcome message will be inserted here -->
      </div>

      <!-- Quick Suggestions -->
      <div id="suggestions-container" class="px-4 py-2 bg-gray-50 border-t border-gray-200">
        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
          <button
            onclick="selectSuggestion('Show available hotels')"
            class="flex items-center px-3 py-2 bg-white text-xs text-gray-700 border border-gray-300 rounded-full hover:bg-orange-50 hover:border-orange-300 transition-colors duration-200 whitespace-nowrap">
            <i data-lucide="house" class="w-[16px] mr-1"></i> Show hotels
          </button>

          <button
            onclick="selectSuggestion('make reservation')"
            class="flex items-center px-3 py-2 bg-white text-xs text-gray-700 border border-gray-300 rounded-full hover:bg-orange-50 hover:border-orange-300 transition-colors duration-200 whitespace-nowrap">
            <i data-lucide="stamp" class="w-[16px] mr-1"></i> Make a Reservation
          </button>

          <button
            onclick="selectSuggestion('Budget hotels under ₱3000')"
            class="flex items-center px-3 py-2 bg-white text-xs text-gray-700 border border-gray-300 rounded-full hover:bg-orange-50 hover:border-orange-300 transition-colors duration-200 whitespace-nowrap">
            <i data-lucide="philippine-peso" class="w-[16px] mr-1"></i> Budget options
          </button>

          <button
            onclick="selectSuggestion('What can you help me with?')"
            class="flex items-center px-3 py-2 bg-white text-xs text-gray-700 border border-gray-300 rounded-full hover:bg-orange-50 hover:border-orange-300 transition-colors duration-200 whitespace-nowrap">
            <i data-lucide="message-circle-question-mark" class="w-[16px] mr-1"></i> Help
          </button>
        </div>
      </div>

      <!-- Enhanced Chat Input -->
      <div class="p-3 border-t border-gray-200 bg-white">
        <div class="relative flex items-center space-x-2">
          <input
            type="text"
            id="user-input"
            class="flex-1 p-3 text-sm text-gray-900 border border-gray-300 rounded-xl bg-gray-50 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 placeholder-gray-500"
            placeholder="Ask Smart about hotels and bookings..."
            maxlength="500">
          <button
            id="send-button"
            class="px-4 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[60px]"
            title="Send message">
            <svg id="send-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
            <svg id="loading-icon" class="w-4 h-4 hidden animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 12v4m8-10h-4M6 12H2"></path>
            </svg>
          </button>
        </div>
        <div class="flex justify-between items-center mt-2 px-1">
          <span class="text-xs text-gray-500">Press Enter to send</span>
          <span id="char-count" class="text-xs text-gray-500">0/500</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Enhanced Styles -->
<style>
  /* Custom animations */
  @keyframes fade-in-up {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .animate-fade-in-up {
    animation: fade-in-up 0.3s ease-out;
  }

  /* Custom scrollbar */
  #chat-box::-webkit-scrollbar {
    width: 6px;
  }

  #chat-box::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
  }

  #chat-box::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
  }

  #chat-box::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
  }

  /* Hide scrollbar for suggestions but keep functionality */
  .scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }

  .scrollbar-hide::-webkit-scrollbar {
    display: none;
  }

  /* Message animations */
  .message-fade-in {
    animation: message-fade-in 0.3s ease-out;
  }

  @keyframes message-fade-in {
    from {
      opacity: 0;
      transform: translateY(8px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Smooth transitions */
  .transition-all {
    transition: all 0.3s ease;
  }
</style>

<script>
  // Enhanced suggestion selection
  function selectSuggestion(text) {
    const userInput = document.getElementById('user-input');
    userInput.value = text;
    userInput.focus();
    // Auto-send after short delay
    setTimeout(() => {
      if (window.chatController) {
        window.chatController.sendMessage();
      }
    }, 100);
  }

  // Initialize character counter
  document.addEventListener('DOMContentLoaded', function() {
    const userInput = document.getElementById('user-input');
    const charCount = document.getElementById('char-count');

    if (userInput && charCount) {
      userInput.addEventListener('input', function() {
        charCount.textContent = `${this.value.length}/500`;

        // Update color when approaching limit
        if (this.value.length > 450) {
          charCount.classList.add('text-red-500');
          charCount.classList.remove('text-gray-500');
        } else {
          charCount.classList.remove('text-red-500');
          charCount.classList.add('text-gray-500');
        }
      });
    }

    // Add welcome message when chat opens
    const chatToggle = document.getElementById('chat-toggle');
    if (chatToggle) {
      chatToggle.addEventListener('click', function() {
        setTimeout(() => {
          const chatBox = document.getElementById('chat-box');
          if (chatBox && chatBox.children.length === 0) {
            if (window.chatController) {
              window.chatController.uiManager.addWelcomeMessage();
            }
          }
        }, 100);
      });
    }
  });

  // Enhanced toggle function
  function toggleChat() {
    const chatContainer = document.getElementById('chat-container');
    const chatToggle = document.getElementById('chat-toggle');

    if (chatContainer && chatToggle) {
      chatContainer.classList.toggle('hidden');

      // Add animation class when showing
      if (!chatContainer.classList.contains('hidden')) {
        chatContainer.classList.add('animate-fade-in-up');
      }
    }
  }

  // Enhanced clear chat function
  function clearChat() {
    if (window.chatController) {
      window.chatController.clearChat();
    }
  }

  // Initialize icons
  lucide.createIcons();
</script>

<script src="/booking-sys/src/pages/ai/js/script.js"></script>