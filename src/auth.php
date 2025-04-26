<?php
session_start();

// Check if the user is logged in by checking the session
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // If not logged in, redirect to login page
    header("Location:login.php");
    exit();
}
