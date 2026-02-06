<?php

/**
 * Email Configuration
 * 
 * Supports multiple mail drivers:
 * - sendmail: Uses system sendmail (default for local development)
 * - smtp: Uses SMTP server (Gmail, Mailtrap, etc.)
 */

return [
    'driver' => 'smtp',  // Use SMTP driver
    'from' => [
        'name' => 'School Management System',
        'address' => 'no-reply@school.local'
    ],
    'to' => [
        'address' => 'ahmedlebshtenlebshten@gmail.com',
        'name' => 'School Admin'
    ],
    
    // SMTP Configuration (Mailtrap)
    'smtp' => [
        'host' => 'sandbox.smtp.mailtrap.io',
        'port' => 587,
        'username' => 'd375cca0275be7',
        'password' => 'e314ef7d2b77a8',
        'encryption' => 'tls',
    ],
];

