<?php 
session_start();
include 'connection.php'; 

$error_message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null; 
    $class = $_POST['class'] ?? null;

    // Determine the correct table based on the ID
    if ($id == 1) {
        $table_name = 'ahmed';
    } elseif ($id == 2) {
        $table_name = 'mohamed';
    } else {
        $error_message = "All Feilds Are Required";
    }

    if (empty($error_message)) {
        // fetch personal data from student_data table
        $sql = "SELECT * FROM student_data WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $student_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if student data is found
        if ($student_data) {
            // Log the retrieved class for debugging
            error_log("DB Class: " . $student_data['class']);
            error_log("Submitted Class: " . $class);

            // Compare the student's class with the submitted class
            if (trim(strtolower($student_data['class'])) !== trim(strtolower($class))) {
                $error_message = "Error: The student ID does not belong to the specified class.";
            } else {
                // Query to fetch all data from the selected table
                $sql = "SELECT * FROM $table_name";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $student_marks = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Calculate total marks
                $total_marks = array_sum(array_column($student_marks, 'marks'));

                // Store only essential authentication info in session
                $_SESSION['student_id'] = $student_data['id'];
                $_SESSION['student_class'] = $student_data['class'];
                
                // Set the authentication flag
                $_SESSION['loggedin'] = true;

                // Redirect to index.php to display student information
                header("Location:index.php");
                exit();
            }
        } else {
            $error_message = "No results found for this student.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assetes/style-login.css"> 
</head>
<body>
<?= include 'nav.html';?>
<div class="login-container">
    <h2>Login</h2>
    
    <!-- Display error message if any -->
    <?php if (!empty($error_message)): ?>
        <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>
    
    <form action="" method="POST">
        <div class="form-group">
            <label for="id">Student ID:</label>
            <input type="number" name="id" id="id" required>
        </div>

        <div class="form-group">
            <label for="class">Class:</label>
            <select name="class" id="class" required>
                <option value="">Select Class</option>
                <option value="First">First</option>
                <option value="Second">Second</option>
                <option value="Third">Third</option>
                <option value="Foutth">Foutth</option>
            </select>
        </div>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
