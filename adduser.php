<?php
include 'connection.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['authenticated'])) {
    $firstName = isset($_SESSION['firstName']) ? $_SESSION['firstName'] : '';
    $lastName = isset($_SESSION['lastName']) ? $_SESSION['lastName'] : '';
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
    $phone = isset($_SESSION['phone']) ? $_SESSION['phone'] : '';
    $receptionistId = isset($_SESSION['receptionist-id']) ? $_SESSION['receptionist-id'] : '';
    
    $patientId = mysqli_real_escape_string($con, $_POST['patient-id']);
    $patientfirstName = mysqli_real_escape_string($con, $_POST['first-namep']);
    $patientlastName = mysqli_real_escape_string($con, $_POST['last-namep']);

    // Add a new row to the table with the specified attributes and values
    $insertQuery = "INSERT INTO bigTable1 (Receptionist_FirstName, Receptionist_LastName, Receptionist_ID, Receptionist_Phone, Receptionist_Email, Patient_FirstName, Patient_LastName, Patient_ID)
                    VALUES ('$firstName', '$lastName', '$receptionistId', '$phone', '$email', '$patientfirstName', '$patientlastName', '$patientId')";

    $insertResult = mysqli_query($con, $insertQuery);

    if ($insertResult !== false) {
        $response = ['status' => 'success', 'message' => 'Patient added successfully.'];
    } else {
        $response = ['status' => 'error', 'message' => 'Error adding patient: ' . mysqli_error($con)];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>



