// Student Registration Form Validation

document.addEventListener("DOMContentLoaded", function () {

    const form = document.querySelector("form");

    if (form) {

        form.addEventListener("submit", function (event) {

            // Student ID validation
            const studentId = document.querySelector(
                'input[name="student_id"]'
            ).value.trim();

            if (studentId === "") {
                alert("Please enter Student ID.");
                event.preventDefault();
                return;
            }


            // Phone number validation
            const phoneInput = document.querySelector(
                'input[name="phone"]'
            );

            if (phoneInput && phoneInput.value.trim() !== "") {

                const phone = phoneInput.value.trim();

                if (!/^[0-9]{10,15}$/.test(phone)) {
                    alert("Please enter a valid phone number.");
                    event.preventDefault();
                    return;
                }
            }


            // Captcha validation
            const captcha = document.querySelector(
                'input[name="captcha"]'
            );

            const captchaAnswer = document.querySelector(
                'input[name="captcha_answer"]'
            );

            if (captcha && captchaAnswer) {

                if (captcha.value !== captchaAnswer.value) {
                    alert("Incorrect Captcha! Please try again.");
                    event.preventDefault();
                    return;
                }
            }


            // Terms and Conditions validation
            const terms = document.querySelector(
                'input[name="terms"]'
            );

            if (terms && !terms.checked) {
                alert("Please agree to the Terms and Conditions.");
                event.preventDefault();
                return;
            }

        });
    }
});


// Delete Confirmation Function

function confirmDelete(studentId) {

    return confirm(
        "Are you sure you want to delete student ID: "
        + studentId + "?"
    );
}