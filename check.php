<?php
include 'connection.php';

// Set the content type header for JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract and sanitize the input
    $patientId = mysqli_real_escape_string($con, $_POST['patient-id']);
    $patientfirstName = mysqli_real_escape_string($con, $_POST['first-namep']);
    $patientlastName = mysqli_real_escape_string($con, $_POST['last-namep']);

    // Check if patient exists
    $checkQuery = "SELECT * FROM bigTable1 WHERE Patient_ID = '$patientId' AND Patient_FirstName ='$patientfirstName' AND Patient_LastName = '$patientlastName'";
    $checkResult = mysqli_query($con, $checkQuery);

    if ($checkResult !== false) {
        $patientExists = mysqli_fetch_assoc($checkResult);

        if ($patientExists) {
            // Patient found
            echo json_encode(['status' => 'success', 'message' => 'Patient found.']);
            exit(); // Exit here for successful case
        } else {
            // Patient not found
            echo json_encode(['status' => 'error', 'message' => 'Patient not found.']);
            exit();
        }
    } else {
        // Error in the query
        echo json_encode(['status' => 'error', 'message' => 'Error checking patient information: ' . mysqli_error($con)]);
        exit();
    }
}

// Handle the case when the request method is not POST
echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
?>
