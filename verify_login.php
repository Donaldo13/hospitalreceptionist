<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get values from the form
    $firstName = mysqli_real_escape_string($con, $_POST['first-name']);
    $lastName = mysqli_real_escape_string($con, $_POST['last-name']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $receptionistId = mysqli_real_escape_string($con, $_POST['receptionist-id']);
    $emailConfirmation = isset($_POST['email-confirmation']) ? 1 : 0;
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $transactionType = $_POST['transaction-type'];

    // SQL query to check user credentials
    $sql = "SELECT * FROM ReceptionistTable WHERE RecFirstName = '$firstName' AND RecLastName = '$lastName' AND RecPassword = '$password' AND RecID = '$receptionistId' AND RecPhone = '$phone' AND (RecEmail = '$email' OR '$emailConfirmation' = 0)";

    $result = mysqli_query($con, $sql);

    if ($result !== false) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            // After successful verification
            session_start();
            $_SESSION['authenticated'] = true;
            $_SESSION['firstName'] = $firstName;
            $_SESSION['lastName'] = $lastName;
            $_SESSION['email'] = $email;
            $_SESSION['phone'] = $phone;
            $_SESSION['receptionist-id'] = $receptionistId;


            if ($transactionType === 'search-account') {
                // Return JSON response for success with redirection
                echo json_encode(['status' => 'success', 'message' => 'Verification successful.', 'redirect' => 'search.php']);
                exit();
            } elseif ($transactionType === 'update-information'){
                echo json_encode(['status' => 'success', 'message' => 'Verification successful.', 'redirect' => 'update.php']);
                exit();
            } elseif ($transactionType === 'create-account'){
                echo json_encode(['status' => 'success', 'message' => 'Verification successful.', 'redirect' => 'create.php']);
                exit();
            } elseif ($transactionType === 'book-appointment'){
                echo json_encode(['status' => 'success', 'message' => 'Verification successful.', 'redirect' => 'makeapp.php']);
                exit();
            } elseif($transactionType === 'cancel-appointment'){
                echo json_encode(['status' => 'success', 'message' => 'Verification successful.', 'redirect' => 'cancela.php']);
                exit();
            } elseif($transactionType ==='request-procedures'){
                echo json_encode(['status' => 'success', 'message' => 'Verification successful.', 'redirect' => 'procedure.php']);
                exit();
            }  elseif($transactionType ==="cancel-procedures"){
                echo json_encode(['status' => 'success', 'message' => 'Verification successful.', 'redirect' => 'cancelp.php']);
                exit();
            }else {
                // Return JSON response for success (no redirection for other transaction types)
                echo json_encode(['status' => 'success', 'message' => 'Verification successful.']);
                exit();
            }
        } else {
            // No user found
            echo json_encode(['status' => 'error', 'message' => 'No user found with the provided credentials.']);
        }
    } else {
        // Error in the query
        echo json_encode(['status' => 'error', 'message' => 'Error executing query: ' . mysqli_error($con)]);
    }

    // Close the connection
    mysqli_close($con);
}
?>