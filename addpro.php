<?php
include 'connection.php';

// Set the content type header for JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract and sanitize the input
    $appointmentId = mysqli_real_escape_string($con, $_POST['appointment-id']);
    $procedureDate = mysqli_real_escape_string($con, $_POST['procedure-date']);
    $procedureType = mysqli_real_escape_string($con, $_POST['procedure-type']);

    // Generate a random 2-digit Procedure ID
    $procedureId = mt_rand(10, 99);

    // Update the Apps table with procedure details
    $updateQuery = "UPDATE Apps SET Proc = '$procedureType', ProcDate = '$procedureDate', ProcID = '$procedureId' WHERE ApptID = '$appointmentId'";
    
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult !== false) {
        $response = ['status' => 'success', 'message' => 'Procedure details updated successfully.', 'procedureId' => $procedureId];
    } else {
        $response = ['status' => 'error', 'message' => 'Error updating procedure details: ' . mysqli_error($con)];
    }

    echo json_encode($response); // Ensure that only JSON is echoed
} else {
    // Handle the case when the request method is not POST
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

error_log("Debug: Some information");
?>

