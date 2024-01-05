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
    <title>Make Appointment</title>
</head>

<body style="background: url('medical_background.jpg') center center fixed; background-size: contain; color: white;">

    <h1>Make Appointment</h1>

    <!-- Form for Patient Verification -->
    <form id="verify-patient-form">
        <label for="patient-id">Patient ID:</label>
        <input type="text" id="patient-id" name="patient-id" required pattern="[0-9]+" title="Please enter numbers only">

        <label for="first-namep">First Name:</label>
        <input type="text" id="first-namep" name="first-namep" required pattern="[A-Za-z]+" title="Please enter letters only">

        <label for="last-namep">Last Name:</label>
        <input type="text" id="last-namep" name="last-namep" required pattern="[A-Za-z]+" title="Please enter letters only">

        <button type="submit">Verify Patient</button>
    </form>

    <!-- Form for Appointment Request -->
    <form id="request-appointment-form" style="display: none;">
        <label for="appointment-date">Appointment Date:(yyyy-mm-dd)</label>
        <input type="date" id="appointment-date" name="appointment-date" required>
        
        <label for="appointment-type">Appointment Type:</label>
        <input type="text" id="appointment-type" name="appointment-type" required>

        <label for="doctor-name">Doctor Name:</label>
        <input type="text" id="doctor-name" name="doctor-name" required>

        <button type="submit">Request Appointment</button>
    </form>

    <script>
        document.getElementById("verify-patient-form").addEventListener("submit", function (event) {
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

            // Check if patient exists
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "check.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        alert('Patient found. Proceeding to appointment details.');
                        document.getElementById("verify-patient-form").style.display = "none";
                        document.getElementById("request-appointment-form").style.display = "block";
                    } else {
                        alert('Patient not found. Redirecting to create an account.');
                        window.location.href = "create.php";
                    }
                }
            };
            xhr.send("patient-id=" + encodeURIComponent(patientId) +
                "&first-namep=" + encodeURIComponent(patientfirstName) +
                "&last-namep=" + encodeURIComponent(patientlastName));
        });

        document.getElementById("request-appointment-form").addEventListener("submit", function (event) {
            event.preventDefault();

            // Get form values for appointment request
            var appointmentDate = document.getElementById("appointment-date").value;
            var appointmentType = document.getElementById("appointment-type").value;
            var doctorName = document.getElementById("doctor-name").value;
            var patientId = document.getElementById("patient-id").value;
            var patientfirstName = document.getElementById("first-namep").value;
            var patientlastName = document.getElementById("last-namep").value;
            // Validate appointment request input (you may add your own validation rules)
            var dateRegex = /^\d{4}-\d{2}-\d{2}$/; // yyyy-mm-dd format
            var letterRegex = /^[A-Za-z]+$/;

            if (!dateRegex.test(appointmentDate)) {
                alert('Invalid date format. Please use yyyy-mm-dd.');
                return;
            }

            if (!letterRegex.test(appointmentType) || !letterRegex.test(doctorName)) {
                alert('Invalid input for appointment type or doctor name. Please use letters only.');
                return;
            }
            // Confirmation alert
            var confirmation = confirm('You are about to REQUEST an Appointment. Are you sure you want to do so?');
            if (confirmation) {
                var appointmentId = generateRandomId(1000, 9999);
                alert('Appointment made successfully!\nAppointment ID: ' + appointmentId);

                // Add the appointment details to the database (you need to implement this part)
                sendAppointmentData(patientId, patientfirstName, patientlastName, appointmentId, appointmentType, appointmentDate, doctorName);
                // Redirect options
                var proceedProcedure = confirm('Do you want to proceed to Procedure?');
                if (proceedProcedure) {
                    window.location.href = "procedure.php";
                }
            }
        });

        // Function to generate a random 4-digit ID
        function generateRandomId(min, max) {
            return Math.floor(Math.random() * (max - min + 1) + min);
        }

        function sendAppointmentData(patientId, patientfirstName, patientlastName, appointmentId, appointmentType, appointmentDate, doctorName) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "addapp.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                console.log(xhr.responseText);
                var response = JSON.parse(xhr.responseText);
                // Handle the response as needed
                console.log(response);
            } else {
                // Handle errors
                console.error('Error adding appointment:', xhr.status);
            }
        }
    };

    var data = "patient-id=" + encodeURIComponent(patientId) +
        "&patient-first-name=" + encodeURIComponent(patientfirstName) +
        "&patient-last-name=" + encodeURIComponent(patientlastName) +
        "&appointment-id=" + encodeURIComponent(appointmentId) +
        "&appointment-type=" + encodeURIComponent(appointmentType) +
        "&appointment-date=" + encodeURIComponent(appointmentDate) +
        "&doctor-name=" + encodeURIComponent(doctorName);

    xhr.send(data);
}
    </script>
</body>

</html>
