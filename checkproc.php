<?php
include 'connection.php';

// Set the content type header for JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract and sanitize the input
    $procedureId = mysqli_real_escape_string($con, $_POST['procedure-id']);

    // Check if procedure exists
    $checkQuery = "SELECT * FROM Apps WHERE ProcID = '$procedureId'";
    $checkResult = mysqli_query($con, $checkQuery);

    if ($checkResult !== false) {
        $procedureExists = mysqli_fetch_assoc($checkResult);

        if ($procedureExists) {
            // Procedure found
            echo json_encode(['status' => 'success', 'message' => 'Procedure found.']);
            // You can add additional logic here, like showing an alert for procedure cancellation.
            exit(); // Exit here for successful case
        } else {
            // Procedure not found
            echo json_encode(['status' => 'error', 'message' => 'Procedure not found. Please check the information and re-enter valid data.']);
            exit();
        }
    } else {
        // Error in the query
        echo json_encode(['status' => 'error', 'message' => 'Error checking procedure information: ' . mysqli_error($con)]);
        exit();
    }
}

// Handle the case when the request method is not POST
echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
?>
