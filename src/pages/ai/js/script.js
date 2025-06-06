function toggleChat() {
  const chatContainer = document.getElementById('chat-container');
  const chatToggle = document.getElementById('chat-toggle');
  chatContainer.classList.toggle('hidden');
  chatToggle.classList.toggle('hidden');
}
document.getElementById('chat-toggle').addEventListener('click', toggleChat);

function selectSuggestion(text) {
  document.getElementById('user-input').value = text;
  sendMessage();
}


document.addEventListener('DOMContentLoaded', () => {
  const userInput = document.getElementById('user-input');
  userInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
          e.preventDefault(); // Prevent default form submission
          sendMessage();
      }
  });
  // New: Variables to store user's location
  window.userLatitude = null;
  window.userLongitude = null;
  getUserLocation();
});

// New: Function to get user's location using Geolocation API
function getUserLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        window.userLatitude = position.coords.latitude;
        window.userLongitude = position.coords.longitude;
        console.log(`User location obtained: Lat ${window.userLatitude}, Lng ${window.userLongitude}`);
        // Optional: You could display a message in the chat that location was obtained
        // const chatBox = document.getElementById('chat-box');
        // const infoMessage = document.createElement('div');
        // infoMessage.className = 'bot-message info-message'; // Add a distinct class for info messages
        // infoMessage.textContent = 'Location detected. I can provide more relevant recommendations!';
        // chatBox.appendChild(infoMessage);
        // chatBox.scrollTop = chatBox.scrollHeight;
      },
      (error) => {
        console.error('Error getting location:', error);
        const chatBox = document.getElementById('chat-box');
        const errorMessage = document.createElement('div');
        errorMessage.className = 'bot-message error-message'; // Add a distinct class for error messages
        if (error.code === error.PERMISSION_DENIED) {
          errorMessage.textContent = 'Location access denied by user. Recommendations might not be location-specific.';
        } else {
          errorMessage.textContent = 'Could not get your location. Recommendations might not be location-specific.';
        }
        chatBox.appendChild(errorMessage);
        chatBox.scrollTop = chatBox.scrollHeight;
      },
      {
        enableHighAccuracy: true, // Request a more precise location
        timeout: 10000,          // Maximum time (ms) to wait for a location
        maximumAge: 60000        // Max age (ms) of a cached location to use
      }
    );
  } else {
      console.warn('Geolocation is not supported by this browser.');
      const chatBox = document.getElementById('chat-box');
      const errorMessage = document.createElement('div');
      errorMessage.className = 'bot-message error-message';
      errorMessage.textContent = 'Geolocation is not supported by your browser. Recommendations might not be location-specific.';
      chatBox.appendChild(errorMessage);
      chatBox.scrollTop = chatBox.scrollHeight;
  }
}

function sendMessage() {
  const userInputElement = document.getElementById('user-input');
  const userInput = userInputElement.value.trim();
  const messageListWrapper = document.getElementById('chat-box');
  
  if (userInput === "") return;

  // Clear any preview placeholder
  if (messageListWrapper.textContent.trim() === 'Preview result...') {
    messageListWrapper.innerHTML = '';
  }
  
  // Append user message
  const userMessage = document.createElement('div');
  userMessage.className = 'flex justify-end';
  userMessage.innerHTML =`<div class="bg-blue-100 text-blue-900 text-start rounded-lg px-2 py-2 max-w-[70%]">${userInput}</div>`;
  //userMessage.textContent = userInput;
  messageListWrapper.appendChild(userMessage);
  
  // Build request body
  const requestBody = { message: userInput };
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
  messageListWrapper.appendChild(loadingMessage);

  // Send request to backend
  fetch("/booking-sys/src/pages/ai/backend/chatbot.php", {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(requestBody)
  }).then(response => response.json())
    .then(data => {
      loadingMessage.remove();
      
      const botMessage = document.createElement('div');
      botMessage.className = 'flex items-start';
      botMessage.innerHTML = data.error
        ? `<div class="bg-orange-100 text-orange-900 p-2 rounded-lg px-4 py-2 max-w-[70%]">Smart: ${data.error}</div>`
        : `<div class="bg-orange-100 text-orange-900 p-2 rounded-lg px-4 py-2 max-w-[70%]">Smart: ${data.response}</div>`;
      messageListWrapper.appendChild(botMessage);
      userInputElement.value = '';
    }).catch(error => {
      loadingMessage.remove();
      
      const errorMessage = document.createElement('div');
      errorMessage.className = 'bot-message error-message bg-red-100 text-red-900 rounded-lg px-4 py-2 max-w-xs self-start';
      errorMessage.textContent = 'Bot: Failed to fetch response. Please check your network connection.';
      messageListWrapper.appendChild(errorMessage);
    });
}

