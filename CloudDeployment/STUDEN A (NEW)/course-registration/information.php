<?php
require_once "auth.php";
requireLogin();

$student = getCurrentStudent($conn);

if (!$student) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <h1>Student Information</h1>

    <nav class="navbar">
        <div class="nav-left">
            <a class="nav-button" href="index.php">Home</a>
            <a class="nav-button" href="courses.php">Registration</a>
            <a class="nav-button" href="registered.php">Registered Courses</a>
            <a class="nav-button" href="information.php">Information</a>
        </div>
        <a class="logout-button" href="logout.php">Logout</a>
    </nav>

    <div class="side-box">

        <h2>Account Details</h2>

        <p>
            <strong>Student ID:</strong>
            <?= htmlspecialchars($student["StudentID"]) ?>
        </p>

        <p>
            <strong>Student Name:</strong>
            <?= htmlspecialchars($student["StudentName"]) ?>
        </p>

        <p>
            <strong>Email:</strong>
            <?= htmlspecialchars($student["StudentEmail"]) ?>
        </p>

    </div>

</div>

</body>
</html>
