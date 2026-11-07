<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/db.php";

function requireLogin()
{
    if (!isset($_SESSION["account_id"])) {
        header("Location: login.php");
        exit;
    }
}

function getCurrentStudent($conn)
{
    $stmt = $conn->prepare("
        SELECT AccountID, StudentID, StudentName, StudentEmail
        FROM Accounts
        WHERE AccountID = ?
    ");

    $stmt->execute([$_SESSION["account_id"]]);

    return $stmt->fetch();
}
?>
