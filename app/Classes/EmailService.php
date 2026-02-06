<?php

namespace App\Classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Email Service
 * 
 * Handles email sending with support for multiple drivers:
 * - Sendmail (local development)
 * - SMTP (production, Gmail, Mailtrap, etc.)
 */
class EmailService
{
    private $config;
    private $mail;
    private $error = '';

    public function __construct()
    {
        $this->config = require __DIR__ . '/../Config/email.php';
        $this->mail = new PHPMailer(true);
        $this->configureMail();
    }

    /**
     * Configure PHPMailer based on email driver
     */
    private function configureMail()
    {
        try {
            $driver = $this->config['driver'];

            if ($driver === 'smtp') {
                $this->mail->isSMTP();
                $this->mail->Host = $this->config['smtp']['host'];
                $this->mail->Port = $this->config['smtp']['port'];
                $this->mail->SMTPAuth = true;
                $this->mail->Username = $this->config['smtp']['username'];
                $this->mail->Password = $this->config['smtp']['password'];
                $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $this->mail->Timeout = 10; // 10 second timeout
                $this->mail->SMTPDebug = 0; // Disable debug output
            } else {
                // Use sendmail for local development
                $this->mail->isSendmail();
            }

            // Set default sender
            $this->mail->setFrom(
                $this->config['from']['address'],
                $this->config['from']['name']
            );

        } catch (Exception $e) {
            $this->error = "Mail configuration error: " . $e->getMessage();
        }
    }

    /**
     * Send a contact form email
     * 
     * @param string $senderName Name of the person sending the message
     * @param string $senderEmail Email address of the sender
     * @param string $subject Subject of the message
     * @param string $message The message content
     * @return bool True if sent successfully, false otherwise
     */
    public function sendContactEmail($senderName, $senderEmail, $subject, $message)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearReplyTos();

            // Validate recipient email
            $recipientEmail = trim($this->config['to']['address']);
            if (empty($recipientEmail)) {
                $recipientEmail = 'ahmedlebshtenlebshten@gmail.com';
            }

            // Set recipients
            $this->mail->addAddress(
                $recipientEmail,
                $this->config['to']['name']
            );
            $this->mail->addReplyTo($senderEmail, $senderName);

            // Set email content
            $this->mail->isHTML(true);
            $this->mail->Subject = 'New Contact Form Message: ' . $subject;
            
            // HTML version
            $this->mail->Body = $this->buildHtmlBody($senderName, $senderEmail, $subject, $message);
            
            // Plain text version for non-HTML clients
            $this->mail->AltBody = $this->buildPlainTextBody($senderName, $senderEmail, $subject, $message);

            // Send the email
            if ($this->mail->send()) {
                return true;
            } else {
                $this->error = "Email sending failed: " . $this->mail->ErrorInfo;
                return false;
            }

        } catch (Exception $e) {
            $this->error = "Email error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Build HTML email body
     */
    private function buildHtmlBody($name, $email, $subject, $message)
    {
        return "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #f0f0f0; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
                    .field { margin-bottom: 15px; }
                    .label { font-weight: bold; color: #0066cc; }
                    .message-content { background-color: #f9f9f9; padding: 15px; border-left: 4px solid #0066cc; margin-top: 20px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>New Contact Form Message</h2>
                    </div>
                    <div class='field'>
                        <span class='label'>From:</span> {$name} ({$email})
                    </div>
                    <div class='field'>
                        <span class='label'>Subject:</span> {$subject}
                    </div>
                    <div class='message-content'>
                        <span class='label'>Message:</span>
                        <p>" . nl2br(htmlspecialchars($message)) . "</p>
                    </div>
                </div>
            </body>
            </html>
        ";
    }

    /**
     * Build plain text email body
     */
    private function buildPlainTextBody($name, $email, $subject, $message)
    {
        return "New Contact Form Message\n"
             . "==========================\n\n"
             . "From: {$name} ({$email})\n"
             . "Subject: {$subject}\n\n"
             . "Message:\n"
             . $message;
    }

    /**
     * Get the last error message
     */
    public function getError()
    {
        return $this->error;
    }
}
