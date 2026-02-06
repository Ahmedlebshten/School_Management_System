<?php
// Router script for PHP development server
// Serves static files and routes requests to public/

if (preg_match('/\.(?:js|css|gif|jpg|jpeg|png|svg|ico|woff|woff2|ttf|eot)$/', $_SERVER["REQUEST_URI"])) {
    // Static files - serve from public folder
    $file = __DIR__ . '/public' . $_SERVER["REQUEST_URI"];
    if (file_exists($file)) {
        return false; // Serve the actual file
    }
}

// Route all requests through public/index.php
require_once(__DIR__ . '/public/index.php');
?>
