<?php

require_once __DIR__ . '/env-helper.php';

/**
 * Email Helper Class
 * Handles sending email notifications for booking approvals via SendGrid
 * 
 * PROVIDER: SendGrid (Free Tier - 100 emails/day)
 * Configure in .env file:
 *  - SENDGRID_API_KEY
 *  - SENDGRID_FROM_EMAIL
 *  - SENDGRID_FROM_NAME
 * 
 * Benefits of Email:
 *  - FREE 100 emails/day forever
 *  - Professional looking
 *  - Can be printed/saved
 *  - No restrictions
 *  - No signup required from recipients
 */
class EmailHelper
{
  private $apiKey;
  private $fromEmail;
  private $fromName;

  public function __construct()
  {
    // Load configuration from environment variables
    $this->apiKey = EnvHelper::get('SENDGRID_API_KEY', '');
    $this->fromEmail = EnvHelper::get('SENDGRID_FROM_EMAIL', 'noreply@bookingsystem.com');
    $this->fromName = EnvHelper::get('SENDGRID_FROM_NAME', 'StaySmart');
  }

  /**
   * Send booking approval email
   */
  public function sendBookingApprovalEmail($emailAcc, $clientName, $propertyTitle, $checkInDate, $checkOutDate, $totalAmount)
  {
    try {
      // Format the email content
      $subject = "Booking Approved - $propertyTitle";
      $htmlContent = $this->formatBookingApprovalHTML(
        $clientName,
        $propertyTitle,
        $checkInDate,
        $checkOutDate,
        $totalAmount
      );

      $plainTextContent = $this->formatBookingApprovalPlainText(
        $clientName,
        $propertyTitle,
        $checkInDate,
        $checkOutDate,
        $totalAmount
      );

      // Send the email
      return $this->sendEmail($emailAcc, $clientName, $subject, $htmlContent, $plainTextContent);
    } catch (Exception $e) {
      error_log("Email Error: " . $e->getMessage());
      return [
        'success' => false,
        'message' => $e->getMessage()
      ];
    }
  }

  /**
   * Send email via SendGrid API
   */
  private function sendEmail($emailAcc, $toName, $subject, $htmlContent, $plainTextContent)
  {
    // Log the email (for development/debugging)
    $this->logEmail($emailAcc, $subject, $plainTextContent);

    // If API key is not configured, return mock success (development mode)
    if (empty($this->apiKey)) {
      return [
        'success' => true,
        'message' => 'Email logged (SendGrid not configured - running in development mode)',
        'mode' => 'development'
      ];
    }

    // SendGrid API endpoint
    $url = 'https://api.sendgrid.com/v3/mail/send';

    // Prepare email data (SendGrid v3 API format)
    $data = [
      'personalizations' => [
        [
          'to' => [
            [
              'email' => $emailAcc,
              'name' => $toName
            ]
          ],
          'subject' => $subject
        ]
      ],
      'from' => [
        'email' => $this->fromEmail,
        'name' => $this->fromName
      ],
      'content' => [
        [
          'type' => 'text/plain',
          'value' => $plainTextContent
        ],
        [
          'type' => 'text/html',
          'value' => $htmlContent
        ]
      ]
    ];

    // Send via cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer ' . $this->apiKey,
      'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode >= 200 && $httpCode < 300) {
      return [
        'success' => true,
        'message' => 'Email sent successfully via SendGrid',
        'response' => $response
      ];
    } else {
      return [
        'success' => false,
        'message' => 'Failed to send email via SendGrid',
        'response' => $response,
        'http_code' => $httpCode
      ];
    }
  }

  /**
   * Format booking approval email as HTML
   */
  private function formatBookingApprovalHTML($clientName, $propertyTitle, $checkInDate, $checkOutDate, $totalAmount)
  {
    // Format dates for display
    $checkIn = date('M d, Y', strtotime($checkInDate));
    $checkOut = date('M d, Y', strtotime($checkOutDate));

    // Format amount
    $amount = number_format($totalAmount, 2);

    // Beautiful HTML email template
    return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .success-badge { background: #10b981; color: white; padding: 8px 16px; border-radius: 20px; display: inline-block; font-weight: bold; margin: 10px 0; }
        .booking-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .detail-label { font-weight: bold; color: #666; }
        .detail-value { color: #333; }
        .total-amount { background: #667eea; color: white; padding: 15px; border-radius: 8px; text-align: center; font-size: 24px; font-weight: bold; margin: 20px 0; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Booking Confirmed!</h1>
            <div class="success-badge">✓ APPROVED</div>
        </div>
        <div class="content">
            <p>Hi <strong>{$clientName}</strong>,</p>
            <p>Great news! Your booking has been <strong>approved</strong> and confirmed.</p>
            
            <div class="booking-details">
                <h2 style="margin-top: 0; color: #667eea;">📋 Booking Details</h2>
                
                <div class="detail-row">
                    <span class="detail-label">🏠 Property:</span>
                    <span class="detail-value">{$propertyTitle}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">📅 Check-in:</span>
                    <span class="detail-value">{$checkIn}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">📅 Check-out:</span>
                    <span class="detail-value">{$checkOut}</span>
                </div>
            </div>
            
            <div class="total-amount">
                💰 Total Amount: PHP {$amount}
            </div>
            
            <p>We're excited to host you! If you have any questions, please don't hesitate to contact us.</p>
            
            <p><strong>Thank you for choosing us!</strong></p>
        </div>
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
HTML;
  }

  /**
   * Format booking approval email as plain text (fallback)
   */
  private function formatBookingApprovalPlainText($clientName, $propertyTitle, $checkInDate, $checkOutDate, $totalAmount)
  {
    $checkIn = date('M d, Y', strtotime($checkInDate));
    $checkOut = date('M d, Y', strtotime($checkOutDate));
    $amount = number_format($totalAmount, 2);

    return <<<TEXT
BOOKING APPROVED!

Hi {$clientName},

Your booking has been approved and confirmed!

BOOKING DETAILS:
================
Property: {$propertyTitle}
Check-in: {$checkIn}
Check-out: {$checkOut}
Total Amount: PHP {$amount}

Thank you for choosing us!

---
This is an automated message. Please do not reply.
TEXT;
  }

  /**
   * Log email to file for debugging
   */
  private function logEmail($toEmail, $subject, $content)
  {
    $logDir = __DIR__ . '/../../logs';
    if (!file_exists($logDir)) {
      mkdir($logDir, 0777, true);
    }

    $logFile = $logDir . '/email_log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "\n" . str_repeat('-', 50) . "\n";
    $logEntry .= "Timestamp: {$timestamp}\n";
    $logEntry .= "To: {$toEmail}\n";
    $logEntry .= "Subject: {$subject}\n";
    $logEntry .= "Content:\n{$content}\n";
    $logEntry .= str_repeat('-', 50) . "\n";

    file_put_contents($logFile, $logEntry, FILE_APPEND);
  }
}
