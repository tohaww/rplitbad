<?php
// Simple cache clear script - DELETE THIS FILE AFTER USE for security
// Access via: https://yourdomain.com/clear-cache.php

// Set timeout
set_time_limit(300);

echo "<pre>";
echo "Clearing Laravel caches...\n\n";

// Navigate to Laravel root (one level up from public)
chdir(__DIR__ . '/..');

// Run artisan commands
$commands = [
    'php artisan view:clear',
    'php artisan cache:clear',
    'php artisan config:clear',
    'php artisan route:clear',
];

foreach ($commands as $command) {
    echo "Running: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo $output . "\n";
}

echo "\nCache cleared successfully!\n";
echo "⚠️ IMPORTANT: Delete this file (clear-cache.php) after use for security.\n";
echo "</pre>";

