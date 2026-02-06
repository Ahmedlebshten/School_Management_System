<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Classes\Auth;
use App\Config\Database;

Auth::requireLogin();

// Get database connection
$db = Database::getInstance();

// Get student ID from session
$student_id = $_SESSION['student_id'];
$student_class = $_SESSION['student_class'];

// Fetch fresh student data from database
$sql = "SELECT * FROM student_data WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $student_id, \PDO::PARAM_INT);
$stmt->execute();
$student_data = $stmt->fetch(\PDO::FETCH_ASSOC);

// Determine table name based on class
$classMap = [
    'first' => 'ahmed',
    'second' => 'mohamed',
];
$table_name = $classMap[strtolower($student_class)] ?? 'student_marks';

// DIRECT QUERY - NO FILTERING
$sql = "SELECT * FROM `" . $table_name . "` ORDER BY subject ASC";
$stmt = $db->prepare($sql);

if (!$stmt->execute()) {
    die("Query failed: " . implode(", ", $stmt->errorInfo()));
}

// FETCH ALL ROWS
$student_marks = $stmt->fetchAll(\PDO::FETCH_ASSOC);

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
    <title>Student Information - DEBUG</title>
    <style>
        .debug-info {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-family: monospace;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <?php require __DIR__ . '/../app/templates/nav.html'; ?>

    <div class="container">
        <h1>Student Information [DEBUG MODE]</h1>
        
        <div class="debug-info">
QUERY DEBUG INFO:
─────────────────
Table Name: <?php echo htmlspecialchars($table_name); ?>
Student Class: <?php echo htmlspecialchars($student_class); ?>
SQL Query: SELECT * FROM `<?php echo htmlspecialchars($table_name); ?>` ORDER BY subject ASC
Total Rows Returned: <?php echo count($student_marks); ?>
Total Marks Sum: <?php echo $total_marks; ?>

RAW DATA RETURNED:
<?php echo json_encode($student_marks, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
        </div>
        
        <div class="student-info">
            <p><strong>Name:</strong> <?= htmlspecialchars($student_data['name'] ?? '') ?></p>
            <p><strong>ID:</strong> <?= htmlspecialchars($student_data['id'] ?? '') ?></p>
            <p><strong>Class:</strong> <?= htmlspecialchars($student_data['class'] ?? '') ?></p>
        </div>

        <h2>Marks (<?php echo count($student_marks); ?> rows)</h2>
        <table class="marks-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (empty($student_marks)) {
                    echo '<tr><td colspan="3">No marks found</td></tr>';
                } else {
                    $row_num = 0;
                    foreach ($student_marks as $mark): 
                        $row_num++;
                ?>
                <tr>
                    <td><?php echo $row_num; ?></td>
                    <td><?= htmlspecialchars($mark['subject'] ?? '') ?></td>
                    <td><?= htmlspecialchars($mark['marks'] ?? '') ?></td>
                </tr>
                <?php endforeach; } ?>
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
