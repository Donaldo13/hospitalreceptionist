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
    <title>Cancel Appointment</title>
</head>

<body style="background: url('medical_background.jpg') center center fixed; background-size: contain; color: white;">

    <h1>Cancel Appointment</h1>

    <!-- Form for Cancel Appointment -->
    <form id="cancel-appointment-form">
        <label for="appointment-id">Appointment ID:</label>
        <input type="text" id="appointment-id" name="appointment-id" required pattern="[0-9]+" title="Please enter numbers only">

        <button type="submit">Check and Cancel Appointment</button>
    </form>

    <script>
        document.getElementById("cancel-appointment-form").addEventListener("submit", function (event) {
            event.preventDefault();

            // Get form values
            var appointmentId = document.getElementById("appointment-id").value;

            // Validate form input
            if (!/^[0-9]+$/.test(appointmentId)) {
                alert('Invalid input. Please enter a valid Appointment ID.');
                return;
            }

            // Check if appointment exists
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "checkAppID.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        // Appointment found, confirm cancellation
                        var confirmation = confirm('You are about to cancel an appointment. If this is a pre-surgical appointment, this cancellation will also cancel your procedure. Are you sure you want to do so?');
                        if (confirmation) {
                            // User confirmed, proceed with cancellation
                            deleteAppointment(appointmentId);
                            // Add code to update database or perform further actions if needed
                        } else {
                            // User canceled the confirmation, no action needed
                        }
                    } else {
                        // Appointment not found, ask user to check information
                        alert('Appointment does not exist. Please check the information and re-enter a valid Appointment ID.');
                    }
                }
            };
            xhr.send("appointment-id=" + encodeURIComponent(appointmentId));
        });
        function deleteAppointment(appointmentId) {
        // Call deleteapp.php to delete the appointment
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "deleteapp.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // Appointment deleted successfully
                    alert('Appointment canceled successfully!');
                    // Add code to update database or perform further actions if needed
                } else {
                    // Error deleting appointment
                    alert('Error canceling appointment: ' + response.message);
                }
            }
        };
        xhr.send("appointment-id=" + encodeURIComponent(appointmentId));
    }
    </script>
</body>

</html>