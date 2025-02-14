<?php
// Get environment variables or use defaults
$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_DATABASE') ?: 'zeroweb1_namo';
$user = getenv('DB_USERNAME') ?: 'zeroweb1_namo';
$pass = getenv('DB_PASSWORD') ?: 'namopass';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Connection retry logic
$maxTries = 10;
$tries = 0;
$sleepTime = 3; // seconds

while ($tries < $maxTries) {
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        // If connection successful, break the loop
        break;
    } catch (PDOException $e) {
        $tries++;
        if ($tries === $maxTries) {
            // If all retries failed, throw the error
            throw new PDOException("Database connection failed after $maxTries attempts: " . $e->getMessage());
        }
        // Wait before trying again
        sleep($sleepTime);
    }
} 