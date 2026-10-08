<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Invalid request.");
}

$student_id = trim($_POST["student_id"] ?? "");
$first_name = trim($_POST["first_name"] ?? "");
$middle_name = trim($_POST["middle_name"] ?? "");
$last_name = trim($_POST["last_name"] ?? "");
$department = $_POST["department"] ?? "";
$batch = $_POST["batch"] ?? "";
$year = $_POST["year"] ?? "";
$semester = $_POST["semester"] ?? "";
$address = trim($_POST["address"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$gender = $_POST["gender"] ?? "";
$birth_date = $_POST["birth_date"] ?? "";

if (
    $student_id === "" ||
    $first_name === "" ||
    $last_name === "" ||
    $department === "" ||
    $batch === "" ||
    $year === "" ||
    $semester === "" ||
    $gender === "" ||
    $birth_date === ""
) {
    exit("Please fill in all required fields.");
}

if ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Invalid email address.");
}

if (!in_array($department, ["CSE", "ICT","BBA"], true)) {
    exit("Invalid department.");
}

if (!in_array($batch, ["1", "2", "3", "4", "5"], true)) {
    exit("Invalid batch.");
}

if (!in_array($year, ["1st Year", "2nd Year", "3rd Year", "4th Year"], true)) {
    exit("Invalid year.");
}

if (!in_array($semester, [
    "1st Semester", "2nd Semester",
    "3rd Semester", "4th Semester",
    "5th Semester", "6th Semester",
    "7th Semester", "8th Semester"
], true)) {
    exit("Invalid semester.");
}

if (!in_array($gender, ["Male", "Female", "Other"], true)) {
    exit("Invalid gender.");
}

$date = DateTime::createFromFormat("!Y-m-d", $birth_date);

if (
    !$date ||
    $date->format("Y-m-d") !== $birth_date ||
    $birth_date > date("Y-m-d")
) {
    exit("Invalid birth date.");
}

$sql = "UPDATE students SET
        first_name = ?,
        middle_name = ?,
        last_name = ?,
        department = ?,
        batch = ?,
        year = ?,
        semester = ?,
        address = ?,
        email = ?,
        phone = ?,
        gender = ?,
        birth_date = ?
        WHERE student_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sssssssssssss",
    $first_name,
    $middle_name,
    $last_name,
    $department,
    $batch,
    $year,
    $semester,
    $address,
    $email,
    $phone,
    $gender,
    $birth_date,
    $student_id
);

try {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: view.php?success=updated");
    exit;
} catch (mysqli_sql_exception $e) {
    mysqli_stmt_close($stmt);
    error_log($e->getMessage());
    exit("Unable to update student information.");
}
?>