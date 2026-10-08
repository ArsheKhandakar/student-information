```php
<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    exit("Invalid request.");
}

if (!isset($_GET["student_id"]) || trim($_GET["student_id"]) === "") {
    exit("Student ID is required.");
}

$student_id = trim($_GET["student_id"]);

$sql = "DELETE FROM students WHERE student_id = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    exit("Unable to prepare delete query.");
}

mysqli_stmt_bind_param($stmt, "s", $student_id);

try {
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) === 0) {
        mysqli_stmt_close($stmt);
        exit("Student not found.");
    }

    mysqli_stmt_close($stmt);

    header("Location: view.php?success=deleted");
    exit;

} catch (mysqli_sql_exception $e) {

    mysqli_stmt_close($stmt);

    error_log($e->getMessage());

    exit("Unable to delete student information.");
}
?>
```
