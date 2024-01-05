<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientId = mysqli_real_escape_string($con, $_POST['patient-id']);

    

    // Check if patient exists
    $checkQuery = "SELECT * FROM bigTable1 WHERE Patient_ID = '$patientId'";
    $checkResult = mysqli_query($con, $checkQuery);

    if ($checkResult !== false) {
        $patientExists = mysqli_fetch_assoc($checkResult);

        if ($patientExists) {
            echo json_encode(['status' => 'success', 'message' => 'Patient found.']);
            exit();
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
