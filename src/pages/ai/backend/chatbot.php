<?php
header('Content-Type: application/json');

$api_key = 'AIzaSyAVTtWzjt2vP3pfDkNoabV3Dr7txtwlqRM'; //Gemini API keys

$sessions_dir = __DIR__ . '/sessions'; //ensure session folder exist
if (!is_dir($sessions_dir)) {
  mkdir($sessions_dir, 0755, true);
}

// Get the raw POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['message'])) {
  echo json_encode(['error' => 'No message provided.']);
  exit;
}

$message = trim($input['message']);
$latitude = $input['latitude'] ?? null;
$longitude = $input['longitude'] ?? null;
$session_id = $input['session_id'] ?? uniqid('chat_', true);

$session_file = $sessions_dir . '/' . $session_id . '.json';
$session_data = [];

if (file_exists($session_file)) {
  $session_data = json_decode(file_get_contents($session_file), true) ?? [];
}

$hotel_triggers = [
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
];

$reservation_triggers = [
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
];

$hotel_matched = false;
$reservation_matched = false;

foreach ($hotel_triggers as $trigger) {
  if (stripos($message, $trigger) !== false) {
    $hotel_matched = true;
    break;
  }
}

foreach ($reservation_triggers as $trigger) {
  if (stripos($message, $trigger) !== false) {
    $reservation_matched = true;
    break;
  }
}

$hotel_data = '';
$hotels_available = false;
$hotel_count = 0;
$available_hotels = [];

if ($hotel_matched || $reservation_matched || !empty($session_data['reservation_step'])) {
  $hotel_data_result = fetchHotelData();
  $hotel_data = $hotel_data_result['data'];
  $hotels_available = $hotel_data_result['available'];
  $hotel_count = $hotel_data_result['count'];
  $available_hotels = $hotel_data_result['hotels'];

  // Store available hotels in session for reservation
  if ($hotels_available) {
    $session_data['available_hotels'] = $available_hotels;
  }
}

//chatbot reservation process
$reservation_in_progress = !empty($session_data['reservation_step']);
$direct_response = '';

if ($reservation_matched && !$reservation_in_progress) {
  // Start new reservation
  $session_data['reservation_step'] = 'ask_hotel_selection';
  $session_data['reservation_data'] = [];
  $reservation_in_progress = true;

  if ($hotels_available) {
    $direct_response = "Great! I'd be happy to help you make a reservation. Here are our available budget hotels:\n\n" . $hotel_data . "\n\nPlease select a hotel by number (1, 2, etc.):";
  } else {
    $direct_response = "I'd love to help you make a reservation, but currently no hotels are available under ₱3000. Please check back later!";
  }
} elseif ($reservation_in_progress) {
  // Handle the reservation step
  $step_result = handleReservationFlow($message, $session_data);
  if (!empty($step_result)) {
    $direct_response = $step_result;
  }
}

// ============================================
// RESPONSE GENERATION - FIXED LOGIC
// ============================================

// If we have a direct response from reservation flow, use it and skip Gemini
if (!empty($direct_response)) {
  // Save session
  file_put_contents($session_file, json_encode($session_data, JSON_PRETTY_PRINT));

  echo json_encode([
    'response' => trim($direct_response),
    'hotels_matched' => $hotel_matched,
    'reservation_matched' => $reservation_matched,
    'reservation_in_progress' => $reservation_in_progress,
    'session_id' => $session_id
  ]);
  exit;
}

// ============================================
// GEMINI API CALL (Only for non-reservation messages)
// ============================================

$final_prompt = buildFinalPrompt(
  $message,
  $hotel_matched,
  $reservation_matched,
  $reservation_in_progress,
  $hotel_data,
  $hotels_available,
  $available_hotels,
  $session_data,
  $latitude,
  $longitude
);

$payload = [
  'contents' => [
    [
      'parts' => [
        [
          'text' => $final_prompt
        ]
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
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
  echo json_encode(['error' => 'Failed to connect to Gemini API']);
  exit;
}

curl_close($ch);

$response_data = json_decode($response, true);
$text = $response_data['candidates'][0]['content']['parts'][0]['text'] ?? null;

// Fallback response if Gemini fails
if (!$text) {
  $text = generateFallbackResponse($message, $hotel_matched, $reservation_matched, $reservation_in_progress, $hotel_data, $hotels_available);
}

// Save session
file_put_contents($session_file, json_encode($session_data, JSON_PRETTY_PRINT));

// Return to frontend
echo json_encode([
  'response' => trim($text),
  'hotels_matched' => $hotel_matched,
  'reservation_matched' => $reservation_matched,
  'reservation_in_progress' => $reservation_in_progress,
  'session_id' => $session_id
]);

// ============================================
// HELPER FUNCTIONS
// ============================================

function fetchHotelData()
{
  $backend_url = "http://localhost/AI-Gemini/backend.php";
  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL => $backend_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
  ]);
  $backend_response = curl_exec($ch);
  curl_close($ch);

  // DEBUG: Log the actual response from backend
  file_put_contents(
    __DIR__ . '/debug_backend_response.log',
    date('Y-m-d H:i:s') . " - Backend Response: " . $backend_response . "\n",
    FILE_APPEND
  );

  $result = [
    'data' => '',
    'available' => false,
    'count' => 0,
    'hotels' => []
  ];

  if ($backend_response !== false) {
    $data = json_decode($backend_response, true);

    // DEBUG: Log the parsed data structure
    file_put_contents(
      __DIR__ . '/debug_hotel_structure.log',
      date('Y-m-d H:i:s') . " - Hotel Data Structure: " . print_r($data, true) . "\n",
      FILE_APPEND
    );

    if (!empty($data['hotels']) && is_array($data['hotels'])) {
      $result['available'] = true;
      $result['count'] = count($data['hotels']);
      $result['hotels'] = $data['hotels'];

      $hotel_data = "";
      foreach ($data['hotels'] as $index => $hotel) {
        // Check what fields are actually available
        $title = $hotel['title'] ?? $hotel['name'] ?? 'Unknown Hotel';
        $description = $hotel['description'] ?? 'No description available';
        $price = isset($hotel['price_per_night']) ? '₱' . number_format($hotel['price_per_night'], 0) : 'Price not available';
        $address = $hotel['address'] ?? $hotel['location'] ?? 'Address not specified';

        // Try to find the actual property ID field
        $property_id = $hotel['property_id'] ?? $hotel['id'] ?? $hotel['title'] ?? 'unknown';

        // Store all available data for reservation
        $result['hotels'][$index] = $hotel; // Keep original data
        $result['hotels'][$index]['property_id'] = $property_id;
        $result['hotels'][$index]['display_title'] = $title;

        $hotel_data .= ($index + 1) . ". **$title**\n";
        $hotel_data .= "   $address\n";
        $hotel_data .= "   $price per night\n";
        $hotel_data .= "   $description\n\n";
      }
      $result['data'] = $hotel_data;
    } else {
      $result['data'] = "Currently no hotels available under ₱3000.";
    }
  } else {
    $result['data'] = "Unable to fetch hotel data at the moment.";
  }

  return $result;
}

function handleReservationFlow($message, &$session_data)
{
  $step = $session_data['reservation_step'];
  $reservation_data = $session_data['reservation_data'] ?? [];

  switch ($step) {
    case 'ask_hotel_selection':
      if (is_numeric($message) && isset($session_data['available_hotels'][$message - 1])) {
        $session_data['reservation_data']['selected_hotel'] = $session_data['available_hotels'][$message - 1];
        $session_data['reservation_step'] = 'ask_name';
        $selected_hotel = $session_data['available_hotels'][$message - 1];
        return "Great choice! You selected: **" . ($selected_hotel['title'] ?? 'Unknown') .
          "**\n\nPlease provide your full name:";
      }
      return "Please select a hotel by number (1, 2, 3, etc.) from the available options.";

    case 'ask_name':
      $session_data['reservation_data']['name'] = $message;
      $session_data['reservation_step'] = 'ask_phone';
      $selected_hotel = $reservation_data['selected_hotel'] ?? [];
      return "Thank you, " . $message . "! Now please provide your phone number:";

    case 'ask_phone':
      $session_data['reservation_data']['phoneno'] = $message;
      $session_data['reservation_step'] = 'ask_duration';
      return "Perfect! How many nights would you like to stay?";

    case 'ask_duration':
      $session_data['reservation_data']['duration'] = $message;
      $session_data['reservation_step'] = 'ask_description';
      return "Any special requests or notes for your stay? (Type 'none' if no special requests)";

    case 'ask_description':
      $session_data['reservation_data']['description'] = $message;

      // Process the reservation
      $result = processReservation($session_data['reservation_data']);

      if ($result['success']) {
        $session_data['reservation_step'] = 'completed';
        $session_data['reservation_data'] = [];
        return "✅ " . $result['message'];
      } else {
        $session_data['reservation_step'] = 'error';
        return "❌ " . $result['message'];
      }

    case 'completed':
      return "Your reservation is already completed. How else can I help you?";
  }

  return "";
}

function processReservation($reservation_data)
{
  // Your actual reservation API endpoint
  $reservation_url = "http://localhost/booking-sys/src/pages/ai/backend/reservation-endpoint.php";

  $selected_hotel = $reservation_data['selected_hotel'] ?? [];

  // DEBUG: Log what we're sending to reservation
  file_put_contents(
    __DIR__ . '/debug_reservation_data.log',
    date('Y-m-d H:i:s') . " - Reservation Data: " . print_r($reservation_data, true) . "\n",
    FILE_APPEND
  );

  // Extract the actual property_id from the hotel data
  $property_id = $selected_hotel['property_id'] ??
    $selected_hotel['id'] ??
    $selected_hotel['title'] ??
    '';

  $post_data = [
    'unit' => $property_id, // This should be the actual database ID
    'name' => $reservation_data['name'] ?? '',
    'phoneno' => $reservation_data['phoneno'] ?? '',
    'stay-duration' => $reservation_data['duration'] ?? '',
    'description' => $reservation_data['description'] ?? ''
  ];

  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL => $reservation_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($post_data),
    CURLOPT_TIMEOUT => 15,
  ]);

  $response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  // Debug the reservation attempt
  file_put_contents(
    __DIR__ . '/debug_reservation.log',
    date('Y-m-d H:i:s') . " - Response: " . $response . " - HTTP Code: " . $http_code . "\n",
    FILE_APPEND
  );

  if ($response === false) {
    return [
      'success' => false,
      'message' => "Sorry, we encountered a connection issue. Please try again or contact support."
    ];
  }

  $result = json_decode($response, true);

  if ($result && isset($result['error']) && !$result['error']) {
    $hotel_name = $selected_hotel['title'] ?? $selected_hotel['display_title'] ?? 'selected hotel';

    return [
      'success' => true,
      'message' => "✅ Booking confirmed for **" . $hotel_name .
        "**!\n\n📋 Booking Details:\n" .
        "• Name: " . ($reservation_data['name'] ?? '') . "\n" .
        "• Phone: " . ($reservation_data['phoneno'] ?? '') . "\n" .
        "• Duration: " . ($reservation_data['duration'] ?? '') . " nights\n" .
        "• Special Requests: " . ($reservation_data['description'] ?? 'None') . "\n\n" .
        "Thank you for your booking! " . ($result['message'] ?? 'We will contact you shortly.')
    ];
  } else {
    $error_msg = $result['message'] ?? 'Reservation failed. Please try again.';

    // Handle field-specific errors
    if (!empty($result['fields'])) {
      $error_msg .= "\n\nPlease check:\n";
      foreach ($result['fields'] as $field => $error) {
        $error_msg .= "• " . $error . "\n";
      }
    }

    return [
      'success' => false,
      'message' => "❌ " . $error_msg
    ];
  }
}

function buildFinalPrompt(
  $message,
  $hotel_matched,
  $reservation_matched,
  $reservation_in_progress,
  $hotel_data,
  $hotels_available,
  $available_hotels,
  $session_data,
  $latitude,
  $longitude
) {

  $location_context = $latitude && $longitude ? "User location: $latitude, $longitude\n\n" : "";

  // Regular hotel inquiry
  if ($hotel_matched && $hotels_available) {
    return $location_context .
      "You are a helpful travel assistant. The user is looking for budget hotels under ₱3000.\n\n" .
      "Available Hotels:\n" . $hotel_data . "\n\n" .
      "Present the options clearly and ask if they'd like to make a reservation.";
  }

  if ($hotel_matched && !$hotels_available) {
    return $location_context .
      "The user asked about hotels but no hotels are currently available. Apologize politely.";
  }

  // General conversation
  return $location_context . $message;
}

function generateFallbackResponse($message, $hotel_matched, $reservation_matched, $reservation_in_progress, $hotel_data, $hotels_available)
{
  if ($reservation_in_progress) {
    return "I'm here to help with your reservation! Please continue with the booking process.";
  }

  if ($reservation_matched) {
    return "I'd be happy to help you make a reservation! Let me show you our available hotels.";
  }

  if ($hotel_matched && $hotels_available) {
    return "I found some great budget hotels for you! " . $hotel_data . " Would you like to book any of these?";
  }

  if ($hotel_matched) {
    return "I'm sorry, but no hotels are currently available under ₱3000. Please check back later!";
  }

  return "Thanks for your message! How can I assist you with your travel plans today?";
}
