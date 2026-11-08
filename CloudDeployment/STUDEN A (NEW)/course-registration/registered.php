<?php
require_once "auth.php";
requireLogin();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $registrationID = filter_input(
        INPUT_POST,
        "registration_id",
        FILTER_VALIDATE_INT
    );

    if ($registrationID && $registrationID > 0) {
        $stmt = $conn->prepare("
            UPDATE Registrations
            SET Status = 'Cancelled'
            WHERE RegistrationID = ?
              AND AccountID = ?
              AND Status = 'Registered'
        ");

        $stmt->execute([
            $registrationID,
            $_SESSION["account_id"]
        ]);

        if ($stmt->rowCount() > 0) {
            $message = "Registration cancelled successfully.";
        } else {
            $message = "Registration could not be cancelled.";
        }
    } else {
        $message = "Invalid registration.";
    }
}

$stmt = $conn->prepare("
    SELECT
        r.RegistrationID,
        r.RegistrationDate,
        s.SubjectName,
        s.Credits,
        s.StartWeek,
        s.EndWeek
    FROM Registrations r
    INNER JOIN Subjects s
        ON r.SubjectID = s.SubjectID
    WHERE r.AccountID = ?
      AND r.Status = 'Registered'
    ORDER BY r.RegistrationDate DESC
");

$stmt->execute([$_SESSION["account_id"]]);
$courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Courses</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <h1>My Registered Courses</h1>

    <nav class="navbar">
        <div class="nav-left">
            <a class="nav-button" href="index.php">Home</a>
            <a class="nav-button" href="courses.php">Registration</a>
            <a class="nav-button" href="registered.php">Registered Courses</a>
            <a class="nav-button" href="information.php">Information</a>
        </div>
        <a class="logout-button" href="logout.php">Logout</a>
    </nav>

    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (count($courses) > 0): ?>

        <?php foreach ($courses as $course): ?>

            <div class="side-box">

                <h3><?= htmlspecialchars($course["SubjectName"]) ?></h3>

                <p>Credits: <?= (int)$course["Credits"] ?></p>

                <p>
                    Weeks:
                    <?= (int)$course["StartWeek"] ?>
                    -
                    <?= (int)$course["EndWeek"] ?>
                </p>

                <p>
                    Registered on:
                    <?= htmlspecialchars($course["RegistrationDate"]) ?>
                </p>

                <form method="POST" action="registered.php"
                      onsubmit="return confirm('Cancel this registration?');">

                    <input
                        type="hidden"
                        name="registration_id"
                        value="<?= (int)$course["RegistrationID"] ?>"
                    >

                    <button type="submit">Cancel Registration</button>

                </form>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p>You have not registered for any courses yet.</p>
        <a href="courses.php">Browse Available Courses</a>

    <?php endif; ?>

</div>

</body>
</html>
