
<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Redirect to the login page or perform authentication
    header("Location: verify_login.php");
    exit();
}
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleupdate.css"> 
    <title>Create Patient Account</title>
</head>

<body style="background: url('medical_background.jpg') center center fixed; background-size: contain; color: white;">

    <h1>Create Patient Account</h1>

    <form id="create-form">
        <label for="patient-id">Patient ID:</label>
        <input type="text" id="patient-id" name="patient-id" required pattern="[0-9]+" title="Please enter numbers only">

        <label for="first-namep">First Name:</label>
        <input type="text" id="first-namep" name="first-namep" required pattern="[A-Za-z]+" title="Please enter letters only">

        <label for="last-namep">Last Name:</label>
        <input type="text" id="last-namep" name="last-namep" required pattern="[A-Za-z]+" title="Please enter letters only">

        <button type="submit">Create Account</button>
    </form>
    <script>
        document.getElementById("create-form").addEventListener("submit", function (event) {
            event.preventDefault();

            // Get form values
            var patientId = document.getElementById("patient-id").value;
            var patientfirstName = document.getElementById("first-namep").value;
            var patientlastName = document.getElementById("last-namep").value;

            // Validate form input
            if (!/^[0-9]+$/.test(patientId) || !/^[A-Za-z]+$/.test(patientfirstName) || !/^[A-Za-z]+$/.test(patientlastName)) {
                alert('Invalid input. Please check your data.');
                return;
            }

            // Check if user exists
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "check.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        alert('User already exists: ' + response.message);
                    } else {
                        // User not found, proceed to create
                        createUser();
                    }
                }
            };
            xhr.send("patient-id=" + encodeURIComponent(patientId) +
                "&first-namep=" + encodeURIComponent(patientfirstName) +
                "&last-namep=" + encodeURIComponent(patientlastName));
        });

        function createUser() {
    // Get form values
    var patientId = document.getElementById("patient-id").value;
    var patientfirstName = document.getElementById("first-namep").value;
    var patientlastName = document.getElementById("last-namep").value;

    // Validate form input
    if (!/^[0-9]+$/.test(patientId) || !/^[A-Za-z]+$/.test(patientfirstName) || !/^[A-Za-z]+$/.test(patientlastName)) {
        alert('Invalid input. Please check your data.');
        return;
    }

    // Create new user
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "adduser.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    alert('Patient created successfully. Details:\nID: ' + patientId +
                        '\nFirst Name: ' + patientfirstName +
                        '\nLast Name: ' + patientlastName +
                        '\n' + response.message);
                } catch (error) {
                    alert('Error parsing JSON response. Please try again.');
                }
            } else {
                alert('Error creating user. Please try again');
            }
        }
    };

    xhr.send("patient-id=" + encodeURIComponent(patientId) +
        "&first-namep=" + encodeURIComponent(patientfirstName) +
        "&last-namep=" + encodeURIComponent(patientlastName));
}

    </script>
</body>
</html>