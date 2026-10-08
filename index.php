
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <h2>Student Information Form</h2>

    <form action="insert.php" method="POST"
          enctype="multipart/form-data">

        <label>First Name:</label>
        <input type="text" name="first_name" required>

        <label>Middle Name (Optional):</label>
        <input type="text" name="middle_name">

        <label>Last Name:</label>
        <input type="text" name="last_name" required>

        <label>Student ID:</label>
        <input type="text" name="student_id" required>

        <label>Department:</label>
        <select name="department" required>
            <option value="">Select Department</option>
            <option>CSE</option>
            <option>ICT</option>
            <option>BBA</option>
        </select>

        <label>Batch:</label>
        <select name="batch" required>
            <option value="">Select Batch</option>
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
        </select>

        <label>Year:</label>
        <select name="year" required>
            <option value="">Select Year</option>
            <option>1st Year</option>
            <option>2nd Year</option>
            <option>3rd Year</option>
            <option>4th Year</option>
        </select>

        <label>Semester:</label>
        <select name="semester" required>
            <option value="">Select Semester</option>
            <option>1st Semester</option>
            <option>2nd Semester</option>
            <option>3rd Semester</option>
            <option>4th Semester</option>
            <option>5th Semester</option>
            <option>6th Semester</option>
            <option>7th Semester</option>
            <option>8th Semester</option>
        </select>

        <label>Address:</label>
        <textarea name="address"></textarea>

        <label>Email:</label>
        <input type="email" name="email">

        <label>Phone Number:</label>
        <input type="tel" name="phone">

        <label>Gender:</label>

        <div class="options">

            <label>
                <input type="radio"
                       name="gender"
                       value="Male"
                       required>
                Male
            </label>

            <label>
                <input type="radio"
                       name="gender"
                       value="Female">
                Female
            </label>

            <label>
                <input type="radio"
                       name="gender"
                       value="Other">
                Other
            </label>

        </div>


        <label>Club Membership:</label>

        <div class="options">

            <label>
                <input type="checkbox"
                       name="clubs[]"
                       value="Programming Club">
                Programming Club
            </label>

            <label>
                <input type="checkbox"
                       name="clubs[]"
                       value="Sports Club">
                Sports Club
            </label>

            <label>
                <input type="checkbox"
                       name="clubs[]"
                       value="Cultural Club">
                Cultural Club
            </label>

            <label>
                <input type="checkbox"
                       name="clubs[]"
                       value="Debate Club">
                Debate Club
            </label>

            <label>
                <input type="checkbox"
                       name="clubs[]"
                       value="Science Club">
                Science Club
            </label>

            <label>
                <input type="checkbox"
                       name="clubs[]"
                       value="Photography Club">
                Photography Club
            </label>

        </div>


        <label>Student Image:</label>
        <input type="file"
               name="image"
               accept="image/*">


        <label>Birth Date:</label>
        <input type="date"
               name="birth_date"
               required>


        <!-- CAPTCHA -->

        <label>Captcha:</label>

        <div class="captcha-box">

            <?php

            $num1 = rand(1, 9);
            $num2 = rand(1, 9);

            $captcha_answer = $num1 + $num2;

            ?>

            <strong>
                <?= $num1 ?> + <?= $num2 ?> = ?
            </strong>

            <br><br>

            <input type="number"
                   name="captcha"
                   placeholder="Enter answer"
                   required>

            <input type="hidden"
                   name="captcha_answer"
                   value="<?= $captcha_answer ?>">

        </div>


        <!-- TERMS AND CONDITIONS -->

        <label>

            <input type="checkbox"
                   name="terms"
                   value="yes"
                   required>

            I agree to the Terms and Conditions

        </label>


        <button type="submit">
            Submit
        </button>

    </form>

</div>
<script src="script.js"></script>
</body>
</html>