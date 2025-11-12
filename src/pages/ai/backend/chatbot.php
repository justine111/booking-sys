<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;

// ============================================
// 1. Load environment variables
// ============================================
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$api_key = $_ENV['GEMINI_API_KEY'] ?? getenv('GEMINI_API_KEY');
if (!$api_key) {
    http_response_code(500);
    echo json_encode(['error' => 'Missing GEMINI_API_KEY in .env']);
    exit;
}

// ============================================
// 2. Basic setup
// ============================================
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit; // handle preflight
}

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$api_key";

// ============================================
// 3. Read and validate input
// ============================================
$input = json_decode(file_get_contents("php://input"), true);
if (!$input || !isset($input['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input. Expecting JSON with a "message" field.']);
    exit;
}

$user_message = trim($input['message']);
$client_location_info = '';
$hotel_list = '';
$matched = false;

// ============================================
// 4. Load trigger keywords
// ============================================
require_once __DIR__ . '/trigger.php'; // $hotel_triggers must exist

foreach ($hotel_triggers as $trigger) {
    if (stripos($user_message, $trigger) !== false) {
        $matched = true;
        break;
    }
}

// ============================================
// 5. Fetch hotel data if trigger matched
// ============================================
if ($matched) {
    $backend_url = "http://localhost/AI-Gemini/backend.php";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $backend_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
    ]);
    $backend_response = curl_exec($ch);
    $curl_err = curl_error($ch);
    curl_close($ch);

    if ($backend_response === false) {
        error_log("CURL error (backend): $curl_err");
        $hotel_list = "Sorry, we encountered a server issue while fetching hotel data.";
    } else {
        $data = json_decode($backend_response, true);
        if (!empty($data['hotels'])) {
            foreach ($data['hotels'] as $hotel) {
                $desc = $hotel['description'] ?? 'Unknown Hotel';
                $loc = $hotel['location'] ?? 'Unknown location';
                $price = isset($hotel['price']) ? number_format($hotel['price'], 0) : 'N/A';
                $hotel_list .= "- $desc located in $loc, priced at ₱$price per night.\n";
            }
        } else {
            $hotel_list = "Sorry, I couldn't find any available hotels under ₱3000 right now.";
        }
    }
}

// ============================================
// 6. Reverse geocode (optional)
// ============================================
if (isset($input['latitude'], $input['longitude'])) {
    $lat = (float)$input['latitude'];
    $lon = (float)$input['longitude'];
    $geo_url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$lat&lon=$lon&zoom=10&addressdetails=1";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $geo_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => "TravelBot/1.0 (your_email@example.com)",
        CURLOPT_TIMEOUT => 10,
    ]);
    $geo_response = curl_exec($ch);
    $geo_http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $geo_err = curl_error($ch);
    curl_close($ch);

    if ($geo_response === false) {
        error_log("Geocode CURL error: $geo_err");
    } elseif ($geo_http === 200) {
        $geo_data = json_decode($geo_response, true);
        if (isset($geo_data['display_name'])) {
            $client_location_info = "User's approximate location: {$geo_data['display_name']}.";
        } else {
            $client_location_info = "User coordinates: Latitude $lat, Longitude $lon.";
        }
    } else {
        error_log("Geocode HTTP error $geo_http: $geo_response");
    }
}

// ============================================
// 7. Build prompt
// ============================================
if (!empty($hotel_list)) {
    $prompt_text = <<<EOD
You are a helpful travel assistant. The user wants budget-friendly hotels under ₱3000.

$client_location_info

Here are the available hotels:
$hotel_list

Please reply in a friendly, well-formatted style with bullet points, mentioning each hotel's price and location.
Encourage quick booking if availability is limited.
EOD;
} else {
    $prompt_text = $client_location_info . "\n\n" . $user_message;
}

// ============================================
// 8. Send to Gemini API
// ============================================
$request_body = [
    "contents" => [
        [
            "parts" => [
                ["text" => $prompt_text]
            ]
        ]
    ]
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($request_body),
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    CURLOPT_TIMEOUT => 20,
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_err = curl_error($ch);
curl_close($ch);

// Save raw response for debugging
file_put_contents(__DIR__ . "/debug_gemini_response.json", $response ?: 'NO RESPONSE');

// ============================================
// 9. Handle Gemini API errors
// ============================================
if ($response === false) {
    error_log("CURL error (Gemini): $curl_err");
    http_response_code(500);
    echo json_encode(['error' => 'Gemini API request failed']);
    exit;
}

$response_data = json_decode($response, true);

if ($http_code !== 200) {
    $error_msg = $response_data['error']['message'] ?? "Gemini API returned HTTP $http_code";
    error_log("Gemini API HTTP $http_code: $error_msg");
    http_response_code($http_code);
    echo json_encode(['error' => $error_msg]);
    exit;
}

$text = $response_data['candidates'][0]['content']['parts'][0]['text'] ?? null;
if (!$text) {
    error_log("Unexpected Gemini API structure: " . $response);
    http_response_code(500);
    echo json_encode(['error' => 'Unexpected response format from Gemini. See debug log.']);
    exit;
}

// ============================================
// 10. Output AI response
// ============================================
$ai_response = trim($text);
echo json_encode(['response' => $ai_response]);
