<?php
header('Content-Type: application/json');

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Environment-based configuration
class Config
{
  const API_KEY = 'AIzaSyAVTtWzjt2vP3pfDkNoabV3Dr7txtwlqRM';
  const SESSIONS_DIR = __DIR__ . '/sessions';
  const MAX_MESSAGE_LENGTH = 1000;
  const SESSION_TIMEOUT = 3600; // 1 hour
}

// Input validation and sanitization
class InputValidator
{
  public static function validateInput($input)
  {
    if (!is_array($input)) {
      throw new InvalidArgumentException('Input must be an array');
    }

    if (!isset($input['message']) || empty(trim($input['message']))) {
      throw new InvalidArgumentException('Message is required');
    }

    $message = trim($input['message']);
    if (strlen($message) > Config::MAX_MESSAGE_LENGTH) {
      throw new InvalidArgumentException('Message too long');
    }

    // Sanitize message
    $message = self::sanitizeText($message);

    $result = [
      'message' => $message,
      'latitude' => isset($input['latitude']) ? self::validateCoordinate($input['latitude']) : null,
      'longitude' => isset($input['longitude']) ? self::validateCoordinate($input['longitude']) : null,
      'session_id' => isset($input['session_id']) ? self::validateSessionId($input['session_id']) : self::generateSessionId()
    ];

    return $result;
  }

  private static function sanitizeText($text)
  {
    $text = strip_tags($text);
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    return $text;
  }

  private static function validateCoordinate($coord)
  {
    if (!is_numeric($coord)) {
      return null;
    }
    $coord = floatval($coord);
    return ($coord >= -180 && $coord <= 180) ? $coord : null;
  }

  private static function validateSessionId($sessionId)
  {
    if (!preg_match('/^chat_[a-zA-Z0-9_-]+$/', $sessionId)) {
      return self::generateSessionId();
    }
    return $sessionId;
  }

  private static function generateSessionId()
  {
    return 'chat_' . time() . '_' . bin2hex(random_bytes(8));
  }
}

// Enhanced session management
class SessionManager
{
  private $sessionFile;
  private $sessionData;

  public function __construct($sessionId)
  {
    $this->ensureSessionsDir();
    $this->sessionFile = Config::SESSIONS_DIR . '/' . $sessionId . '.json';
    $this->loadSession();
    $this->cleanupOldSessions();
  }

  private function ensureSessionsDir()
  {
    if (!is_dir(Config::SESSIONS_DIR)) {
      mkdir(Config::SESSIONS_DIR, 0755, true);
    }
  }

  private function loadSession()
  {
    if (file_exists($this->sessionFile)) {
      $data = json_decode(file_get_contents($this->sessionFile), true);
      // Check if session is expired
      if ($data && isset($data['timestamp']) && (time() - $data['timestamp']) < Config::SESSION_TIMEOUT) {
        $this->sessionData = $data;
        return;
      }
    }
    $this->sessionData = ['timestamp' => time()];
  }

  public function saveSession()
  {
    $this->sessionData['timestamp'] = time();
    file_put_contents($this->sessionFile, json_encode($this->sessionData, JSON_PRETTY_PRINT));
  }

  public function getData($key = null)
  {
    if ($key === null) {
      return $this->sessionData;
    }
    return $this->sessionData[$key] ?? null;
  }

  public function setData($key, $value)
  {
    $this->sessionData[$key] = $value;
  }

  public function clearReservation()
  {
    unset($this->sessionData['reservation_step'], $this->sessionData['reservation_data'], $this->sessionData['available_hotels']);
  }

  private function cleanupOldSessions()
  {
    $files = glob(Config::SESSIONS_DIR . '/*.json');
    $now = time();
    foreach ($files as $file) {
      if (($now - filemtime($file)) > Config::SESSION_TIMEOUT) {
        @unlink($file);
      }
    }
  }
}

// Hotel service with better error handling
class HotelService
{
  private const BACKEND_URL = "http://localhost/AI-Gemini/backend.php";
  private const TIMEOUT = 15;

  public static function fetchHotels()
  {
    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => self::BACKEND_URL,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => self::TIMEOUT,
      CURLOPT_FAILONERROR => true
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
      error_log("Hotel service error: $error");
      return self::createErrorResult("Unable to fetch hotel data at the moment");
    }

    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      error_log("Hotel service JSON error: " . json_last_error_msg());
      return self::createErrorResult("Invalid hotel data format");
    }

    return self::processHotelData($data);
  }

  private static function processHotelData($data)
  {
    if (empty($data['hotels']) || !is_array($data['hotels'])) {
      return [
        'data' => "Currently no hotels available under ₱3000.",
        'available' => false,
        'count' => 0,
        'hotels' => []
      ];
    }

    $hotelData = "";
    $availableHotels = [];

    foreach ($data['hotels'] as $index => $hotel) {
      $title = $hotel['title'] ?? $hotel['name'] ?? 'Unknown Hotel';
      $description = $hotel['description'] ?? 'No description available';
      $price = isset($hotel['price_per_night']) ? '₱' . number_format($hotel['price_per_night'], 0) : 'Price not available';
      $address = $hotel['address'] ?? $hotel['location'] ?? 'Address not specified';
      $propertyId = $hotel['property_id'] ?? $hotel['id'] ?? $title;

      $availableHotels[] = [
        'property_id' => $propertyId,
        'display_title' => $title,
        'original_data' => $hotel
      ];

      $hotelData .= ($index + 1) . ". **$title**\n";
      $hotelData .= "   📍 $address\n";
      $hotelData .= "   💰 $price per night\n";
      $hotelData .= "   📝 $description\n\n";
    }

    return [
      'data' => $hotelData,
      'available' => true,
      'count' => count($data['hotels']),
      'hotels' => $availableHotels
    ];
  }

  private static function createErrorResult($message)
  {
    return [
      'data' => $message,
      'available' => false,
      'count' => 0,
      'hotels' => []
    ];
  }
}

// Enhanced reservation handler
class ReservationHandler
{
  private const RESERVATION_URL = "http://localhost/booking-sys/src/pages/ai/backend/reservation-endpoint.php";

  public static function process($reservationData)
  {
    $selectedHotel = $reservationData['selected_hotel'] ?? [];
    $propertyId = $selectedHotel['property_id'] ?? '';

    if (empty($propertyId)) {
      return self::createErrorResult("Invalid hotel selection");
    }

    $postData = [
      'unit' => $propertyId,
      'name' => $reservationData['name'] ?? '',
      'phoneno' => $reservationData['phoneno'] ?? '',
      'stay-duration' => $reservationData['duration'] ?? '',
      'description' => $reservationData['description'] ?? ''
    ];

    // Validate required fields
    $validation = self::validateReservationData($postData);
    if (!$validation['valid']) {
      return self::createErrorResult($validation['message']);
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => self::RESERVATION_URL,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => http_build_query($postData),
      CURLOPT_TIMEOUT => 15,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) {
      return self::createErrorResult("Connection to reservation service failed");
    }

    $result = json_decode($response, true);
    return self::parseReservationResponse($result, $reservationData);
  }

  private static function validateReservationData($data)
  {
    if (empty($data['name'])) {
      return ['valid' => false, 'message' => 'Name is required'];
    }
    if (empty($data['phoneno'])) {
      return ['valid' => false, 'message' => 'Phone number is required'];
    }
    if (empty($data['stay-duration']) || !is_numeric($data['stay-duration']) || $data['stay-duration'] <= 0) {
      return ['valid' => false, 'message' => 'Valid stay duration is required'];
    }
    return ['valid' => true];
  }

  private static function parseReservationResponse($result, $reservationData)
  {
    if ($result && isset($result['error']) && !$result['error']) {
      $hotelName = $reservationData['selected_hotel']['display_title'] ?? 'selected hotel';

      $confirmationMessage = "✅ Booking confirmed for **" . $hotelName . "**!\n\n" .
        "📋 Booking Details:\n" .
        "• Name: " . ($reservationData['name'] ?? '') . "\n" .
        "• Phone: " . ($reservationData['phoneno'] ?? '') . "\n" .
        "• Duration: " . ($reservationData['duration'] ?? '') . " nights\n" .
        "• Special Requests: " . ($reservationData['description'] ?? 'None') . "\n\n" .
        "Thank you for your booking! " . ($result['message'] ?? 'We will contact you shortly.');

      return [
        'success' => true,
        'message' => $confirmationMessage
      ];
    }

    $errorMsg = $result['message'] ?? 'Reservation failed. Please try again.';
    if (!empty($result['fields'])) {
      $errorMsg .= "\n\nPlease check:\n";
      foreach ($result['fields'] as $field => $error) {
        $errorMsg .= "• " . $error . "\n";
      }
    }

    return self::createErrorResult($errorMsg);
  }

  private static function createErrorResult($message)
  {
    return [
      'success' => false,
      'message' => "❌ " . $message
    ];
  }
}

// Enhanced Chatbot Class with Fixed Reservation Flow
class EnhancedChatbot
{
  private $sessionManager;
  private $input;
  private $triggers;

  public function __construct($sessionManager, $input)
  {
    $this->sessionManager = $sessionManager;
    $this->input = $input;
    $this->triggers = [
      'hotel' => [
        'hotel',
        'hotels',
        'accommodation',
        'stay',
        'booking',
        'lodging',
        'inn',
        'motel',
        'resort',
        'hostel',
        'place to stay',
        'where to stay',
        'book hotel',
        'cheap hotel',
        'budget hotel',
        'affordable stay',
        'room',
        'rooms',
        'vacation stay'
      ],
      'reservation' => [
        'book',
        'reserve',
        'reservation',
        'booking',
        'make reservation',
        'i want to book',
        'i want to reserve',
        'make booking',
        'reserve room',
        'book room',
        'confirm booking'
      ],
      'reset' => [
        'hello',
        'hi',
        'hey',
        'start',
        'new',
        'restart',
        'again',
        'another',
        'help',
        'menu',
        'options'
      ]
    ];
  }

  public function process()
  {
    $message = $this->input['message'];
    $latitude = $this->input['latitude'];
    $longitude = $this->input['longitude'];

    // Check if we should reset completed reservation
    $this->checkResetReservation($message);

    $hotelMatched = $this->checkTriggers($message, 'hotel');
    $reservationMatched = $this->checkTriggers($message, 'reservation');
    $reservationInProgress = $this->sessionManager->getData('reservation_step') !== null;

    // Handle reservation flow FIRST
    $directResponse = '';
    if ($reservationInProgress) {
      $directResponse = $this->handleReservationFlow($message);
    } elseif ($reservationMatched && !$reservationInProgress) {
      $directResponse = $this->startNewReservation();
    }

    // If we have a direct response from reservation flow, use it
    if (!empty($directResponse)) {
      return [
        'response' => trim($directResponse),
        'hotels_matched' => $hotelMatched,
        'reservation_matched' => $reservationMatched,
        'reservation_in_progress' => $reservationInProgress,
        'session_id' => $this->input['session_id']
      ];
    }

    // Process through Gemini API for non-reservation messages
    return $this->processWithGemini($message, $hotelMatched, $reservationMatched, $reservationInProgress, $latitude, $longitude);
  }

  private function checkResetReservation($message)
  {
    $currentStep = $this->sessionManager->getData('reservation_step');

    // Reset completed reservation if user starts a new conversation
    if ($currentStep === 'completed') {
      $lowerMessage = strtolower($message);

      // Check for reset keywords
      foreach ($this->triggers['reset'] as $keyword) {
        if (strpos($lowerMessage, $keyword) !== false) {
          $this->sessionManager->clearReservation();
          return;
        }
      }

      // Check for hotel/reservation keywords
      if ($this->checkTriggers($message, 'hotel') || $this->checkTriggers($message, 'reservation')) {
        $this->sessionManager->clearReservation();
        return;
      }

      // Also reset if it's clearly a new topic (not simple acknowledgments)
      if (!$this->isSimpleAcknowledgment($message)) {
        $this->sessionManager->clearReservation();
      }
    }

    // Reset error state on new intent
    if ($currentStep === 'error' && ($this->checkTriggers($message, 'hotel') || $this->checkTriggers($message, 'reservation'))) {
      $this->sessionManager->clearReservation();
    }
  }

  private function isSimpleAcknowledgment($message)
  {
    $acknowledgments = [
      'thanks',
      'thank you',
      'ok',
      'okay',
      'got it',
      'understood',
      'cool',
      'great',
      'awesome',
      'perfect',
      'nice',
      'good'
    ];

    $lowerMessage = strtolower(trim($message));
    return in_array($lowerMessage, $acknowledgments);
  }

  private function checkTriggers($message, $type)
  {
    $lowerMessage = strtolower($message);
    foreach ($this->triggers[$type] as $trigger) {
      if (strpos($lowerMessage, $trigger) !== false) {
        return true;
      }
    }
    return false;
  }

  private function startNewReservation()
  {
    $hotelData = HotelService::fetchHotels();

    if ($hotelData['available']) {
      $this->sessionManager->setData('reservation_step', 'ask_hotel_selection');
      $this->sessionManager->setData('reservation_data', []);
      $this->sessionManager->setData('available_hotels', $hotelData['hotels']);

      return "Great! I'd be happy to help you make a reservation. Here are our available budget hotels:\n\n" .
        $hotelData['data'] .
        "\n\nPlease select a hotel by number (1, 2, etc.):";
    } else {
      return "I'd love to help you make a reservation, but currently no hotels are available under ₱3000. Please check back later!";
    }
  }

  private function handleReservationFlow($message)
  {
    $step = $this->sessionManager->getData('reservation_step');
    $reservationData = $this->sessionManager->getData('reservation_data') ?? [];
    $availableHotels = $this->sessionManager->getData('available_hotels') ?? [];

    switch ($step) {
      case 'ask_hotel_selection':
        if (is_numeric($message) && isset($availableHotels[$message - 1])) {
          $this->sessionManager->setData('reservation_data', array_merge($reservationData, [
            'selected_hotel' => $availableHotels[$message - 1]
          ]));
          $this->sessionManager->setData('reservation_step', 'ask_name');

          $selectedHotel = $availableHotels[$message - 1];
          return "Great choice! You selected: **" . ($selectedHotel['display_title'] ?? 'Unknown Hotel') .
            "**\n\nPlease provide your full name:";
        } else {
          return "Please select a valid hotel number (1, 2, 3, etc.) from the list above:";
        }

      case 'ask_name':
        if (empty(trim($message))) {
          return "Please provide your full name:";
        }
        $this->sessionManager->setData('reservation_data', array_merge($reservationData, [
          'name' => trim($message)
        ]));
        $this->sessionManager->setData('reservation_step', 'ask_phone');
        return "Thank you, " . trim($message) . "! Now please provide your phone number:";

      case 'ask_phone':
        if (empty(trim($message))) {
          return "Please provide your phone number:";
        }
        $this->sessionManager->setData('reservation_data', array_merge($reservationData, [
          'phoneno' => trim($message)
        ]));
        $this->sessionManager->setData('reservation_step', 'ask_duration');
        return "Perfect! How many nights would you like to stay?";

      case 'ask_duration':
        if (!is_numeric($message) || $message <= 0) {
          return "Please enter a valid number of nights (e.g., 1, 2, 3):";
        }
        $this->sessionManager->setData('reservation_data', array_merge($reservationData, [
          'duration' => intval($message)
        ]));
        $this->sessionManager->setData('reservation_step', 'ask_description');
        return "Any special requests or notes for your stay? (Type 'none' if no special requests)";

      case 'ask_description':
        $description = empty(trim($message)) || strtolower(trim($message)) === 'none' ? 'No special requests' : trim($message);
        $this->sessionManager->setData('reservation_data', array_merge($reservationData, [
          'description' => $description
        ]));

        // Process the reservation
        $result = ReservationHandler::process($this->sessionManager->getData('reservation_data'));

        if ($result['success']) {
          $this->sessionManager->setData('reservation_step', 'completed');
          $this->sessionManager->setData('reservation_data', []);
          $this->sessionManager->setData('last_reservation_time', time());
          return "✅ " . $result['message'];
        } else {
          $this->sessionManager->setData('reservation_step', 'error');
          return "❌ " . $result['message'] . "\n\nSay 'book' to try again or ask about hotels.";
        }

      case 'completed':
        // Check if user wants to start over
        if ($this->checkTriggers($message, 'reservation') || $this->checkTriggers($message, 'hotel')) {
          return $this->startNewReservation();
        }

        // If it's a simple acknowledgment, stay in completed state
        if ($this->isSimpleAcknowledgment($message)) {
          return "You're welcome! Is there anything else I can help you with today?";
        }

        // Otherwise, offer options
        return "Your previous reservation was completed successfully. Would you like to:\n" .
          "• Make another reservation\n" .
          "• Browse available hotels\n" .
          "• Ask something else?";

      case 'error':
        // Allow retry from error state
        if ($this->checkTriggers($message, 'reservation') || strtolower($message) === 'retry' || strtolower($message) === 'try again') {
          return $this->startNewReservation();
        }

        if ($this->checkTriggers($message, 'hotel')) {
          $hotelData = HotelService::fetchHotels();
          if ($hotelData['available']) {
            return "Here are our available hotels:\n\n" . $hotelData['data'] .
              "\n\nWould you like to book any of these?";
          } else {
            return "Currently no hotels are available. Please check back later!";
          }
        }

        return "There was an error with your previous reservation. You can:\n" .
          "• Say 'book' to try again\n" .
          "• Say 'hotels' to see available options\n" .
          "• Ask for help";
    }

    return "";
  }

  private function processWithGemini($message, $hotelMatched, $reservationMatched, $reservationInProgress, $latitude, $longitude)
  {
    // Build prompt for Gemini API
    $prompt = $this->buildPrompt($message, $hotelMatched, $reservationMatched, $reservationInProgress, $latitude, $longitude);

    // Call Gemini API
    $response = $this->callGeminiAPI($prompt);

    return [
      'response' => trim($response),
      'hotels_matched' => $hotelMatched,
      'reservation_matched' => $reservationMatched,
      'reservation_in_progress' => $reservationInProgress,
      'session_id' => $this->input['session_id']
    ];
  }

  private function buildPrompt($message, $hotelMatched, $reservationMatched, $reservationInProgress, $latitude, $longitude)
  {
    $locationContext = $latitude && $longitude ? "User location: $latitude, $longitude\n\n" : "";

    // If we're in reservation flow but ended up here, provide context-aware response
    if ($reservationInProgress) {
      $currentStep = $this->sessionManager->getData('reservation_step');
      return $locationContext .
        "You are a travel assistant currently in a reservation process. " .
        "Current step: $currentStep. " .
        "The user said: \"$message\". " .
        "Provide helpful, concise assistance related to completing the reservation.";
    }

    // Handle hotel inquiries
    if ($hotelMatched) {
      $hotelData = HotelService::fetchHotels();
      if ($hotelData['available']) {
        return $locationContext .
          "You are a helpful travel assistant. The user is looking for budget hotels under ₱3000.\n\n" .
          "Available Hotels:\n" . $hotelData['data'] . "\n\n" .
          "Present the options clearly and ask if they'd like to make a reservation. " .
          "Be friendly and helpful. If the user seems interested in booking, suggest they say 'book' or 'reserve'.";
      } else {
        return $locationContext .
          "You are a helpful travel assistant. The user asked about hotels but none are currently available under ₱3000. " .
          "Apologize politely and suggest they check back later. Offer alternative help.";
      }
    }

    // General conversation with context awareness
    $sessionContext = "";
    $lastReservationTime = $this->sessionManager->getData('last_reservation_time');
    if ($lastReservationTime && (time() - $lastReservationTime) < 300) { // 5 minutes
      $sessionContext = "Note: The user recently completed a reservation. ";
    }

    return $locationContext . $sessionContext .
      "You are a friendly travel assistant. Respond helpfully to: \"$message\"";
  }

  private function callGeminiAPI($prompt)
  {
    $api_key = Config::API_KEY;

    $payload = [
      'contents' => [
        [
          'parts' => [
            ['text' => $prompt]
          ]
        ]
      ],
      'generationConfig' => [
        'temperature' => 0.7,
        'maxOutputTokens' => 1000,
        'candidateCount' => 1
      ],
      'safetySettings' => [
        [
          'category' => 'HARM_CATEGORY_HARASSMENT',
          'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
        ],
        [
          'category' => 'HARM_CATEGORY_HATE_SPEECH',
          'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
        ]
      ]
    ];

    $ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$api_key");
    curl_setopt_array($ch, [
      CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
      CURLOPT_POST => 1,
      CURLOPT_POSTFIELDS => json_encode($payload),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 30
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) {
      return "I apologize, but I'm having trouble processing your request right now. Please try again in a moment.";
    }

    $responseData = json_decode($response, true);

    if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
      return $responseData['candidates'][0]['content']['parts'][0]['text'];
    }

    if (isset($responseData['error'])) {
      error_log("Gemini API error: " . $responseData['error']['message']);
    }

    return "Thanks for your message! I'm here to help you with hotel bookings and travel assistance. How can I help you today?";
  }
}

// Main execution
try {
  // Get and validate input
  $rawInput = file_get_contents('php://input');
  if (empty($rawInput)) {
    throw new InvalidArgumentException('No input received');
  }

  $input = json_decode($rawInput, true);

  if (json_last_error() !== JSON_ERROR_NONE) {
    throw new InvalidArgumentException('Invalid JSON input: ' . json_last_error_msg());
  }

  $validatedInput = InputValidator::validateInput($input);

  // Initialize session
  $sessionManager = new SessionManager($validatedInput['session_id']);

  // Process through enhanced chatbot
  $chatbot = new EnhancedChatbot($sessionManager, $validatedInput);
  $response = $chatbot->process();

  // Save session state
  $sessionManager->saveSession();

  // Return response
  echo json_encode($response);
} catch (InvalidArgumentException $e) {
  error_log("Input validation error: " . $e->getMessage());
  http_response_code(400);
  echo json_encode([
    'error' => 'Invalid input',
    'response' => 'Please provide a valid message.'
  ]);
} catch (Exception $e) {
  error_log("Chatbot system error: " . $e->getMessage());
  http_response_code(500);
  echo json_encode([
    'error' => 'System error',
    'response' => 'I apologize, but I encountered a system error. Please try again in a moment.'
  ]);
}
