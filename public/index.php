<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Classes\Auth;

Auth::requireLogin();

// Get student ID from session
$student_id = $_SESSION['student_id'];
$student_class = $_SESSION['student_class'];

// ============================================
// DIRECT PDO CONNECTION (Same as test-db.php)
// ============================================
try {
    $pdo = new PDO('mysql:host=db;dbname=school', 'root', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch fresh student data from database
$sql = "SELECT * FROM student_data WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $student_id, PDO::PARAM_INT);
$stmt->execute();
$student_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Determine table name based on class
$classMap = [
    'first' => 'ahmed',
    'second' => 'mohamed',
];
$table_name = $classMap[strtolower($student_class)] ?? 'student_data';

// ============================================
// FETCH ALL MARKS FROM TABLE (DIRECT QUERY)
// ============================================
$result = $pdo->query('SELECT * FROM ' . $table_name);
$student_marks = $result->fetchAll(PDO::FETCH_ASSOC);

// Calculate total marks
$total_marks = 0;
foreach ($student_marks as $mark) {
    if (isset($mark['marks'])) {
        $total_marks += (int)$mark['marks'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/style.css">
    <link rel="stylesheet" href="/assets/style-index.css">
    <title>Student Information</title>
</head>
<body>
    <?php require __DIR__ . '/../app/templates/nav.html'; ?>

    <div class="container">
        <h1>Student Information</h1>
        
        <div class="student-info">
            <p><strong>Name:</strong> <?= htmlspecialchars($student_data['name'] ?? '') ?></p>
            <p><strong>ID:</strong> <?= htmlspecialchars($student_data['id'] ?? '') ?></p>
            <p><strong>Class:</strong> <?= htmlspecialchars($student_data['class'] ?? '') ?></p>
        </div>

        <h2>Marks (<?php echo count($student_marks); ?> subjects)</h2>
        
        <table class="marks-table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($student_marks as $mark): ?>
                <tr>
                    <td><?= htmlspecialchars($mark['subject'] ?? '') ?></td>
                    <td><?= htmlspecialchars($mark['marks'] ?? '') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="total-marks"><strong>Total Marks:</strong> <?= htmlspecialchars($total_marks) ?> out of 400</p>

        <div class="actions">
            <a class="btn btn-primary" href="download_result.php">Download Result</a>
            <form action="logout.php" method="POST" style="display: inline;">
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
