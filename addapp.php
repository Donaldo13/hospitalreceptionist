<?php
include 'connection.php';

// Set the content type header for JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract and sanitize the input
    $patientId = mysqli_real_escape_string($con, $_POST['patient-id']);
    $patientFirstName = mysqli_real_escape_string($con, $_POST['patient-first-name']);
    $patientLastName = mysqli_real_escape_string($con, $_POST['patient-last-name']);
    $appointmentId = mysqli_real_escape_string($con, $_POST['appointment-id']);
    $appointmentType = mysqli_real_escape_string($con, $_POST['appointment-type']);
    $appointmentDate = mysqli_real_escape_string($con, $_POST['appointment-date']);
    $doctorName = mysqli_real_escape_string($con, $_POST['doctor-name']);

    // Add the appointment details to the database
    $currentDate = date("Y-m-d");
    $insertQuery = "INSERT INTO Apps (PatID, PatF, PatL, ApptID, Appt, ApptDate, Proc, ProcDate, DocName)
                    VALUES ('$patientId', '$patientFirstName', '$patientLastName', '$appointmentId', '$appointmentType', '$appointmentDate','' ,'$currentDate','$doctorName')";

    $insertResult = mysqli_query($con, $insertQuery);

    if ($insertResult !== false) {
        $response = ['status' => 'success', 'message' => 'Appointment added successfully.'];
    } else {
        $response = ['status' => 'error', 'message' => 'Error adding appointment: ' . mysqli_error($con)];
    }
    
    echo json_encode($response);
}


?>
