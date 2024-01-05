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
    <title>Schedule a Procedure</title>
</head>

<body style="background: url('medical_background.jpg') center center fixed; background-size: contain; color: white;">

    <h1>Schedule a Procedure</h1>

    <!-- Form for Scheduling a Procedure -->
    <form id="schedule-procedure-form">
        <label for="procedure-date">Procedure Date:(yyyy-mm-dd)</label>
        <input type="date" id="procedure-date" name="procedure-date" required>

        <label for="procedure-type">Procedure Type:</label>
        <input type="text" id="procedure-type" name="procedure-type" required pattern="[A-Za-z]+" title="Please enter letters only">

        <label for="appointment-id">Appointment ID:</label>
        <input type="text" id="appointment-id" name="appointment-id" required pattern="[0-9]+" title="Please enter numbers only">

        <button type="submit">Schedule Procedure</button>
    </form>

    <script>
        document.getElementById("schedule-procedure-form").addEventListener("submit", function (event) {
            event.preventDefault();

            // Get form values
            var procedureDate = document.getElementById("procedure-date").value;
            var procedureType = document.getElementById("procedure-type").value;
            var appointmentId = document.getElementById("appointment-id").value;

            // Validate form input
            var dateRegex = /^\d{4}-\d{2}-\d{2}$/; // yyyy-mm-dd format
            var letterRegex = /^[A-Za-z]+$/;
            var numberRegex = /^[0-9]+$/;

            if (!dateRegex.test(procedureDate)) {
                alert('Invalid date format. Please use yyyy-mm-dd.');
                return;
            }

            if (!letterRegex.test(procedureType)) {
                alert('Invalid input for procedure type. Please use letters only.');
                return;
            }

            if (!numberRegex.test(appointmentId)) {
                alert('Invalid input for appointment ID. Please use numbers only.');
                return;
            }

            // Check if the appointment ID exists
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "checkAppID.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        // Appointment ID confirmed, proceed to schedule the procedure
                        scheduleProcedure(appointmentId,procedureDate, procedureType);
                    } else {
                        // Appointment ID not found
                        alert('Pre-procedure appointment was not made with the specified ID because an appointment with that ID does not exist.');
                        var userDecision = confirm('Options:\n1. Make an appointment\n2. Cancel');
                        if (userDecision) {
                            // Redirect to makeapp.php
                            window.location.href = "makeapp.php";
                        }
                    }
                }
            };
            xhr.send("appointment-id=" + encodeURIComponent(appointmentId));
        });

        function scheduleProcedure(appointmentId, procedureDate, procedureType) {
    // Send the procedure details to addpro.php
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "addpro.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                
                var response = JSON.parse(xhr.responseText);

                // Handle the response as needed
                if (response.status === 'success') {
                    // Display success message after updating procedure details
                    alert('Procedure created successfully!\nProcedure ID: ' + response.procedureId);
                } else {
                    console.error('Error updating procedure details:', response.message);
                }
            } else {
                // Handle errors
                console.error('Error updating procedure details:', xhr.status);
            }
        }
    };

    var data = "appointment-id=" + encodeURIComponent(appointmentId) +
        "&procedure-date=" + encodeURIComponent(procedureDate) +
        "&procedure-type=" + encodeURIComponent(procedureType);

    xhr.send(data);
}
    </script>
</body>

</html>
