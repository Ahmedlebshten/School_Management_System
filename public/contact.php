<?php
set_time_limit(15); // 15 second timeout for email sending
require_once __DIR__ . '/../app/bootstrap.php';

use App\Classes\EmailService;

$message_sent = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $subject = htmlspecialchars($_POST['subject'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    // Validate inputs
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email address";
    } else {
        // Send email using EmailService
        try {
            $emailService = new EmailService();
            if ($emailService->sendContactEmail($name, $email, $subject, $message)) {
                $message_sent = true;
            } else {
                $error_message = $emailService->getError();
            }
        } catch (Exception $e) {
            $error_message = "Failed to send email: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/style.css">
    <link rel="stylesheet" href="/assets/style-contact.css">
    <title>Contact Us - School System</title>
</head>
<body>
    <?php require __DIR__ . '/../app/templates/nav.html'; ?>

    <div class="container">
        <div class="section">
            <h2>Contact Us</h2>
            <p>We would love to hear from you! Please fill out the form below or contact us using the information provided. Our team is here to assist you with any inquiries.</p>
        </div>

        <div class="section">
            <h2>Get in Touch</h2>
            <div class="contact-info">
                <h3>Address</h3>
                <p>Egypt</p>

                <h3>Phone</h3>
                <p>+020 102-1734-362</p>

                <h3>Email</h3>
                <p>ahmedlebshtenlebshten@gmail.com</p>
            </div>
        </div>

        <div class="section">
            <h2>Contact Form</h2>

            <?php if ($message_sent): ?>
                <div class="success-message">
                    <strong>Success!</strong> Your message has been sent successfully. We'll get back to you soon!
                </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <strong>Error!</strong> <?= $error_message ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/script.js"></script>
</body>
</html>
