<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - School System</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            /* Limit the width of the container */
            margin: 20px auto;
            /* Center the container */
            padding: 20px;
            background-color: #ffffff;
            /* White background for content */
            border-radius: 8px;
            /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
        }

        .section {
            margin-bottom: 20px;
            /* Space between sections */
            padding: 15px;
            /* Padding inside each section */
            border-left: 4px solid #3498db;
            /* Blue left border for section emphasis */
        }

        .section h2 {
            color: #3498db;
            /* Header color */
            font-size: 24px;
            /* Header size */
            margin-top: 0;
            /* Remove default top margin */
        }

        .section p {
            line-height: 1.6;
            /* Improve readability */
            color: #333;
            /* Darker text color for better contrast */
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .container {
                padding: 15px;
                /* Less padding on smaller screens */
            }

            .section h2 {
                font-size: 20px;
                /* Smaller font size for headers */
            }
        }
    </style>
</head>

<body>

    <?= require(__DIR__ . '/nav.html'); ?>
    <div class="container">
        <div class="section">
            <h2>About Our School</h2>
            <p>Welcome To our school system. We are dedicated to providing an outstanding educational experience for
                students of all ages. Our mission is to foster a love of learning, to provide a supportive and inclusive
                environment, and to prepare our students for a successful future.</p>
            <p>We believe in a holistic approach to education, combining rigorous academics with extracurricular
                activities, to help students achieve their full potential. Our team of experienced educators is
                committed to nurturing the intellectual, emotional, and social growth of each student.</p>
        </div>

        <div class="section">
            <h2>Our Mission</h2>
            <p>Our mission is to create a nurturing and stimulating environment that fosters academic excellence and
                personal growth. We aim to prepare our students to be lifelong learners and responsible global citizens,
                equipped with the skills and knowledge needed to succeed in an ever-changing world.</p>
        </div>

    </div>



    <script src="../assets/script.js"></script>
</body>

</html>