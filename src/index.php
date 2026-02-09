<?php 

require(__DIR__ . '/auth.php');
include 'connection.php';

// Get student ID from session
$student_id = $_SESSION['student_id'];
$student_class = $_SESSION['student_class'];

// Fetch fresh student data from database
$sql = "SELECT * FROM student_data WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $student_id, PDO::PARAM_INT);
$stmt->execute();
$student_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Determine table name based on student ID
if ($student_id == 1) {
    $table_name = 'ahmed';
} elseif ($student_id == 2) {
    $table_name = 'mohamed';
} else {
    $table_name = 'student_marks';
}

// Fetch ALL marks data from database
$sql = "SELECT * FROM $table_name";
$stmt = $db->prepare($sql);
$stmt->execute();
$student_marks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total marks
$total_marks = array_sum(array_column($student_marks, 'marks'));

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/style-index.css">
  <title>Student_Information</title>
</head>

<body>
  <?= require(__DIR__ . '/nav.html');?>

  <h1>Student Information</h1>
  <p>Student Name : <?= htmlspecialchars($student_data['name']) ?></p>
  <p>Student ID: <?= htmlspecialchars($student_data['id']) ?></p>
  <p>Student Class: <?= htmlspecialchars($student_data['class']) ?></p>

  <h2>Marks</h2>
  <table>
    <tr>
      <th>Subject</th>
      <th>Marks</th>
    </tr>
    <?php foreach ($student_marks as $mark): ?>
    <tr>
      <td><?= htmlspecialchars($mark['subject']) ?></td>
      <td><?= htmlspecialchars($mark['marks']) ?></td>
    </tr>
    <?php endforeach; ?>
  </table>

  <p>Total Marks: <?= htmlspecialchars($total_marks) ?> out of 400</p>

  <a class="download-btn" href="download_result.php">Download Result</a>

  <form action="logout.php" method="POST" style="margin-top: 20px;">
      <button type="submit">Logout</button>
  </form>
</body>

</html>
