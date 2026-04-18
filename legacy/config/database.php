<?php
// Configuration for Database Connection
$host = 'localhost';
$db   = 'spk_blewah';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // For clean code, we should handle this gracefully in a real app
     // throw new \PDOException($e->getMessage(), (int)$e->getCode());
     error_log($e->getMessage());
     // die("Database connection failed.");
}
?>
