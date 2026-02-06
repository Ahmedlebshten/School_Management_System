<?php
require_once __DIR__ . '/../app/bootstrap.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/style.css">
    <link rel="stylesheet" href="/assets/style-home.css">
    <title>School System - Home</title>
</head>
<body>
    <?php require __DIR__ . '/../app/templates/nav.html'; ?>

    <div class="container">
        <div class="welcome-message">
            <h1>Welcome to Our School System</h1>
            <p>Your gateway to managing student information and school data.</p>
        </div>

        <div class="info">
            <h2>What We Offer</h2>
            <p>Our system provides an efficient way to manage student records, grades, and class information. Whether you are a student, teacher, or administrator, our platform helps you stay organized and informed.</p>
            <a href="login.php" class="btn btn-primary">View Your Results</a>
        </div>
    </div>
</body>
</html>
