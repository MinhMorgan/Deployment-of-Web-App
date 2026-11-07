<?php
session_start();

require_once "db.php";

if (!isset($_SESSION['account_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("
    SELECT StudentID, StudentName, StudentEmail, CreatedAt
    FROM Accounts
    WHERE AccountID = ?
");

$stmt->execute([$_SESSION['account_id']]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    header("Location: logout.php");
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

    <header class="banner">
        <div class="university-logo">UNIVERSITY</div>

        <div class="banner-title">
            <h1>THÔNG TIN SINH VIÊN</h1>
            <h2>STUDENT INFORMATION</h2>
        </div>
    </header>

    <nav class="navbar">
        <div class="nav-left">
            <a href="index.php" class="nav-button">🏠 Home</a>
            <a href="courses.php" class="nav-button">📋 Registration</a>
            <a href="registered.php" class="nav-button">📚 Registered Courses</a>
            <a href="information.php" class="nav-button active">👤 Information</a>
        </div>
    </nav>

    <main class="content">

        <fieldset class="announcement">
            <legend>Thông tin sinh viên | Student Information</legend>

            <p>
                <strong>Student ID:</strong>
                <?= htmlspecialchars($student['StudentID']) ?>
            </p>

            <p>
                <strong>Full Name:</strong>
                <?= htmlspecialchars($student['StudentName']) ?>
            </p>

            <p>
                <strong>Email:</strong>
                <?= htmlspecialchars($student['StudentEmail']) ?>
            </p>

            <p>
                <strong>Account Created:</strong>
                <?= htmlspecialchars($student['CreatedAt'] ?? '') ?>
            </p>

        </fieldset>

    </main>

</div>

</body>
</html>
