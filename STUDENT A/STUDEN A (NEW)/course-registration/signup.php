<?php
session_start();
require_once "db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $studentID = trim($_POST["student_id"] ?? "");
    $studentName = trim($_POST["student_name"] ?? "");
    $studentEmail = trim($_POST["student_email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";

    if (
        $studentID === "" ||
        $studentName === "" ||
        $studentEmail === "" ||
        $password === "" ||
        $confirmPassword === ""
    ) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($studentEmail, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif (strlen($password) < 8) {
        $error = "Password must contain at least 8 characters.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("
                INSERT INTO Accounts
                (StudentID, StudentName, StudentEmail, PasswordHash)
                VALUES (:id, :name, :email, :password)
            ");

            $stmt->execute([
                ":id" => $studentID,
                ":name" => $studentName,
                ":email" => $studentEmail,
                ":password" => $passwordHash
            ]);

            $success = "Account created successfully! You can now log in.";
        } catch (PDOException $e) {
            error_log($e->getMessage());

            if ($e->getCode() === "23000") {
                $error = "Student ID or email already exists.";
            } else {
                $error = "Unable to create account. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal | Sign Up</title>
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>

<div class="signup-container">

    <section class="signup-left">
        <div class="university-name">
            TON DUC THANG UNIVERSITY
        </div>

        <div class="left-title">
            <h1>STUDENT<br>REGISTRATION<br>PORTAL</h1>
        </div>

        <div class="left-footer">
            Create your account and start your journey.
        </div>

        <div class="circle-icon" aria-hidden="true">+</div>
    </section>

    <section class="signup-right">
        <div class="signup-content">
            <h2>Create Account</h2>
            <p class="signup-subtitle">
                Register to access the Course Registration Portal.
            </p>

            <?php if ($error): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success-message">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="signup.php">
                <div class="input-group">
                    <span class="input-icon" aria-hidden="true">▣</span>
                    <input type="text" name="student_id" placeholder="Student ID"
                           value="<?= htmlspecialchars($_POST["student_id"] ?? "") ?>" required>
                </div>

                <div class="input-group">
                    <span class="input-icon" aria-hidden="true">♙</span>
                    <input type="text" name="student_name" placeholder="Student Name"
                           value="<?= htmlspecialchars($_POST["student_name"] ?? "") ?>" required>
                </div>

                <div class="input-group">
                    <span class="input-icon" aria-hidden="true">✉</span>
                    <input type="email" name="student_email" placeholder="Student Email"
                           value="<?= htmlspecialchars($_POST["student_email"] ?? "") ?>" required>
                </div>

                <div class="input-group">
                    <span class="input-icon" aria-hidden="true">▣</span>
                    <input type="password" name="password" placeholder="Password"
                           autocomplete="new-password" required>
                </div>

                <div class="input-group">
                    <span class="input-icon" aria-hidden="true">✓</span>
                    <input type="password" name="confirm_password" placeholder="Confirm Password"
                           autocomplete="new-password" required>
                </div>

                <button type="submit" class="signup-button">Create Account</button>
            </form>

            <p class="account-link">
                Already have an account?
                <a href="login.php">Login</a>
            </p>
        </div>
    </section>

</div>

</body>
</html>
