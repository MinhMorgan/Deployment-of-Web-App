<?php

session_start();

require_once "db.php";

// If already logged in, go to dashboard.
if (isset($_SESSION["account_id"])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $mssv = trim($_POST["mssv"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($mssv === "" || $password === "") {

        $error = "Please enter your student ID and password.";

    } else {

        try {

            $sql = "
                SELECT
                    AccountID,
                    StudentID,
                    StudentName,
                    StudentEmail,
                    PasswordHash
                FROM Accounts
                WHERE StudentID = ?
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$mssv]);

            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            if (
                $student &&
                password_verify(
                    $password,
                    $student["PasswordHash"]
                )
            ) {

                session_regenerate_id(true);

                $_SESSION["account_id"] =
                    $student["AccountID"];

                $_SESSION["student_id"] =
                    $student["StudentID"];

                $_SESSION["student_name"] =
                    $student["StudentName"];

                $_SESSION["student_email"] =
                    $student["StudentEmail"];

                header("Location: index.php");
                exit();

            } else {

                $error =
                    "Incorrect student ID or password.";
            }

        } catch (PDOException $e) {

            error_log($e->getMessage());

            $error =
                "Login failed. Please try again later.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Student Portal | Login</title>

    <link
        rel="stylesheet"
        href="css/login.css">

</head>

<body>

<div class="login-container">

    <!-- LEFT PANEL -->

    <div class="login-left">

        <!-- Optional logo -->
        <!--
        <img
            src="images/tdtu-logo.png"
            alt="TDTU Logo"
            class="tdtu-logo">
        -->

        <div
            class="university-name"
            id="university-name">

            UNIVERSITY PORTAL

        </div>

        <div class="left-title">

            <h1 id="left-title-1">
                CỔNG
            </h1>

            <h1 id="left-title-2">
                THÔNG TIN
            </h1>

            <h1 id="left-title-3">
                SINH VIÊN
            </h1>

        </div>

        <div class="circle-icon">

            <span>▤</span>

        </div>

        <div
            class="left-footer"
            id="left-footer">

            HỆ THỐNG THÔNG TIN SINH VIÊN

        </div>

    </div>

    <!-- RIGHT PANEL -->

    <div class="login-right">

        <div class="language">

            <button
                type="button"
                onclick="setLanguage('vi')">

                🇻🇳 VI

            </button>

            <button
                type="button"
                onclick="setLanguage('en')">

                🇬🇧 EN

            </button>

        </div>

        <div class="login-content">

            <h2 id="welcome-title">
                XIN CHÀO!
            </h2>

            <p
                class="login-subtitle"
                id="login-subtitle">

                Vui lòng đăng nhập vào hệ thống

            </p>

            <?php if ($error !== ""): ?>

                <p class="error-message">

                    <?= htmlspecialchars($error) ?>

                </p>

            <?php endif; ?>

            <form
                action="login.php"
                method="POST"
                id="loginForm">

                <div class="input-group">

                    <span class="input-icon">
                        👤
                    </span>

                    <input
                        type="text"
                        name="mssv"
                        id="mssv"
                        placeholder="Mã số sinh viên (MSSV)"
                        required
                        autocomplete="username"
                        value="<?= htmlspecialchars($_POST['mssv'] ?? '') ?>">

                </div>

                <div class="input-group">

                    <span class="input-icon">
                        🔒
                    </span>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Mật khẩu"
                        required
                        autocomplete="current-password">

                    <button
                        type="button"
                        class="eye-button"
                        onclick="togglePassword()"
                        aria-label="Show or hide password">

                        👁

                    </button>

                </div>

                <button
                    type="submit"
                    class="login-button"
                    id="login-button">

                    ĐĂNG NHẬP

                </button>

            </form>

            <p
                class="login-note"
                id="login-note">

                Sử dụng tài khoản sinh viên của bạn để đăng nhập.

            </p>

            <p>

                Don't have an account?

                <a href="signup.php">
                    Create an account
                </a>

            </p>

        </div>

    </div>

</div>

<script src="js/login.js"></script>

</body>
</html>