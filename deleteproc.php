<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract and sanitize the input
    $procedureId = mysqli_real_escape_string($con, $_POST['procedure-id']);

    // Update specific columns with empty values based on the Procedure ID
    $updateQuery = "UPDATE Apps SET Proc = '', ProcDate = CURRENT_DATE(), ProcID = '' WHERE ProcID = '$procedureId'";
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult !== false) {
        // Columns updated successfully, no need to echo anything
        exit();
    } else {
        // Error in the query
        echo json_encode(['status' => 'error', 'message' => 'Error clearing procedure information: ' . mysqli_error($con)]);
        exit();
    }
}

// Handle the case when the request method is not POST
echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
?>
