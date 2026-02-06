/**
 * Application Scripts
 */

// Initialize EmailJS
document.addEventListener('DOMContentLoaded', function() {
    if (typeof emailjs !== 'undefined') {
        emailjs.init({
            publicKey: "XYoEFevYgZhus3FDd",
        });
    }
});

/**
 * Handle contact form submission
 */
const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        sendContactEmail();
    });
}

/**
 * Send contact email via EmailJS
 */
function sendContactEmail() {
    const params = {
        from_name: document.getElementById("name").value,
        email_id: document.getElementById("email").value,
        subject: document.getElementById("subject").value,
        message: document.getElementById("message").value,
    };

    emailjs.send("service_5kbhtg8", "template_zcta5b8", params)
        .then(function(response) {
            alert("Email Sent Successfully!");
            document.getElementById('contactForm').reset();
        }, function(error) {
            alert("Failed to send email. Please try again.");
            console.error("EmailJS error:", error);
        });
}

/**
 * Handle footer scroll animation
 */
const footer = document.getElementById('footer');
if (footer) {
    let lastScrollTop = 0;

    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        if (currentScroll > lastScrollTop) {
            // Scrolling down
            footer.style.transform = 'translateY(100%)';
        } else {
            // Scrolling up
            footer.style.transform = 'translateY(0)';
        }

        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
    });
}

/**
 * Utility: Format date
 */
function formatDate(date) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(date).toLocaleDateString('en-US', options);
}

/**
 * Utility: Show notification
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => notification.remove(), 3000);
}
