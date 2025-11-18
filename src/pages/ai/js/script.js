function toggleChat() {
  const chatContainer = document.getElementById('chat-container');
  const chatToggle = document.getElementById('chat-toggle');
  chatContainer.classList.toggle('hidden');
  chatToggle.classList.toggle('hidden');
}

document.getElementById('chat-toggle').addEventListener('click', toggleChat);

// Enhanced session management with error handling
class ChatSessionManager {
  constructor() {
    this.currentSessionId = null;
    this.isProcessing = false;
    this.userLocation = { latitude: null, longitude: null };
  }

  generateSessionId() {
    return 'chat_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
  }

  initializeSession() {
    try {
      this.currentSessionId = localStorage.getItem('chat_session_id') || this.generateSessionId();
      localStorage.setItem('chat_session_id', this.currentSessionId);
      return this.currentSessionId;
    } catch (error) {
      console.error('Session initialization failed:', error);
      this.currentSessionId = this.generateSessionId();
      return this.currentSessionId;
    }
  }

  clearSession() {
    try {
      this.currentSessionId = this.generateSessionId();
      localStorage.setItem('chat_session_id', this.currentSessionId);
      return this.currentSessionId;
    } catch (error) {
      console.error('Session clearance failed:', error);
      return this.currentSessionId;
    }
  }
}

// Enhanced location service
class LocationService {
  static async getUserLocation() {
    return new Promise((resolve, reject) => {
      if (!navigator.geolocation) {
        reject(new Error('Geolocation is not supported'));
        return;
      }

      navigator.geolocation.getCurrentPosition(
        (position) => resolve({
          latitude: position.coords.latitude,
          longitude: position.coords.longitude
        }),
        (error) => reject(error),
        {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 60000
        }
      );
    });
  }
}

// Enhanced chat UI manager
class ChatUIManager {
  constructor() {
    this.chatBox = document.getElementById('chat-box');
    this.userInput = document.getElementById('user-input');
    this.sendButton = document.getElementById('send-button');
  }

  addMessage(content, isUser = false, messageType = 'normal') {
    const messageDiv = document.createElement('div');
    const messageClass = isUser 
      ? 'flex justify-end' 
      : 'flex items-start';

    let bgColor, textColor, borderClass = '';
    
    switch (messageType) {
      case 'error':
        bgColor = 'bg-red-100';
        textColor = 'text-red-900';
        borderClass = 'border-l-4 border-red-500';
        break;
      case 'success':
        bgColor = 'bg-green-100';
        textColor = 'text-green-900';
        borderClass = 'border-l-4 border-green-500';
        break;
      case 'warning':
        bgColor = 'bg-orange-100';
        textColor = 'text-orange-900';
        borderClass = 'border-l-4 border-orange-500';
        break;
      default:
        bgColor = isUser ? 'bg-blue-100' : 'bg-orange-100';
        textColor = isUser ? 'text-blue-900' : 'text-orange-900';
    }

    messageDiv.className = messageClass;
    messageDiv.innerHTML = `
      <div class="${bgColor} ${textColor} ${borderClass} text-start rounded-lg px-4 py-2 max-w-[70%] break-words">
        ${isUser ? content : `Smart: ${content}`}
      </div>
    `;

    this.chatBox.appendChild(messageDiv);
    this.scrollToBottom();
  }

  showLoadingMessage() {
    const loadingDiv = document.createElement('div');
    loadingDiv.id = 'loading-message';
    loadingDiv.className = 'flex items-start';
    loadingDiv.innerHTML = `
      <div class="bg-gray-100 text-gray-700 p-2 rounded-lg max-w-[70%] flex items-center">
        <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24" style="color: #ff6b00;">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Smart: Processing your request...
      </div>
    `;
    this.chatBox.appendChild(loadingDiv);
    this.scrollToBottom();
  }

  removeLoadingMessage() {
    const loadingMessage = document.getElementById('loading-message');
    if (loadingMessage) {
      loadingMessage.remove();
    }
  }

  scrollToBottom() {
    this.chatBox.scrollTop = this.chatBox.scrollHeight;
  }

  clearChat() {
    this.chatBox.innerHTML = '';
    this.addWelcomeMessage();
  }

  addWelcomeMessage() {
    this.addMessage('Hello! I\'m your travel assistant. How can I help you with hotels and reservations today?', false, 'normal');
  }

  setInputState(disabled) {
    this.userInput.disabled = disabled;
    this.sendButton.disabled = disabled;
    
    if (!disabled) {
      this.userInput.focus();
    }
  }

  // New method to handle suggestion selection
  setInputValue(text) {
    this.userInput.value = text;
    this.userInput.focus();
  }
}

// Main chat controller
class ChatController {
  constructor() {
    this.sessionManager = new ChatSessionManager();
    this.uiManager = new ChatUIManager();
    this.isProcessing = false;
  }

  async initialize() {
    // Initialize session
    this.sessionManager.initializeSession();

    // Set up event listeners
    this.setupEventListeners();

    // Get user location with better error handling
    await this.initializeUserLocation();

    // Add welcome message
    this.uiManager.addWelcomeMessage();
  }

  setupEventListeners() {
    const userInput = this.uiManager.userInput;
    const sendButton = this.uiManager.sendButton;
    
    // Enter key to send
    userInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        this.sendMessage();
      }
    });

    // Send button click
    sendButton.addEventListener('click', () => {
      this.sendMessage();
    });

    // Add input validation
    userInput.addEventListener('input', (e) => {
      this.validateInput(e.target.value);
    });
  }

  validateInput(input) {
    // Basic input sanitization
    const sanitized = input.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
    if (sanitized !== input) {
      this.uiManager.addMessage('Invalid input detected. Please avoid script tags.', false, 'warning');
    }
  }

  async initializeUserLocation() {
    try {
      const location = await LocationService.getUserLocation();
      window.userLatitude = location.latitude;
      window.userLongitude = location.longitude;
      console.log(`User location obtained: Lat ${location.latitude}, Lng ${location.longitude}`);
    } catch (error) {
      console.warn('Location access failed:', error);
      let errorMessage = 'Could not get your location. ';
      
      if (error.code === error.PERMISSION_DENIED) {
        errorMessage += 'Location access was denied. ';
      }
      
      errorMessage += 'Recommendations might not be location-specific.';
      this.uiManager.addMessage(errorMessage, false, 'warning');
    }
  }

  async sendMessage() {
    if (this.isProcessing) {
      return;
    }

    const userInput = this.uiManager.userInput.value.trim();
    if (!userInput) {
      return;
    }

    // Clear preview placeholder if present
    if (this.uiManager.chatBox.textContent.trim() === 'Preview result...') {
      this.uiManager.chatBox.innerHTML = '';
    }

    // Add user message
    this.uiManager.addMessage(userInput, true);

    // Show loading
    this.uiManager.showLoadingMessage();
    this.isProcessing = true;
    this.uiManager.setInputState(true);

    try {
      const response = await this.makeApiRequest(userInput);
      this.handleApiResponse(response);
    } catch (error) {
      this.handleError(error);
    } finally {
      this.cleanup();
    }
  }

  async makeApiRequest(userInput) {
    const requestBody = {
      message: userInput,
      session_id: this.sessionManager.currentSessionId
    };

    if (window.userLatitude && window.userLongitude) {
      requestBody.latitude = window.userLatitude;
      requestBody.longitude = window.userLongitude;
    }

    const response = await fetch("/booking-sys/src/pages/ai/backend/chatbot.php", {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify(requestBody)
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    return await response.json();
  }

  handleApiResponse(data) {
    this.uiManager.removeLoadingMessage();

    if (data.error) {
      this.uiManager.addMessage(data.error, false, 'error');
      return;
    }

    const messageType = data.reservation_in_progress || data.reservation_matched ? 'success' : 'normal';
    this.uiManager.addMessage(data.response, false, messageType);
    
    // Clear input on successful processing
    this.uiManager.userInput.value = '';
  }

  handleError(error) {
    this.uiManager.removeLoadingMessage();
    
    console.error('Chat error:', error);
    
    let errorMessage = 'Failed to get response. ';
    if (error.message.includes('HTTP error')) {
      errorMessage += 'Please check your network connection.';
    } else {
      errorMessage += 'Please try again in a moment.';
    }
    
    this.uiManager.addMessage(errorMessage, false, 'error');
  }

  cleanup() {
    this.isProcessing = false;
    this.uiManager.setInputState(false);
    this.uiManager.removeLoadingMessage();
  }

  clearChat() {
    this.sessionManager.clearSession();
    this.uiManager.clearChat();
  }

  // Method for suggestion selection
  selectSuggestion(text) {
    this.uiManager.setInputValue(text);
    this.sendMessage();
  }
}

// Global functions for HTML compatibility
function sendMessage() {
  if (window.chatController) {
    window.chatController.sendMessage();
  }
}

function clearChat() {
  if (window.chatController) {
    window.chatController.clearChat();
  }
}

function selectSuggestion(text) {
  if (window.chatController) {
    window.chatController.selectSuggestion(text);
  }
}

function toggleChat() {
  const chatContainer = document.getElementById('chat-container');
  const chatToggle = document.getElementById('chat-toggle');
  if (chatContainer && chatToggle) {
    chatContainer.classList.toggle('hidden');
    chatToggle.classList.toggle('hidden');
  }
}

// Initialize chat when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  window.chatController = new ChatController();
  window.chatController.initialize();
  
  // Add event listener for chat toggle if it exists
  const chatToggle = document.getElementById('chat-toggle');
  if (chatToggle) {
    chatToggle.addEventListener('click', toggleChat);
  }
});