<?php
include 'connection.php';
include 'header.php';




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientId = mysqli_real_escape_string($con, $_POST['patient-id']);
    $shots = mysqli_real_escape_string($con, $_POST['shots']);
    $illnesses = mysqli_real_escape_string($con, $_POST['illnesses']);

    // Validate inputs
    if (!is_numeric($patientId) || !preg_match("/^[A-Za-z]+$/", $shots) || !preg_match("/^[A-Za-z]+$/", $illnesses)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input. Please check your data.']);
        exit();
    }

    // Check if patient exists
    $checkQuery = "SELECT * FROM bigTable1 WHERE Patient_ID = '$patientId'";
    $checkResult = mysqli_query($con, $checkQuery);

    if ($checkResult !== false) {
        $patientExists = mysqli_fetch_assoc($checkResult);

        if ($patientExists) {
            // Update patient's records
            $updateQuery = "UPDATE bigTable1 SET Shots = CONCAT(IFNULL(Shots, ''),', ', '$shots'), Illnesses = CONCAT(IFNULL(Illnesses, ''),', ', '$illnesses') WHERE Patient_ID = '$patientId'";
            $updateResult = mysqli_query($con, $updateQuery);

            if ($updateResult !== false) {
                echo json_encode(['status' => 'success', 'message' => 'Patient information updated successfully.']);
                exit();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error updating patient information: ' . mysqli_error($con)]);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Patient not found.']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error checking patient information: ' . mysqli_error($con)]);
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleupdate.css"> <!-- Add your stylesheet link here -->
    <title>Update Patient's Information</title>
</head>

<body style="background: url('medical_background.jpg') center center fixed; background-size: contain; color: white;">

    <h1>Update Patient's Information</h1>

    <form id="update-form">
        <label for="patient-id">PatientID:</label>
        <input type="text" id="patient-id" name="patient-id" required pattern="[0-9]+" title="Please enter numbers only">

        <label for="shots">Shots:</label>
        <input type="text" id="shots" name="shots" required pattern="[A-Za-z]+" title="Please enter letters only">

        <label for="illnesses">Illnesses:</label>
        <input type="text" id="illnesses" name="illnesses" required pattern="[A-Za-z]+" title="Please enter letters only">

        <button type="submit">Update</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const updateForm = document.getElementById('update-form');

            updateForm.addEventListener('submit', function (event) {
                event.preventDefault();

                // Validate patient existence using JavaScript
                const patientId = document.getElementById('patient-id').value;

                fetch('checkpatient.php', {
                    method: 'POST',
                    body: new URLSearchParams({ 'patient-id': patientId }),
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Patient exists, ask for confirmation
                        const confirmation = window.confirm("Are you sure you want to update the patient's records?");
                        
                        if (confirmation) {
                            // If the user confirms, proceed with form submission
                            const formData = new FormData(updateForm);

                            fetch('update.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    // Handle success case
                                    console.log(data);
                                    alert(data.message);
                                } else {
                                    // Handle error case
                                    console.error('Error:', data.message);
                                    alert('Error: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error: Something went wrong.');
                            });
                        } else {
                            // If the user cancels, do nothing
                            alert('Update canceled by user.');
                        }
                    } else {
                        // Patient does not exist, show an alert
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error: Something went wrong.');
                });
            });
        });
    </script>

</body>

</html>

