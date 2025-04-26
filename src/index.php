<?php 

require(__DIR__ . '/auth.php');

// Fetch session data
$student_data = $_SESSION['student_data'];
$student_marks = $_SESSION['student_marks'];
$total_marks = $_SESSION['total_marks'] ?? 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/style-index.css">
  <title>Student Information</title>
</head>

<body>
  <?= require(__DIR__ . '/nav.html');?>

  <h1>Student Information</h1>
  <p>Student Name: <?= htmlspecialchars($student_data['name']) ?></p>
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
