<?php

namespace App\Classes;

use App\Config\Database;
use PDO;
use Exception;

/**
 * StudentData Class
 * 
 * Fetches LIVE student data directly from MySQL database.
 * - No caching
 * - No mock data
 * - No hardcoded values
 * - Fresh SQL query on every method call
 */
class StudentData
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get student information by ID
     * Queries the database LIVE on every call
     */
    public function getStudentInfo(int $student_id): ?array
    {
        if ($student_id <= 0) {
            throw new Exception("Invalid student ID");
        }

        $sql = "SELECT id, name, class FROM student_data WHERE id = :student_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Get ALL student marks from the appropriate table
     * 
     * Rules:
     * - Student ID = 1 AND Class = "first" → Query ahmed table
     * - Student ID = 2 AND Class = "second" → Query mohamed table
     * - Otherwise → Query student_data table
     * 
     * RETURNS ALL ROWS from the table, loops through each row
     */
    public function getStudentMarks(int $student_id, string $class): array
    {
        if ($student_id <= 0) {
            throw new Exception("Invalid student ID");
        }

        if (empty($class)) {
            throw new Exception("Class cannot be empty");
        }

        // Determine which table to query
        $table = $this->selectTable($student_id, strtolower(trim($class)));

        // Execute LIVE query against database - NO WHERE CLAUSE
        $sql = "SELECT * FROM `" . $table . "` ORDER BY subject ASC";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute marks query for table '{$table}'");
        }

        // Fetch ALL rows from the table
        $all_marks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($all_marks)) {
            throw new Exception("No marks found in table '{$table}'");
        }

        // Loop through all rows and verify we have data
        $marks_array = [];
        foreach ($all_marks as $row) {
            $marks_array[] = [
                'id' => $row['id'] ?? null,
                'subject' => $row['subject'] ?? null,
                'marks' => $row['marks'] ?? null
            ];
        }

        return $marks_array;
    }

    /**
     * Get ALL rows from a specific marks table
     * Useful for displaying complete table data
     * 
     * @param string $class Class name (first, second, etc)
     * @return array All marks from the selected table
     */
    public function getAllMarksFromClass(string $class): array
    {
        if (empty($class)) {
            throw new Exception("Class cannot be empty");
        }

        // Determine table based on class
        $table = 'student_data'; // default
        
        if (strtolower(trim($class)) === 'first') {
            $table = 'ahmed';
        } elseif (strtolower(trim($class)) === 'second') {
            $table = 'mohamed';
        }

        // Query ALL rows from the table
        $sql = "SELECT * FROM `" . $table . "` ORDER BY subject ASC";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to fetch marks from table '{$table}'");
        }

        // Fetch all rows
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($rows)) {
            throw new Exception("No data found in table '{$table}'");
        }

        // Build complete array with all rows
        $all_marks = [];
        foreach ($rows as $row) {
            $all_marks[] = $row;
        }

        return $all_marks;
    }

    /**
     * Get ALL rows from a specific marks table
     * Executes fresh database queries
     */
    public function getCompleteStudentData(int $student_id, string $class): array
    {
        // Get student info
        $student_info = $this->getStudentInfo($student_id);

        if (!$student_info) {
            throw new Exception("Student ID {$student_id} not found in database");
        }

        // Verify class matches
        if (strtolower(trim($student_info['class'])) !== strtolower(trim($class))) {
            throw new Exception("Student ID {$student_id} does not belong to class '{$class}'");
        }

        // Get student marks
        $marks = $this->getStudentMarks($student_id, $class);

        if (empty($marks)) {
            throw new Exception("No marks found for student {$student_id}");
        }

        // Return combined data
        return [
            'student_id' => (int)$student_info['id'],
            'student_name' => $student_info['name'],
            'student_class' => $student_info['class'],
            'marks' => $marks,
            'total_marks' => (int)array_sum(array_column($marks, 'marks')),
            'subjects_count' => count($marks)
        ];
    }

    /**
     * Determine target table based on student ID and class
     * 
     * RULES:
     * - Student ID = 1 AND Class = "first" → ahmed
     * - Student ID = 2 AND Class = "second" → mohamed
     * - Otherwise → student_data (fallback)
     */
    private function selectTable(int $student_id, string $class_normalized): string
    {
        if ($student_id === 1 && $class_normalized === 'first') {
            return 'ahmed';
        }

        if ($student_id === 2 && $class_normalized === 'second') {
            return 'mohamed';
        }

        return 'student_data';
    }

    /**
     * Search students by name
     * Returns fresh data from database
     */
    public function searchStudents(string $name_query): array
    {
        $sql = "SELECT id, name, class FROM student_data WHERE name LIKE :name ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', '%' . $name_query . '%', PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all students from database
     * Fresh query on every call
     */
    public function getAllStudents(): array
    {
        $sql = "SELECT id, name, class FROM student_data ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Add new student (Insert into database)
     * Returns the inserted data
     */
    public function addStudent(int $id, string $name, string $class): array
    {
        // Validate input
        if ($id <= 0) {
            throw new Exception("Student ID must be positive");
        }

        if (empty($name)) {
            throw new Exception("Student name is required");
        }

        if (empty($class)) {
            throw new Exception("Class is required");
        }

        // Check if student already exists
        $existing = $this->getStudentInfo($id);
        if ($existing) {
            throw new Exception("Student ID {$id} already exists");
        }

        // Insert into database
        $sql = "INSERT INTO student_data (id, name, class) VALUES (:id, :name, :class)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':class', $class, PDO::PARAM_STR);
        $stmt->execute();

        // Return the inserted data
        return $this->getStudentInfo($id);
    }

    /**
     * Update student information
     * Returns updated data from database
     */
    public function updateStudent(int $id, string $name, string $class): array
    {
        // Verify student exists
        $existing = $this->getStudentInfo($id);
        if (!$existing) {
            throw new Exception("Student ID {$id} not found");
        }

        // Update in database
        $sql = "UPDATE student_data SET name = :name, class = :class WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':class', $class, PDO::PARAM_STR);
        $stmt->execute();

        // Return updated data
        return $this->getStudentInfo($id);
    }

    /**
     * Delete student
     */
    public function deleteStudent(int $id): bool
    {
        // Verify student exists
        $existing = $this->getStudentInfo($id);
        if (!$existing) {
            throw new Exception("Student ID {$id} not found");
        }

        $sql = "DELETE FROM student_data WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}

?>
