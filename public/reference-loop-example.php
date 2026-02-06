<?php
/**
 * REFERENCE: Proper Way to Display ALL Rows from Table
 * 
 * This shows how the HTML should loop through and display
 * all rows returned from the database
 */

require_once __DIR__ . '/app/bootstrap.php';

use App\Config\Database;

$db = Database::getInstance();

// Simulate Student 1 accessing marks (from ahmed table)
$table_name = "ahmed";

// Query ALL rows from table
$sql = "SELECT * FROM `" . $table_name . "`";
$stmt = $db->prepare($sql);
$stmt->execute();
$all_marks = $stmt->fetchAll(\PDO::FETCH_ASSOC);

// Calculate total
$total_marks = array_sum(array_column($all_marks, 'marks')) ?? 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display ALL Marks - Loop Example</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        h1 { color: #333; }
        .info { background: #f0f0f0; padding: 10px; border-radius: 5px; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .total { background-color: #e8f4f8; font-weight: bold; }
        .status { color: green; font-weight: bold; }
    </style>
</head>
<body>

<h1>📊 All Marks Display - Loop Example</h1>

<div class="info">
    <p><strong>Table Queried:</strong> <code><?php echo htmlspecialchars($table_name); ?></code></p>
    <p><strong>Total Rows in Database:</strong> <span class="status"><?php echo count($all_marks); ?> rows</span></p>
    <p><strong>Query Used:</strong> <code>SELECT * FROM `<?php echo htmlspecialchars($table_name); ?>`</code></p>
</div>

<h2>Marks Table</h2>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>ID</th>
            <th>Subject</th>
            <th>Marks</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if (empty($all_marks)) {
            echo '<tr><td colspan="4" style="text-align: center; color: red;">No data found</td></tr>';
        } else {
            // PROPER LOOP: Display each row
            $row_number = 0;
            foreach ($all_marks as $mark) {
                $row_number++;
                ?>
                <tr>
                    <td><?php echo $row_number; ?></td>
                    <td><?php echo htmlspecialchars($mark['id'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($mark['subject'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($mark['marks'] ?? 'N/A'); ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr class="total">
            <td colspan="3">TOTAL MARKS</td>
            <td><?php echo htmlspecialchars($total_marks); ?></td>
        </tr>
    </tfoot>
</table>

<div class="info">
    <h3>✓ How This Works:</h3>
    <ol>
        <li><strong>Query:</strong> <code>SELECT * FROM table_name</code> (NO WHERE clause)</li>
        <li><strong>Fetch:</strong> <code>fetchAll()</code> returns array of ALL rows</li>
        <li><strong>Loop:</strong> <code>foreach ($all_marks as $mark)</code> iterates through each row</li>
        <li><strong>Display:</strong> Each row is displayed in HTML table</li>
    </ol>
</div>

<div class="info" style="background: #fffde7; border-left: 4px solid #fbc02d;">
    <h3>🔍 Debugging Info:</h3>
    <p>Row count in array: <strong><?php echo count($all_marks); ?></strong></p>
    <p>Loop iterations: <strong><?php echo $row_number ?? 0; ?></strong></p>
    <p>Total marks sum: <strong><?php echo $total_marks; ?></strong></p>
</div>

</body>
</html>
