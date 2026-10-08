<?php
session_start();
require "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Invalid request.");
}

// Validate required fields
$required = [
    "student_id", "first_name", "last_name",
    "department", "batch", "year", "semester",
    "gender", "birth_date"
];

foreach ($required as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === "") {
        exit("Please fill in all required fields.");
    }
}

if (!isset($_POST["terms"])) {
    exit("Please accept the Terms and Conditions.");
}

// Get form values
$student_id = trim($_POST["student_id"]);
$first_name = trim($_POST["first_name"]);
$middle_name = trim($_POST["middle_name"] ?? "");
$last_name = trim($_POST["last_name"]);
$department = $_POST["department"];
$batch = $_POST["batch"];
$year = $_POST["year"];
$semester = $_POST["semester"];
$address = trim($_POST["address"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$gender = $_POST["gender"];
$birth_date = $_POST["birth_date"];
$clubs = $_POST["clubs"] ?? [];

// Validate email if provided
if ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Invalid email address.");
}

// Validate gender
if (!in_array($gender, ["Male", "Female", "Other"], true)) {
    exit("Invalid gender.");
}

// Validate date
$date = DateTime::createFromFormat("Y-m-d", $birth_date);

if (!$date || $date->format("Y-m-d") !== $birth_date) {
    exit("Invalid birth date.");
}

if ($birth_date > date("Y-m-d")) {
    exit("Birth date cannot be in the future.");
}

// Validate clubs
$allowed_clubs = [
    "Programming Club",
    "Sports Club",
    "Cultural Club",
    "Debate Club",
    "Science Club",
    "Photography Club"
];

if (!is_array($clubs)) {
    exit("Invalid club selection.");
}

foreach ($clubs as $club) {
    if (!in_array($club, $allowed_clubs, true)) {
        exit("Invalid club selection.");
    }
}

// Validate and upload image (optional)
$image_name = null;

if (
    isset($_FILES["image"]) &&
    $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE
) {
    if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        exit("Image upload failed.");
    }

    if ($_FILES["image"]["size"] > 2 * 1024 * 1024) {
        exit("Image size must be 2 MB or less.");
    }

    $image_info = getimagesize($_FILES["image"]["tmp_name"]);

    if ($image_info === false) {
        exit("Please upload a valid image.");
    }

    $allowed_types = [
        "image/jpeg" => "jpg",
        "image/png"  => "png",
        "image/gif"  => "gif",
        "image/webp" => "webp"
    ];

    $mime = $image_info["mime"];

    if (!isset($allowed_types[$mime])) {
        exit("Only JPG, PNG, GIF, and WEBP images are allowed.");
    }

    $image_name = bin2hex(random_bytes(16))
                . "." . $allowed_types[$mime];

    $upload_path = __DIR__ . "/uploads/";

    if (!is_dir($upload_path)) {
        exit("The uploads folder does not exist.");
    }

    if (!move_uploaded_file(
        $_FILES["image"]["tmp_name"],
        $upload_path . $image_name
    )) {
        exit("Could not save the uploaded image.");
    }
}

// Insert student and club memberships together
mysqli_begin_transaction($conn);

try {
    $sql = "INSERT INTO students
        (student_id, first_name, middle_name, last_name,
         department, batch, year, semester, address,
         email, phone, gender, image, birth_date)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssssssss",
        $student_id,
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
        $image_name,
        $birth_date
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (!empty($clubs)) {
        $club_sql = "INSERT INTO student_clubs
                     (student_id, club_name) VALUES (?, ?)";

        $club_stmt = mysqli_prepare($conn, $club_sql);

        foreach ($clubs as $club) {
            mysqli_stmt_bind_param(
                $club_stmt,
                "ss",
                $student_id,
                $club
            );

            mysqli_stmt_execute($club_stmt);
        }

        mysqli_stmt_close($club_stmt);
    }

    mysqli_commit($conn);

    header("Location: view.php?success=inserted");
    exit;

} catch (Throwable $e) {
    mysqli_rollback($conn);

    if ($image_name !== null &&
        file_exists(__DIR__ . "/uploads/" . $image_name)) {
        unlink(__DIR__ . "/uploads/" . $image_name);
    }

    if ($e instanceof mysqli_sql_exception &&
        $e->getCode() === 1062) {
        exit("This Student ID already exists. Please use another ID.");
    }
    error_log($e->getMessage());

    exit("Database Error: " . htmlspecialchars($e->getMessage()));
    }
?>