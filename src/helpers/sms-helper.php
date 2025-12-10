<?php

require_once __DIR__ . '/env-helper.php';

/**
 * SMS Helper Class  
 * Handles sending WhatsApp notifications for booking approvals via Twilio
 * 
 * CURRENT PROVIDER: Twilio WhatsApp API
 * Configure in .env file:
 *  - TWILIO_ACCOUNT_SID
 *  - TWILIO_AUTH_TOKEN  
 *  - TWILIO_WHATSAPP_FROM (optional, defaults to sandbox)
 * 
 * Benefits of WhatsApp:
 *  - Works on trial accounts
 *  - No geographic restrictions
 *  - FREE to use
 *  - Widely used in Philippines
 */
class SMSHelper
{
  private $apiKey;
  private $apiUrl;
  private $senderId;

  public function __construct()
  {
    // Load configuration from environment variables using EnvHelper
    $this->apiKey = EnvHelper::get('SMS_API_KEY', '');
    $this->apiUrl = EnvHelper::get('SMS_API_URL', '');
    $this->senderId = EnvHelper::get('SMS_SENDER_ID', 'BookingSys');
  }

  /**
   * Send booking approval SMS
   * 
   * @param string $contactNo Client's phone number
   * @param string $clientName Client's name
   * @param string $propertyTitle Property/hotel name
   * @param string $checkInDate Check-in date
   * @param string $checkOutDate Check-out date
   * @param float $totalAmount Total amount
   * @return array Response with success status and message
   */
  public function sendBookingApprovalSMS($contactNo, $clientName, $propertyTitle, $checkInDate, $checkOutDate, $totalAmount)
  {
    try {
      // Format the message
      $message = $this->formatBookingApprovalMessage(
        $clientName,
        $propertyTitle,
        $checkInDate,
        $checkOutDate,
        $totalAmount
      );

      // Send the SMS
      return $this->sendSMS($contactNo, $message);
    } catch (Exception $e) {
      error_log("SMS Error: " . $e->getMessage());
      return [
        'success' => false,
        'message' => $e->getMessage()
      ];
    }
  }

  /**
   * Format booking approval message
   * 
   * @param string $clientName
   * @param string $propertyTitle
   * @param string $checkInDate
   * @param string $checkOutDate
   * @param float $totalAmount
   * @return string Formatted message
   */
  private function formatBookingApprovalMessage($clientName, $propertyTitle, $checkInDate, $checkOutDate, $totalAmount)
  {
    $formattedCheckIn = date('M d, Y', strtotime($checkInDate));
    $formattedCheckOut = date('M d, Y', strtotime($checkOutDate));
    $formattedAmount = number_format($totalAmount, 2);

    $message = "Hi $clientName! Your booking has been APPROVED!\n\n";
    $message .= "Property: $propertyTitle\n";
    $message .= "Check-in: $formattedCheckIn\n";
    $message .= "Check-out: $formattedCheckOut\n";
    $message .= "Amount: PHP $formattedAmount\n\n";
    $message .= "Thank you for choosing us!";

    return $message;
  }

  /**
   * Send SMS using configured API
   * 
   * @param string $contactNo Phone number
   * @param string $message Message content
   * @return array Response
   */
  private function sendSMS($contactNo, $message)
  {
    // Log the message (for development/debugging)
    $this->logSMS($contactNo, $message);

    // Check if Twilio credentials are configured (since we're using Twilio now)
    $twilioSid = EnvHelper::get('TWILIO_ACCOUNT_SID', '');
    $twilioToken = EnvHelper::get('TWILIO_AUTH_TOKEN', '');
    $twilioNumber = EnvHelper::get('TWILIO_FROM_NUMBER', '');

    // If Twilio credentials are not configured, return mock success (development mode)
    if (empty($twilioSid) || empty($twilioToken) || empty($twilioNumber)) {
      return [
        'success' => true,
        'message' => 'SMS logged (Twilio not configured - running in development mode)',
        'mode' => 'development'
      ];
    }

    // Clean phone number (remove spaces, dashes, etc.)
    $cleanedNumber = $this->cleanPhoneNumber($contactNo);

    // ═══════════════════════════════════════════════════════════════
    // ACTIVE SMS PROVIDER: TWILIO
    // ═══════════════════════════════════════════════════════════════
    // The line below sends SMS via Twilio API
    // Credentials are loaded from .env file (TWILIO_*)

    return $this->sendViaTwilio($cleanedNumber, $message);

    // ═══════════════════════════════════════════════════════════════
    // ALTERNATIVE PROVIDERS (currently disabled):
    // ═══════════════════════════════════════════════════════════════
    // SEMAPHORE SMS (Philippines) - Uncomment to use
    // return $this->sendViaSemaphore($cleanedNumber, $message);

    // Generic REST API
    // return $this->sendViaGenericAPI($cleanedNumber, $message);
  }

  /**
   * Send SMS via Semaphore API (Philippines)
   * Docs: https://semaphore.co/docs
   */
  private function sendViaSemaphore($phoneNumber, $message)
  {
    $url = 'https://api.semaphore.co/api/v4/messages';

    $data = [
      'apikey' => $this->apiKey,
      'number' => $phoneNumber,
      'message' => $message,
      'sendername' => $this->senderId
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
      return [
        'success' => true,
        'message' => 'SMS sent successfully',
        'response' => json_decode($response, true)
      ];
    } else {
      return [
        'success' => false,
        'message' => 'Failed to send SMS',
        'response' => $response,
        'http_code' => $httpCode
      ];
    }
  }

  /**
   * Send WhatsApp message via Twilio API
   * Docs: https://www.twilio.com/docs/whatsapp
   * Works on trial accounts without geographic restrictions!
   */
  private function sendViaTwilio($phoneNumber, $message)
  {
    $accountSid = EnvHelper::get('TWILIO_ACCOUNT_SID');
    $authToken = EnvHelper::get('TWILIO_AUTH_TOKEN');

    // For WhatsApp, we use Twilio's sandbox number
    // Get from: https://console.twilio.com/us1/develop/sms/try-it-out/whatsapp-learn
    $fromWhatsApp = EnvHelper::get('TWILIO_WHATSAPP_FROM', 'whatsapp:+14155238886');

    $url = "https://api.twilio.com/2010-04-01/Accounts/$accountSid/Messages.json";

    // Format numbers for WhatsApp (must have 'whatsapp:' prefix)
    $data = [
      'From' => $fromWhatsApp,  // WhatsApp sandbox number
      'To' => 'whatsapp:' . $phoneNumber,  // Recipient's WhatsApp
      'Body' => $message
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$accountSid:$authToken");

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode >= 200 && $httpCode < 300) {
      return [
        'success' => true,
        'message' => 'WhatsApp message sent successfully via Twilio',
        'response' => json_decode($response, true)
      ];
    } else {
      return [
        'success' => false,
        'message' => 'Failed to send WhatsApp message via Twilio',
        'response' => $response,
        'http_code' => $httpCode
      ];
    }
  }

  /**
   * Clean phone number format
   * Converts to E.164 format required by Twilio (+639xxxxxxxxx)
   * Philippine format: 09171234567 → +639171234567
   */
  private function cleanPhoneNumber($phoneNumber)
  {
    // Remove all non-numeric characters
    $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);

    // Handle Philippine mobile numbers
    // Remove leading 0 (e.g., 09171234567 → 9171234567)
    if (substr($cleaned, 0, 1) === '0' && strlen($cleaned) == 11) {
      $cleaned = substr($cleaned, 1); // Now it's 10 digits: 9171234567
    }

    // Add country code if not present
    // Philippine numbers after removing leading 0 should be 10 digits starting with 9
    if (substr($cleaned, 0, 2) !== '63' && strlen($cleaned) == 10) {
      $cleaned = '63' . $cleaned; // Now: 639171234567
    }

    // Return in E.164 format with + prefix
    return '+' . $cleaned; // Final: +639171234567
  }

  /**
   * Log SMS to file for debugging
   */
  private function logSMS($contactNo, $message)
  {
    $logDir = __DIR__ . '/../../logs';
    if (!is_dir($logDir)) {
      mkdir($logDir, 0755, true);
    }

    $logFile = $logDir . '/sms_log.txt';
    $timestamp = date('Y-m-d H:i:s');

    $logEntry = "[$timestamp] To: $contactNo\n";
    $logEntry .= "Message: $message\n";
    $logEntry .= str_repeat('-', 50) . "\n";

    file_put_contents($logFile, $logEntry, FILE_APPEND);
  }

  /**
   * Send booking cancellation SMS
   */
  public function sendBookingCancellationSMS($contactNo, $clientName, $propertyTitle)
  {
    $message = "Hi $clientName,\n\n";
    $message .= "Your booking for $propertyTitle has been cancelled.\n\n";
    $message .= "If you have any questions, please contact us.\n";
    $message .= "Thank you!";

    return $this->sendSMS($contactNo, $message);
  }

  /**
   * Send custom SMS
   */
  public function sendCustomSMS($contactNo, $message)
  {
    return $this->sendSMS($contactNo, $message);
  }
}
