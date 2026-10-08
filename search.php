<?php
require "db.php";

$student = null;
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $student_id = trim($_POST["student_id"] ?? "");

    if ($student_id === "") {
        $message = "Please enter a Student ID.";
    } else {

        $sql = "SELECT s.*,
                TIMESTAMPDIFF(YEAR, s.birth_date, CURDATE()) AS age
                FROM students s
                WHERE s.student_id = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $student_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $student = mysqli_fetch_assoc($result);
        } else {
            $message = "No student found with this Student ID.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Student</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f8;
            padding: 30px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: #2457a7;
        }

        input, button {
            padding: 10px;
            margin-top: 10px;
        }

        input {
            width: 65%;
        }

        button {
            background: #2457a7;
            color: white;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
            overflow-wrap: anywhere;
        }

        th {
            background: #e5ecf8;
        }

        .message {
            margin-top: 20px;
            color: #c0392b;
        }

        a {
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Search Student by ID</h2>

    <form method="POST" action="search.php">

        <label>Enter Student ID:</label><br>

        <input
            type="text"
            name="student_id"
            placeholder="Example: ST1002"
            required
        >

        <button type="submit">Search</button>

    </form>

    <?php if ($message !== ""): ?>
        <p class="message">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <?php if ($student !== null): ?>

        <h3>Student Information</h3>

        <table>
            <tr>
                <th>Field</th>
                <th>Information</th>
            </tr>

            <tr>
                <td>Student ID</td>
                <td><?= htmlspecialchars($student["student_id"]) ?></td>
            </tr>

            <tr>
                <td>First Name</td>
                <td><?= htmlspecialchars($student["first_name"]) ?></td>
            </tr>

            <tr>
                <td>Middle Name</td>
                <td><?= htmlspecialchars($student["middle_name"] ?? "") ?></td>
            </tr>

            <tr>
                <td>Last Name</td>
                <td><?= htmlspecialchars($student["last_name"]) ?></td>
            </tr>

            <tr>
                <td>Department</td>
                <td><?= htmlspecialchars($student["department"]) ?></td>
            </tr>

            <tr>
                <td>Batch</td>
                <td><?= htmlspecialchars($student["batch"]) ?></td>
            </tr>

            <tr>
                <td>Year</td>
                <td><?= htmlspecialchars($student["year"]) ?></td>
            </tr>

            <tr>
                <td>Semester</td>
                <td><?= htmlspecialchars($student["semester"]) ?></td>
            </tr>

            <tr>
                <td>Address</td>
                <td><?= htmlspecialchars($student["address"] ?? "") ?></td>
            </tr>

            <tr>
                <td>Email</td>
                <td><?= htmlspecialchars($student["email"] ?? "") ?></td>
            </tr>

            <tr>
                <td>Phone</td>
                <td><?= htmlspecialchars($student["phone"] ?? "") ?></td>
            </tr>

            <tr>
                <td>Gender</td>
                <td><?= htmlspecialchars($student["gender"] ?? "") ?></td>
            </tr>

            <tr>
                <td>Birth Date</td>
                <td><?= htmlspecialchars($student["birth_date"] ?? "") ?></td>
            </tr>

            <tr>
                <td>Age</td>
                <td><?= htmlspecialchars((string) $student["age"]) ?></td>
            </tr>

        </table>

        <?php if (!empty($student["image"])): ?>
            <h3>Student Image</h3>

            <img
                src="uploads/<?= rawurlencode(basename($student["image"])) ?>"
                alt="Student Image"
                width="150"
            >
        <?php endif; ?>

    <?php endif; ?>

    <a href="view.php">Back to All Students</a>

</div>

</body>
</html>