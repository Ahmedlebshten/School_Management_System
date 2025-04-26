// For Scrolling

let lastScrollTop = 0;
const footer = document.getElementById('footer');

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

// For Sending Mail

function sendMail() {
  let params = {
    from_name: document.getElementById("name").value,
    email_id: document.getElementById("email").value,
    message: document.getElementById("message").value,
  };
  emailjs
    .send("service_5kbhtg8", "template_zcta5b8", params)
    .then(alert("Email Sent Successfully!"));
}
