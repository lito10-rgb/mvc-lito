<?php
header('Content-Type: text/plain');
$host = 'localhost';
$db   = 'cafeperu_26';
$user = 'cafeperu_cafeperuano';
$pass = 'pelota10*';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    echo "Database connection SUCCESS!";
} catch (\PDOException $e) {
    echo "Database connection FAILED: " . $e->getMessage();
}
