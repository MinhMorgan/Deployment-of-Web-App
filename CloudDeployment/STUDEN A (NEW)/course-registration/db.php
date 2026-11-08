<?php
$server   = "sql106.infinityfree.com";
$username = "if0_43382164";
$password = "Minh0929688478";
$database = "if0_43382164_midterm";

try {
    $conn = new PDO(
        "mysql:host=$server;dbname=$database;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

} catch (PDOException $e) {
    error_log($e->getMessage());
    die("Database connection failed. Please try again later.");
}
?>
