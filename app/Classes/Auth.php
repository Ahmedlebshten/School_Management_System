<?php

namespace App\Classes;

use App\Config\Database;
use PDO;

class Auth
{
    private PDO $db;
    private StudentData $studentData;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->studentData = new StudentData();
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
    }

    /**
     * Require authentication, redirect to login if not authenticated
     */
    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            header("Location: login.php");
            exit();
        }
    }

    /**
     * Login user with student ID and class
     * Fetches LIVE data from database on every login attempt
     */
    public function login(int $id, string $class): array
    {
        $response = ['success' => false, 'message' => ''];

        if (empty($id) || empty($class)) {
            $response['message'] = "All fields are required";
            return $response;
        }

        try {
            // Validate student ID
            if ($id <= 0) {
                $response['message'] = "Invalid student ID";
                return $response;
            }

            // Get student info from database (LIVE query)
            $student_info = $this->studentData->getStudentInfo($id);

            if (!$student_info) {
                $response['message'] = "No student found with this ID";
                return $response;
            }

            // Verify class matches (case-insensitive)
            $db_class = strtolower(trim($student_info['class']));
            $input_class = strtolower(trim($class));

            if ($db_class !== $input_class) {
                $response['message'] = "The student ID does not belong to the specified class";
                return $response;
            }

            // Get student marks from appropriate table (LIVE query)
            $student_marks = $this->studentData->getStudentMarks($id, $input_class);

            if (empty($student_marks)) {
                $response['message'] = "No marks found for this student";
                return $response;
            }

            // Store only essential authentication info in session
            $_SESSION['student_id'] = (int)$student_info['id'];
            $_SESSION['student_name'] = $student_info['name'];
            $_SESSION['student_class'] = $student_info['class'];
            $_SESSION['loggedin'] = true;
            $_SESSION['login_timestamp'] = date('Y-m-d H:i:s');

            $response['success'] = true;
            $response['message'] = "Login successful";

        } catch (\Exception $e) {
            $response['message'] = "Error: " . $e->getMessage();
        }

        return $response;
    }

    /**
     * Conditional table selection based on Student ID and Class
     * 
     * Rules:
     * - If Student ID = 1 AND Class = "first" → Return table: ahmed
     * - If Student ID = 2 AND Class = "second" → Return table: mohamed
     * - Otherwise → Return student_data (fallback)
     */
    private function getTableForStudentAndClass(int $student_id, string $class_lower): ?string
    {
        // Rule 1: Student ID = 1 AND Class = "first"
        if ($student_id === 1 && $class_lower === 'first') {
            return 'ahmed';
        }
        
        // Rule 2: Student ID = 2 AND Class = "second"
        if ($student_id === 2 && $class_lower === 'second') {
            return 'mohamed';
        }
        
        // Default fallback table
        return 'student_data';
    }

    /**
     * Map class to table name (legacy function kept for backward compatibility)
     */
    private function getTableForClass(string $class): ?string
    {
        // Map class names to your table names
        $classMap = [
            'first' => 'ahmed',
            'second' => 'mohamed',
            'third' => 'ahmed',      // Adjust as needed
            'fourth' => 'mohamed',   // Adjust as needed
        ];

        $class_lower = strtolower($class);
        return $classMap[$class_lower] ?? null;
    }

    /**
     * Logout user
     */
    public static function logout(): void
    {
        session_destroy();
        header("Location: home.php");
        exit();
    }
}
