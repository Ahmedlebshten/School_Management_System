<?php
/**
 * Application Configuration
 * 
 * This file contains application-wide configuration settings
 */

return [
    // Application
    'app_name' => 'School Management System',
    'app_version' => '2.0.0',
    'timezone' => 'Africa/Cairo',
    
    // Database
    'database' => [
       'host' => getenv('DB_HOST') ?: 'mysql',
       'name' => getenv('DB_NAME') ?: 'school',
       'user' => getenv('DB_USER') ?: 'root',
       'pass' => getenv('DB_PASS') ?: 'root',
       'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
    ],
    
    // Classes
    'classes' => [
        'first' => 'First Year',
        'second' => 'Second Year',
        'third' => 'Third Year',
        'fourth' => 'Fourth Year',
    ],
    
    // Session
    'session' => [
        'lifetime' => 3600,
        'secure_cookies' => false, // Set to true in production with HTTPS
    ],
    
    // Email
    'email' => [
        'service_id' => 'service_5kbhtg8',
        'template_id' => 'template_zcta5b8',
        'public_key' => 'XYoEFevYgZhus3FDd',
    ],
];
