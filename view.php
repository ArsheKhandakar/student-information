
<?php
require "db.php";

$sql = "SELECT
            s.*,
            TIMESTAMPDIFF(YEAR, s.birth_date, CURDATE()) AS age,
            GROUP_CONCAT(DISTINCT sc.club_name
                         ORDER BY sc.club_name
                         SEPARATOR ', ') AS clubs
        FROM students s
        LEFT JOIN student_clubs sc
            ON s.student_id = sc.student_id
        GROUP BY s.student_id
        ORDER BY s.student_id";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>All Students</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f2f2f2;
        }

        h2 {
            text-align: center;
        }

        .top-buttons {
            margin-bottom: 15px;
        }

        .table-container {
            overflow-x: auto;
            background: white;
            padding: 15px;
        }

        table {
            width: 100%;
            min-width: 1500px;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 9px;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #087f5b;
            color: white;
        }

        img {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        a {
            display: inline-block;
            padding: 8px 12px;
            margin: 3px;
            text-decoration: none;
            border-radius: 4px;
        }

        .add {
            background: #087f5b;
            color: white;
        }

        .edit {
            background: #1769aa;
            color: white;
        }

        .delete {
            background: #c62828;
            color: white;
        }

        .actions {
            text-align: center;
            min-width: 150px;
        }
    </style>
</head>

<body>

<h2>All Student Information</h2>

<div class="top-buttons">

    <a class="add" href="index.php">
        Add New Student
    </a>

    <a class="add" href="search.php">
        Search Student
    </a>

    <a class="add" href="dashboard.php">
        Dashboard
    </a>

</div>

<?php if (isset($_GET["success"]) && $_GET["success"] === "inserted"): ?>

    <p style="color: green;">
        Student information saved successfully!
    </p>

<?php endif; ?>


<?php if (isset($_GET["success"]) && $_GET["success"] === "updated"): ?>

    <p style="color: green;">
        Student information updated successfully!
    </p>

<?php endif; ?>


<div class="table-container">

<table>

    <thead>

        <tr>

            <th>Student ID</th>
            <th>Image</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Department</th>
            <th>Batch</th>
            <th>Year</th>
            <th>Semester</th>
            <th>Address</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Gender</th>
            <th>Clubs</th>
            <th>Birth Date</th>
            <th>Age</th>
            <th class="actions">Actions</th>

        </tr>

    </thead>


    <tbody>

    <?php if (mysqli_num_rows($result) > 0): ?>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>

            <tr>

                <td>
                    <?= htmlspecialchars($row["student_id"]) ?>
                </td>


                <td>

                    <?php if (!empty($row["image"])): ?>

                        <img
                            src="uploads/<?= rawurlencode($row["image"]) ?>"
                            alt="Student image"
                        >

                    <?php else: ?>

                        No image

                    <?php endif; ?>

                </td>


                <td>
                    <?= htmlspecialchars($row["first_name"]) ?>
                </td>


                <td>
                    <?= htmlspecialchars($row["middle_name"] ?? "") ?>
                </td>


                <td>
                    <?= htmlspecialchars($row["last_name"]) ?>
                </td>


                <td>
                    <?= htmlspecialchars($row["department"]) ?>
                </td>


                <td>
                    <?= htmlspecialchars($row["batch"]) ?>
                </td>


                <td>
                    <?= htmlspecialchars($row["year"]) ?>
                </td>


                <td>
                    <?= htmlspecialchars($row["semester"]) ?>
                </td>


                <td>
                    <?= htmlspecialchars($row["address"] ?? "") ?>
                </td>


                <td>
                    <?= htmlspecialchars($row["email"] ?? "") ?>
                </td>


                <td>
                    <?= htmlspecialchars($row["phone"] ?? "") ?>
                </td>


                <td>
                    <?= htmlspecialchars($row["gender"]) ?>
                </td>


                <td>
                    <?= htmlspecialchars($row["clubs"] ?? "None") ?>
                </td>


                <td>
                    <?= htmlspecialchars($row["birth_date"]) ?>
                </td>


                <td>
                    <?= htmlspecialchars((string)$row["age"]) ?>
                </td>


                <td class="actions">

                    <a
                        class="edit"
                        href="edit.php?student_id=<?= urlencode($row["student_id"]) ?>"
                    >
                        Edit
                    </a>


                    <a
                        class="delete"
                        href="delete.php?student_id=<?= urlencode($row["student_id"]) ?>"
                        onclick="return confirm('Are you sure you want to delete this student?');"
                    >
                        Delete
                    </a>

                </td>

            </tr>

        <?php endwhile; ?>

    <?php else: ?>

        <tr>

            <td colspan="17">
                No student records found.
            </td>

        </tr>

    <?php endif; ?>

    </tbody>

</table>

</div>

</body>
</html>

