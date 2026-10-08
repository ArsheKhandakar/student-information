<?php
require "db.php";

if (!isset($_GET["student_id"]) || trim($_GET["student_id"]) === "") {
    exit("Student ID is required.");
}

$student_id = trim($_GET["student_id"]);

$sql = "SELECT * FROM students WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $student_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$student) {
    exit("Student not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
</head>
<body>

<h2>Update Student Information</h2>

<form action="update.php" method="POST">

    <input type="hidden" name="student_id"
           value="<?= htmlspecialchars($student['student_id']) ?>">

    <label>First Name:</label><br>
    <input type="text" name="first_name"
           value="<?= htmlspecialchars($student['first_name']) ?>" required>
    <br><br>

    <label>Middle Name:</label><br>
    <input type="text" name="middle_name"
           value="<?= htmlspecialchars($student['middle_name'] ?? '') ?>">
    <br><br>

    <label>Last Name:</label><br>
    <input type="text" name="last_name"
           value="<?= htmlspecialchars($student['last_name']) ?>" required>
    <br><br>

    <label>Department:</label><br>
    <select name="department" required>
        <?php
        $departments = ["CSE", "ICT", "BBA"];
        foreach ($departments as $dept) {
            $selected = ($student["department"] === $dept) ? "selected" : "";
            echo '<option value="' . htmlspecialchars($dept) . '" '
                . $selected . '>' . htmlspecialchars($dept) . '</option>';
        }
        ?>
    </select>
    <br><br>

    <label>Batch:</label><br>
    <select name="batch" required>
        <?php
        foreach (["1", "2", "3", "4", "5"] as $batch) {
            $selected = ($student["batch"] === $batch) ? "selected" : "";
            echo '<option value="' . $batch . '" ' . $selected . '>'
                . $batch . '</option>';
        }
        ?>
    </select>
    <br><br>

    <label>Year:</label><br>
    <select name="year" required>
        <?php
        foreach (["1st Year", "2nd Year", "3rd Year", "4th Year"] as $year) {
            $selected = ($student["year"] === $year) ? "selected" : "";
            echo '<option value="' . $year . '" ' . $selected . '>'
                . $year . '</option>';
        }
        ?>
    </select>
    <br><br>

    <label>Semester:</label><br>
    <select name="semester" required>
        <?php
        foreach ([
            "1st Semester", "2nd Semester",
            "3rd Semester", "4th Semester",
            "5th Semester", "6th Semester",
            "7th Semester", "8th Semester"
        ] as $semester) {
            $selected = ($student["semester"] === $semester) ? "selected" : "";
            echo '<option value="' . $semester . '" ' . $selected . '>'
                . $semester . '</option>';
        }
        ?>
    </select>
    <br><br>

    <label>Address:</label><br>
    <textarea name="address"><?= htmlspecialchars($student['address'] ?? '') ?></textarea>
    <br><br>

    <label>Email:</label><br>
    <input type="email" name="email"
           value="<?= htmlspecialchars($student['email'] ?? '') ?>">
    <br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone"
           value="<?= htmlspecialchars($student['phone'] ?? '') ?>">
    <br><br>

    <label>Gender:</label><br>
    <select name="gender" required>
        <?php
        foreach (["Male", "Female", "Other"] as $gender) {
            $selected = ($student["gender"] === $gender) ? "selected" : "";
            echo '<option value="' . $gender . '" ' . $selected . '>'
                . $gender . '</option>';
        }
        ?>
    </select>
    <br><br>

    <label>Birth Date:</label><br>
    <input type="date" name="birth_date"
           value="<?= htmlspecialchars($student['birth_date'] ?? '') ?>" required>
    <br><br>

    <button type="submit">Update Student</button>

</form>

<br>
<a href="view.php">Back to All Students</a>

</body>
</html>