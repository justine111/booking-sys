<?php

/**
 * SMS Test Script
 * Run this to test your SMS configuration
 * 
 * Usage: php test_sms.php
 */

// Load the SMS helper
require_once __DIR__ . '/src/helpers/sms-helper.php';

echo "==============================================\n";
echo "SMS Notification Test Script\n";
echo "==============================================\n\n";

// Initialize SMS helper
$smsHelper = new SMSHelper();

// Test configuration
echo "1. Testing Environment Configuration...\n";
$apiKey = EnvHelper::get('SMS_API_KEY', '');
$apiUrl = EnvHelper::get('SMS_API_URL', '');
$senderId = EnvHelper::get('SMS_SENDER_ID', 'BookingSys');

if (empty($apiKey)) {
  echo "   ⚠️  SMS_API_KEY not configured (Development Mode)\n";
} else {
  echo "   ✅ SMS_API_KEY configured\n";
}

if (empty($apiUrl)) {
  echo "   ⚠️  SMS_API_URL not configured (Development Mode)\n";
} else {
  echo "   ✅ SMS_API_URL configured: $apiUrl\n";
}

echo "   ✅ Sender ID: $senderId\n\n";

// Test phone number
echo "2. Enter test phone number (or press Enter for 09171234567): ";
$handle = fopen("php://stdin", "r");
$phoneNumber = trim(fgets($handle));
fclose($handle);

if (empty($phoneNumber)) {
  $phoneNumber = '09171234567';
}

echo "   Using phone number: $phoneNumber\n\n";

// Test data
$testData = [
  'contactNo' => $phoneNumber,
  'clientName' => 'John Doe',
  'propertyTitle' => 'Balay ni Tatay',
  'checkInDate' => '2025-12-10 14:00:00',
  'checkOutDate' => '2025-12-12 12:00:00',
  'totalAmount' => 2500.00
];

echo "3. Sending test SMS...\n";
echo "   Client: {$testData['clientName']}\n";
echo "   Property: {$testData['propertyTitle']}\n";
echo "   Check-in: {$testData['checkInDate']}\n";
echo "   Check-out: {$testData['checkOutDate']}\n";
echo "   Amount: PHP " . number_format($testData['totalAmount'], 2) . "\n\n";

// Send test SMS
$result = $smsHelper->sendBookingApprovalSMS(
  $testData['contactNo'],
  $testData['clientName'],
  $testData['propertyTitle'],
  $testData['checkInDate'],
  $testData['checkOutDate'],
  $testData['totalAmount']
);

// Display results
echo "4. Results:\n";
if ($result['success']) {
  echo "   ✅ SUCCESS: " . $result['message'] . "\n";

  if (isset($result['mode']) && $result['mode'] == 'development') {
    echo "   📝 Running in DEVELOPMENT mode\n";
    echo "   💡 Check logs/sms_log.txt for the message\n";
    echo "   💡 Configure .env with SMS provider credentials for production\n";
  } else {
    echo "   📱 SMS sent to $phoneNumber\n";
    echo "   💡 Check your phone for the message\n";
    echo "   💡 Check your SMS provider dashboard for delivery status\n";
  }

  if (isset($result['response'])) {
    echo "   📊 Provider Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n";
  }
} else {
  echo "   ❌ FAILED: " . $result['message'] . "\n";

  if (isset($result['http_code'])) {
    echo "   📊 HTTP Code: " . $result['http_code'] . "\n";
  }

  if (isset($result['response'])) {
    echo "   📋 Response: " . $result['response'] . "\n";
  }

  echo "   💡 Check logs/sms_log.txt for details\n";
  echo "   💡 Review SMS_SETUP.md for troubleshooting\n";
}

echo "\n==============================================\n";
echo "Test Complete!\n";
echo "==============================================\n\n";

// Show log file location
$logFile = __DIR__ . '/logs/sms_log.txt';
if (file_exists($logFile)) {
  echo "📝 Log File Location: $logFile\n";
  echo "📝 Last 5 lines of log:\n";
  echo "----------------------------------------------\n";
  $lines = file($logFile);
  $lastLines = array_slice($lines, -5);
  foreach ($lastLines as $line) {
    echo $line;
  }
  echo "----------------------------------------------\n";
} else {
  echo "📝 Log file will be created at: $logFile\n";
}

echo "\n✨ For more information, see SMS_SETUP.md\n\n";
