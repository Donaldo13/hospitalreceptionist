<?php
include 'connection.php';

// Set the content type header for JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract and sanitize the input
    $appointmentId = mysqli_real_escape_string($con, $_POST['appointment-id']);

    // Check if appointment exists
    $checkQuery = "SELECT * FROM Apps WHERE ApptID = '$appointmentId'";
    $checkResult = mysqli_query($con, $checkQuery);

    if ($checkResult !== false) {
        $appointmentExists = mysqli_fetch_assoc($checkResult);

        if ($appointmentExists) {
            // Appointment found
            echo json_encode(['status' => 'success', 'message' => 'Appointment found.']);
            exit(); // Exit here for successful case
        } else {
            // Appointment not found
            echo json_encode(['status' => 'error', 'message' => 'Appointment not found.']);
            exit();
        }
    } else {
        // Error in the query
        echo json_encode(['status' => 'error', 'message' => 'Error checking appointment information: ' . mysqli_error($con)]);
        exit();
    }
}

// Handle the case when the request method is not POST
echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
?>