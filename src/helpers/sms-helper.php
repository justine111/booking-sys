<?php

require_once __DIR__ . '/env-helper.php';

/**
 * SMS Helper Class
 * Handles sending SMS notifications for booking approvals
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

    // If API credentials are not configured, return mock success
    if (empty($this->apiKey) || empty($this->apiUrl)) {
      return [
        'success' => true,
        'message' => 'SMS logged (API not configured)',
        'mode' => 'development'
      ];
    }

    // Clean phone number (remove spaces, dashes, etc.)
    $cleanedNumber = $this->cleanPhoneNumber($contactNo);

    // Example implementation for different SMS providers
    // Uncomment and modify based on your SMS provider

    // SEMAPHORE SMS (Philippines)
    return $this->sendViaSemaphore($cleanedNumber, $message);

    // TWILIO SMS (International)
    // return $this->sendViaTwilio($cleanedNumber, $message);

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
   * Send SMS via Twilio API (International)
   * Docs: https://www.twilio.com/docs/sms
   */
  private function sendViaTwilio($phoneNumber, $message)
  {
    $accountSid = EnvHelper::get('TWILIO_ACCOUNT_SID');
    $authToken = EnvHelper::get('TWILIO_AUTH_TOKEN');
    $fromNumber = EnvHelper::get('TWILIO_FROM_NUMBER');

    $url = "https://api.twilio.com/2010-04-01/Accounts/$accountSid/Messages.json";

    $data = [
      'From' => $fromNumber,
      'To' => $phoneNumber,
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
        'message' => 'SMS sent successfully via Twilio',
        'response' => json_decode($response, true)
      ];
    } else {
      return [
        'success' => false,
        'message' => 'Failed to send SMS via Twilio',
        'response' => $response,
        'http_code' => $httpCode
      ];
    }
  }

  /**
   * Clean phone number format
   */
  private function cleanPhoneNumber($phoneNumber)
  {
    // Remove all non-numeric characters
    $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);

    // Add country code if not present (Philippines example)
    if (substr($cleaned, 0, 2) !== '63' && strlen($cleaned) == 10) {
      $cleaned = '63' . $cleaned;
    }

    return $cleaned;
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
