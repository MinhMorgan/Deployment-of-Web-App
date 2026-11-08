<?php
require_once "auth.php";
requireLogin();

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subjectID = filter_input(INPUT_POST, "subject_id", FILTER_VALIDATE_INT);

    if (!$subjectID || $subjectID < 1) {
        $message = "Invalid subject.";
        $messageType = "error";
    } else {
        try {
            $conn->beginTransaction();

            // Lock the subject row while checking available capacity.
            $stmt = $conn->prepare("
                SELECT SubjectID, MaxStudents
                FROM Subjects
                WHERE SubjectID = ?
                FOR UPDATE
            ");
            $stmt->execute([$subjectID]);
            $subject = $stmt->fetch();

            if (!$subject) {
                throw new Exception("Subject not found.");
            }

            // Check whether the student already registered.
            $stmt = $conn->prepare("
                SELECT RegistrationID, Status
                FROM Registrations
                WHERE AccountID = ? AND SubjectID = ?
            ");
            $stmt->execute([$_SESSION["account_id"], $subjectID]);
            $existing = $stmt->fetch();

            if ($existing && $existing["Status"] === "Registered") {
                throw new Exception("You have already registered for this subject.");
            }

            // Count currently registered students.
            $stmt = $conn->prepare("
                SELECT COUNT(*)
                FROM Registrations
                WHERE SubjectID = ? AND Status = 'Registered'
            ");
            $stmt->execute([$subjectID]);
            $registeredCount = (int)$stmt->fetchColumn();

            if ($registeredCount >= (int)$subject["MaxStudents"]) {
                throw new Exception("This subject is full.");
            }

            if ($existing) {
                // Reactivate a previously cancelled registration.
                $stmt = $conn->prepare("
                    UPDATE Registrations
                    SET Status = 'Registered',
                        RegistrationDate = CURRENT_TIMESTAMP
                    WHERE RegistrationID = ?
                ");
                $stmt->execute([$existing["RegistrationID"]]);
            } else {
                $stmt = $conn->prepare("
                    INSERT INTO Registrations (AccountID, SubjectID)
                    VALUES (?, ?)
                ");
                $stmt->execute([$_SESSION["account_id"], $subjectID]);
            }

            $conn->commit();

            $message = "Course registered successfully!";
            $messageType = "success";

        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            $message = $e->getMessage();
            $messageType = "error";
        }
    }
}

// Get all subjects and the number of active registrations.
$stmt = $conn->prepare("
    SELECT
        s.SubjectID,
        s.SubjectName,
        s.Credits,
        s.MaxStudents,
        s.StartWeek,
        s.EndWeek,
        COUNT(r.RegistrationID) AS RegisteredCount,
        MAX(
            CASE
                WHEN r.AccountID = ? AND r.Status = 'Registered'
                THEN 1
                ELSE 0
            END
        ) AS IsRegistered
    FROM Subjects s
    LEFT JOIN Registrations r
        ON s.SubjectID = r.SubjectID
        AND r.Status = 'Registered'
    GROUP BY
        s.SubjectID,
        s.SubjectName,
        s.Credits,
        s.MaxStudents,
        s.StartWeek,
        s.EndWeek
    ORDER BY s.SubjectID
");

$stmt->execute([$_SESSION["account_id"]]);
$courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <h1>Available Courses</h1>

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
        <p style="color:<?= $messageType === "success" ? "green" : "red" ?>;">
            <?= htmlspecialchars($message) ?>
        </p>
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
                    Registered:
                    <?= (int)$course["RegisteredCount"] ?>
                    /
                    <?= (int)$course["MaxStudents"] ?>
                </p>

                <?php if ($course["IsRegistered"]): ?>

                    <p style="color:green;">You are registered.</p>

                <?php elseif ($course["RegisteredCount"] >= $course["MaxStudents"]): ?>

                    <p style="color:red;">Course is full.</p>

                <?php else: ?>

                    <form method="POST" action="courses.php">
                        <input
                            type="hidden"
                            name="subject_id"
                            value="<?= (int)$course["SubjectID"] ?>"
                        >
                        <button type="submit">Register</button>
                    </form>

                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p>No courses are currently available.</p>

    <?php endif; ?>

</div>

</body>
</html>
