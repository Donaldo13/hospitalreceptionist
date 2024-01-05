<?php
include 'connection.php';

// Set the content type header for JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract and sanitize the input
    $appointmentId = mysqli_real_escape_string($con, $_POST['appointment-id']);

    // Delete the appointment from the database
    $deleteQuery = "DELETE FROM Apps WHERE ApptID = '$appointmentId'";
    $deleteResult = mysqli_query($con, $deleteQuery);

    if ($deleteResult !== false) {
        $response = ['status' => 'success', 'message' => 'Appointment canceled successfully.'];
    } else {
        $response = ['status' => 'error', 'message' => 'Error canceling appointment: ' . mysqli_error($con)];
    }

    echo json_encode($response);
} else {
    // Handle the case when the request method is not POST
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
