<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../assetes/style-contact.css">
  <title>Contact Us - School System</title>

</head>

<body>

  <?= require(__DIR__ . '/nav.html'); ?>


  <div class="container">
    <div class="section">
      <h2>Contact Us</h2>
      <p>
        We would love to hear from you! Please fill out the form below or
        contact us using the information provided. Our team is here to assist
        you with any inquiries you may have.
      </p>
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

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <script type="text/javascript">
      src = "https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js" >
    </script>
    <script type="text/javascript">
      (function () {
        emailjs.init({
          publicKey: "XYoEFevYgZhus3FDd",
        });
      })();
    </script>
    <script src="../assets/script.js"></script>


    <div class="section">
      <h2>Contact Form</h2>
      <form>
        <div class="form-group">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" required />
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required />
        </div>
        <div class="form-group">
          <label for="subject">Subject:</label>
          <input type="text" id="subject" name="subject" required />
        </div>
        <div class="form-group">
          <label for="message">Message:</label>
          <textarea id="message" name="message" required></textarea>
        </div>
        <div class="form-group">
          <button type="submit" onclick="sendMail()">Send Message</button>
        </div>
      </form>
    </div>
  </div>



  <script src="assets/script.js"></script>
</body>

</html>