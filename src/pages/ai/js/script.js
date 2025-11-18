// Toggle chat window visibility
function toggleChat() {
  const chatContainer = document.getElementById('chat-container');
  const chatToggle = document.getElementById('chat-toggle');
  chatContainer.classList.toggle('hidden');
  chatToggle.classList.toggle('hidden');
}

document.getElementById('chat-toggle').addEventListener('click', toggleChat);

// Insert suggestion into input and send message
function selectSuggestion(text) {
  document.getElementById('user-input').value = text;
  sendMessage();
}

// Global variables for session management
let currentSessionId = null;
let isProcessingReservation = false;

document.addEventListener('DOMContentLoaded', () => {
  const userInput = document.getElementById('user-input');
  userInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
          e.preventDefault();
          sendMessage();
      }
  });
  
  // Initialize user location and session
  window.userLatitude = null;
  window.userLongitude = null;
  getUserLocation();
  
  // Generate or retrieve session ID
  currentSessionId = localStorage.getItem('chat_session_id') || generateSessionId();
  localStorage.setItem('chat_session_id', currentSessionId);
});

// Generate unique session ID
function generateSessionId() {
  return 'chat_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
}

// Get user location
function getUserLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        window.userLatitude = position.coords.latitude;
        window.userLongitude = position.coords.longitude;
        console.log(`User location obtained: Lat ${window.userLatitude}, Lng ${window.userLongitude}`);
      },
      (error) => {
        console.error('Error getting location:', error);
        const chatBox = document.getElementById('chat-box');
        const errorMessage = document.createElement('div');
        errorMessage.className = 'bot-message error-message';
        if (error.code === error.PERMISSION_DENIED) {
          errorMessage.textContent = 'Location access denied. Recommendations might not be location-specific.';
        } else {
          errorMessage.textContent = 'Could not get your location. Recommendations might not be location-specific.';
        }
        chatBox.appendChild(errorMessage);
        chatBox.scrollTop = chatBox.scrollHeight;
      },
      {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 60000
      }
    );
  } else {
      console.warn('Geolocation is not supported by this browser.');
      const chatBox = document.getElementById('chat-box');
      const errorMessage = document.createElement('div');
      errorMessage.className = 'bot-message error-message';
      errorMessage.textContent = 'Geolocation not supported. Recommendations might not be location-specific.';
      chatBox.appendChild(errorMessage);
      chatBox.scrollTop = chatBox.scrollHeight;
  }

  navigator.geolocation.getCurrentPosition(
    (position) => {
      window.userLatitude = position.coords.latitude;
      window.userLongitude = position.coords.longitude;
      console.log(`User location obtained: Lat ${window.userLatitude}, Lng ${window.userLongitude}`);
    },
    (error) => {
      let msg = '';
      if (error.code === error.PERMISSION_DENIED) {
        msg = 'Location access denied by user. Recommendations might not be location-specific.';
      } else {
        msg = 'Could not get your location. Recommendations might not be location-specific.';
      }
      showBotMessage(msg, 'error');
    },
    {
      enableHighAccuracy: true,
      timeout: 10000,
      maximumAge: 60000
    }
  );
}

// Append bot messages to chat box
function showBotMessage(message, type = 'info') {
  const chatBox = document.getElementById('chat-box');
  const botMessage = document.createElement('div');
  botMessage.className = 'flex items-start';
  botMessage.innerHTML = `<div class="bg-${type === 'error' ? 'red' : 'orange'}-100 text-${type === 'error' ? 'red' : 'orange'}-900 p-2 rounded-lg px-4 py-2 max-w-[70%]">Smart: ${message}</div>`;
  chatBox.appendChild(botMessage);
  chatBox.scrollTop = chatBox.scrollHeight;
}

// Send user message to backend
function sendMessage() {
  if (isProcessingReservation) {
    return; // Prevent multiple simultaneous requests during reservation flow
  }

  const userInputElement = document.getElementById('user-input');
  const userInput = userInputElement.value.trim();
  if (!userInput) return;

  const chatBox = document.getElementById('chat-box');

  // Display user message
  const userMessage = document.createElement('div');
  userMessage.className = 'flex justify-end';
  userMessage.innerHTML = `<div class="bg-blue-100 text-blue-900 text-start rounded-lg px-2 py-2 max-w-[70%]">${userInput}</div>`;
  messageListWrapper.appendChild(userMessage);
  
  // Build request body with session ID
  const requestBody = { 
    message: userInput,
    session_id: currentSessionId
  };
  
  if (window.userLatitude !== null && window.userLongitude !== null) {
    requestBody.latitude = window.userLatitude;
    requestBody.longitude = window.userLongitude;
  }
  
  // Show loading message
  const loadingMessage = document.createElement('div');
  loadingMessage.className = 'flex items-start';
  loadingMessage.innerHTML = `
    <div class="bg-gray-100 text-gray-700 p-2 rounded-lg max-w-[70%] flex items-center">
      <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24" style="color: #ff6b00; stroke: black;">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="#ff6b00" stroke-width="4" fill="none"></circle>
        <path class="opacity-75" fill="#ff6b00" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      Smart: Processing...
    </div>
  `;
  chatBox.appendChild(loadingMessage);
  chatBox.scrollTop = chatBox.scrollHeight;

  // Prepare request payload
  const requestBody = { message: userInput };
  if (window.userLatitude !== null && window.userLongitude !== null) {
    requestBody.latitude = window.userLatitude;
    requestBody.longitude = window.userLongitude;
  }

  // Disable input during processing
  isProcessingReservation = true;
  userInputElement.disabled = true;
  
  // Send request to backend
  fetch("/booking-sys/src/pages/ai/backend/chatbot.php", {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(requestBody)
  })
  .then(response => response.json())
  .then(data => {
    loadingMessage.remove();
    
    const botMessage = document.createElement('div');
    botMessage.className = 'flex items-start';
    
    if (data.error) {
      botMessage.innerHTML = `<div class="bg-orange-100 text-orange-900 p-2 rounded-lg px-4 py-2 max-w-[70%]">Smart: ${data.error}</div>`;
    } else {
      // Check if this is part of a reservation flow
      if (data.reservation_in_progress) {
        botMessage.innerHTML = `<div class="bg-green-100 text-green-900 p-2 rounded-lg px-4 py-2 max-w-[70%] border-l-4 border-green-500">Smart: ${data.response}</div>`;
      } else if (data.reservation_matched) {
        botMessage.innerHTML = `<div class="bg-green-100 text-green-900 p-2 rounded-lg px-4 py-2 max-w-[70%] border-l-4 border-green-500">Smart: ${data.response}</div>`;
      } else {
        botMessage.innerHTML = `<div class="bg-orange-100 text-orange-900 p-2 rounded-lg px-4 py-2 max-w-[70%]">Smart: ${data.response}</div>`;
      }
    }
    
    messageListWrapper.appendChild(botMessage);
    userInputElement.value = '';
    
    // Scroll to bottom
    messageListWrapper.scrollTop = messageListWrapper.scrollHeight;
    
  })
  .catch(error => {
    loadingMessage.remove();
    
    const errorMessage = document.createElement('div');
    errorMessage.className = 'flex items-start';
    errorMessage.innerHTML = `<div class="bg-red-100 text-red-900 p-2 rounded-lg px-4 py-2 max-w-[70%]">Smart: Failed to fetch response. Please check your network connection.</div>`;
    messageListWrapper.appendChild(errorMessage);
    
    // Scroll to bottom
    messageListWrapper.scrollTop = messageListWrapper.scrollHeight;
    
    console.error('Fetch error:', error);
  })
  .finally(() => {
    // Re-enable input
    isProcessingReservation = false;
    userInputElement.disabled = false;
    userInputElement.focus();
  });
}

// Function to clear chat history and start fresh
function clearChat() {
  const messageListWrapper = document.getElementById('chat-box');
  messageListWrapper.innerHTML = '';
  
  // Generate new session ID
  currentSessionId = generateSessionId();
  localStorage.setItem('chat_session_id', currentSessionId);
  
  // Add welcome message
  const welcomeMessage = document.createElement('div');
  welcomeMessage.className = 'flex items-start';
  welcomeMessage.innerHTML = `<div class="bg-orange-100 text-orange-900 p-2 rounded-lg px-4 py-2 max-w-[70%]">Smart: Hello! I'm your travel assistant. How can I help you with hotels and reservations today?</div>`;
  messageListWrapper.appendChild(welcomeMessage);
}

// Add clear chat button to your HTML and call this function
// <button onclick="clearChat()" class="bg-red-500 text-white px-3 py-1 rounded">Clear Chat</button>
