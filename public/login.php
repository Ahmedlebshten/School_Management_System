<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Classes\Auth;

$auth = new Auth();
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $class = trim($_POST['class'] ?? '');

    $result = $auth->login($id, $class);

    if ($result['success']) {
        header("Location: index.php");
        exit();
    } else {
        $error_message = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/style.css">
    <link rel="stylesheet" href="/assets/style-login.css">
    <title>Login</title>
</head>
<body>
    <?php require __DIR__ . '/../app/templates/nav.html'; ?>
    
    <div class="login-container">
        <h2>Student Login</h2>
        
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="form-group">
                <label for="id">Student ID:</label>
                <input 
                    type="number" 
                    name="id" 
                    id="id" 
                    required 
                    min="1"
                    placeholder="Enter your student ID"
                >
            </div>

            <div class="form-group">
                <label for="class">Class:</label>
                <select name="class" id="class" required>
                    <option value="">Select Class</option>
                    <option value="first">First</option>
                    <option value="second">Second</option>
                    <option value="third">Third</option>
                    <option value="fourth">Fourth</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <p class="login-link">Don't have an account? <a href="home.php">Go Home</a></p>
    </div>
</body>
</html>
