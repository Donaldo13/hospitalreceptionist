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
    <title>Cancel Procedure</title>
    <link rel="stylesheet" href="styleupdate.css">
    <script>
        function validateInput() {
            var procedureId = document.getElementById('procedure-id').value;

            // Check if the input is a 2-digit number
            if (!/^\d{2}$/.test(procedureId)) {
                alert('Invalid input. Please make sure you enter a 2-digit ID.');
                return false;
            }

            return true;
        }

        function handleCheckProcResponse(response) {
            if (response.status === 'success') {
                // Procedure found, ask for confirmation
                var confirmCancel = confirm('Procedure found. Are you sure you want to cancel this procedure?');
                if (confirmCancel) {
                    // User confirmed, call deleteproc.php in the background
                    deleteProcedure();
                }
            } else {
                // Procedure not found, show alert
                alert('Procedure not found. Please check the information and re-enter valid data.');
            }
        }

        function deleteProcedure() {
            // Fetch the form data and send it to deleteproc.php using fetch API
            var formData = new FormData(document.getElementById('cancel-form'));

            fetch('deleteproc.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => handleDeleteProcResponse(data))
            .catch(error => console.error('Error:', error));
        }

        function handleDeleteProcResponse(response) {
    console.log('Delete Procedure Response:', response); // Add this line for debugging

    if (response.status === 'success') {
        // Procedure deleted successfully, show alert
        alert('Procedure deleted successfully.');
    } else {
        // Cancellation failed or other error, show alert
        alert('Cancellation failed: ' + response.message);
    }
}


        function cancelProcedure() {
            // Validate input before proceeding
            if (!validateInput()) {
                return;
            }

            // Fetch the form data and send it to checkproc.php using fetch API
            var formData = new FormData(document.getElementById('cancel-form'));

            fetch('checkproc.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => handleCheckProcResponse(data))
            .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body style="background: url('medical_background.jpg') center center fixed; background-size: contain; color: white;">

    <div class="container">
        <h2>Cancel Procedure</h2>
        
        <form id="cancel-form" action="#" method="post" onsubmit="event.preventDefault(); cancelProcedure();">
            <label for="procedure-id">Procedure ID:</label>
            <input type="text" id="procedure-id" name="procedure-id" maxlength="2" pattern="\d{2}" required>
            <button type="submit">Submit</button>
        </form>
    </div>

</body>
</html>



