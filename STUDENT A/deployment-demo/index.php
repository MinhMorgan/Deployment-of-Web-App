
<?php
require_once "db.php";

$sql = "SELECT student_code, name FROM students";
$stmt = $conn->query($sql);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Deployment Demo</title>
</head>
<body>
    <h1>Deployment of Web Applications</h1>

    <h2>Student A Infrastructure Demo</h2>

    <p>Server Status: Online</p>

    <p>Server Time:
        <?php echo date("Y-m-d H:i:s"); ?>
    </p>

    <p>LAN IP: 192.168.100.217</p>

    <hr>

    <h2>Our Group Members</h2>

    <table border="1">
        <tr>
            <th>Student ID</th>
            <th>Name</th>
        </tr>

        <?php foreach ($students as $student): ?>
        <tr>
            <td>
                <?= htmlspecialchars($student['student_code']) ?>
            </td>
            <td>
                <?= htmlspecialchars($student['name']) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
