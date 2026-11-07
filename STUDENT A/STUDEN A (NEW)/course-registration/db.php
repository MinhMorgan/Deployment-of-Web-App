<?php

$server = "localhost";
$username = "root";
$password = "";
$database = "midterm";

try {

    $conn = new PDO(
        "mysql:host=$server;dbname=$database;charset=utf8mb4",
        $username,
        $password
    );

    $conn->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {

    error_log($e->getMessage());

    die("Database connection failed.");

}
?>