<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load .env file from the current directory (backend)
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Your logic here...

$api_key = $_ENV['GEMINI_API_KEY'] ?? getenv('GEMINI_API_KEY');
if (!$api_key) {
  http_response_code(500);
  echo json_encode(['error' => 'API key missing']);
  exit;
}

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$api_key";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['message'])) {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid input']);
  exit;
}
//handle all client chat
$user_message = trim($input['message']);
$client_location_info = '';
$hotel_list = '';

//set chat triggers if match proceed to backend response
require_once __DIR__ . '/trigger.php';
$matched = false;
$hotel_list = ""; // Always initialize

foreach ($hotel_triggers as $trigger) {
  if (stripos($user_message, $trigger) !== false) {
    $matched = true;
    break;
  }
}

if ($matched) {
  $backend_url = "http://localhost/AI-Gemini/backend.php";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $backend_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $backend_response = curl_exec($ch);
  //file_put_contents("debug_backend_response.json", $backend_response); // add this


  if ($backend_response === false) {
    error_log('CURL error (backend): ' . curl_error($ch));
    $hotel_list = "Sorry, we encountered a server issue while fetching hotel data.";
  } else {
    $data = json_decode($backend_response, true);
    if (isset($data['hotels']) && count($data['hotels']) > 0) {
      foreach ($data['hotels'] as $hotel) {
        $description = $hotel['description'];
        $price = number_format($hotel['price'], 0);
        $location = $hotel['location'];

        $hotel_list .= "- $description located in $location, priced at ₱$price per night.\n";
      }
    } else {
      $hotel_list = "Sorry, I couldn't find any available hotels under ₱3000 at the moment.";
    }
  }

  curl_close($ch);
}


// =============================
// 2. Handle Location via Geocode
// =============================
if (isset($input['latitude']) && isset($input['longitude'])) {
  $latitude = (float)$input['latitude'];
  $longitude = (float)$input['longitude'];
  $nominatim_url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$latitude&lon=$longitude&zoom=10&addressdetails=1";

  $ch_nominatim = curl_init();
  curl_setopt($ch_nominatim, CURLOPT_URL, $nominatim_url);
  curl_setopt($ch_nominatim, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch_nominatim, CURLOPT_USERAGENT, "YourAppName/1.0 (your_email@example.com)");
  $nominatim_response = curl_exec($ch_nominatim);
  $nominatim_http_code = curl_getinfo($ch_nominatim, CURLINFO_HTTP_CODE);

  if ($nominatim_response === false) {
    error_log("CURL error (Nominatim): " . curl_error($ch_nominatim));
  }

  curl_close($ch_nominatim);

  if ($nominatim_http_code === 200) {
    $nominatim_data = json_decode($nominatim_response, true);
    if (isset($nominatim_data['display_name'])) {
      $client_location_info = "The user's current approximate location is: " . $nominatim_data['display_name'] . ". ";
    } else {
      $client_location_info = "The user's approximate coordinates are Latitude: $latitude, Longitude: $longitude. ";
    }
  } else {
    $client_location_info = "The user's approximate coordinates are Latitude: $latitude, Longitude: $longitude. ";
    error_log("Nominatim API error: HTTP $nominatim_http_code - $nominatim_response");
  }
}

// =====================
// 3. Construct Prompt
// =====================
$prompt_text = '';
if (!empty($hotel_list)) {
  $prompt_text = <<<EOD
You are a helpful and professional travel assistant.
A user has requested budget-friendly hotels under ₱3000. Based on the data we have, here are the top-rated available hotels:

$hotel_list

Please write a well-formatted response that briefly describes these options in a clear, friendly tone. Use natural language. Mention the price and location of each hotel. If appropriate, suggest the user book soon due to limited availability and make it more like list type for more clear response.
EOD;

  // Optionally, add location info if available
  if (!empty($client_location_info)) {
    $prompt_text = $client_location_info . "\n\n" . $prompt_text;
  }
} else {
  // fallback if no match/trigger
  $prompt_text = $user_message;
}


$request_body = [
  "contents" => [
    [
      "parts" => [
        ['text' => $prompt_text]
      ]
    ]
  ]
];

// =====================
// 4. Send to Gemini API
// =====================
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_body));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($response === false) {
  error_log("CURL error (Gemini API): " . curl_error($ch));
  curl_close($ch);
  http_response_code(500);
  echo json_encode(['error' => 'Request to Gemini API failed']);
  exit;
}

curl_close($ch);

$response_data = json_decode($response, true);

if ($http_code !== 200 || !isset($response_data['candidates'][0]['content']['parts'][0]['text'])) {
  error_log("Google Gemini API error: HTTP $http_code - $response");
  http_response_code(500);
  echo json_encode(['error' => 'Unexpected API response']);
  exit;
}

// =====================
// 5. Output Response
// =====================
$ai_response = trim($response_data['candidates'][0]['content']['parts'][0]['text']);
echo json_encode(['response' => $ai_response]);
