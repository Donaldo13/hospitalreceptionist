<?php
include 'connection.php';


// Check if the user is authenticated
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Redirect to the login page or perform authentication
    header("Location: verify_login.php");
    exit();
}

include 'header.php';

// Retrieve data from bigTable1
$query = "SELECT * FROM bigTable1";
$result = mysqli_query($con, $query);

// HTML header with background styling
echo "<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<link rel='stylesheet' href='style2.css'>
<style>
    table {
        border-collapse: collapse;
        width: 80%;
        margin: 20px auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add box shadow */
    }

    th, td {
        border: 1px solid white;
        padding: 12px; /* Increase padding for better spacing */
        text-align: left;
        font-size: 14px;
        color: white; /* Set font color to white */
    }

    th {
        background-color: #0074d9;
    }
</style>
<title>Search Receptionist's Account - House of Health</title>
<script>
    function showBigTable() {
        document.getElementById('big-table').style.display = 'table';
        document.getElementById('apps-table').style.display = 'none';
    }

    function showAppsTable() {
        document.getElementById('big-table').style.display = 'none';
        document.getElementById('apps-table').style.display = 'table';
    }
</script>
</head>
<body>
";

// Buttons to toggle between tables
echo "<button onclick='showBigTable()'>Big data Table</button>";
echo "<button onclick='showAppsTable()'>Updated Appointments and Procedures</button>";

// Display bigTable1 data in a table
echo "<table id='big-table'>
    <tr>
        <th>Receptionist First Name</th>
        <th>Receptionist Last Name</th>
        <th>Receptionist ID</th>
        <th>Receptionist Phone</th>
        <th>Receptionist Email</th>
        <th>Patient First Name</th>
        <th>Patient Last Name</th>
        <th>Patient ID</th>
        <th>DOB</th>
        <th>Age</th>
        <th>Phone and Address</th>
        <th>Shots</th>
        <th>Illnesses</th>
    </tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>{$row['Receptionist_FirstName']}</td>";
    echo "<td>{$row['Receptionist_LastName']}</td>";
    echo "<td>{$row['Receptionist_ID']}</td>";
    echo "<td>{$row['Receptionist_Phone']}</td>";
    echo "<td>{$row['Receptionist_Email']}</td>";
    echo "<td>{$row['Patient_FirstName']}</td>";
    echo "<td>{$row['Patient_LastName']}</td>";
    echo "<td>{$row['Patient_ID']}</td>";
    echo "<td>{$row['DOB']}</td>";
    echo "<td>{$row['Age']}</td>";
    echo "<td>{$row['Phone_Address']}</td>";
    echo "<td>{$row['Shots']}</td>";
    echo "<td>{$row['Illnesses']}</td>";
    echo "</tr>";
}

echo "</table>";

// Retrieve data from Apps table
$appsQuery = "SELECT * FROM Apps";
$appsResult = mysqli_query($con, $appsQuery);

// Display Apps table data in a table (formatted like bigTable1)
echo "<table id='apps-table' style='display: none;'>
    <tr>
        <th>Patient First Name</th>
        <th>Patient Last Name</th>
        <th>Appointment ID</th>
        <th>Appointment Type</th>
        <th>Appointment Date</th>
        <th>Procedure Type</th>
        <th>Procedure Date</th>
        <th>Doctor's Name</th>
        <th>Procedure ID</th>
    </tr>";

while ($row = mysqli_fetch_assoc($appsResult)) {
    echo "<tr>";
    echo "<td>{$row['PatF']}</td>";
    echo "<td>{$row['PatL']}</td>";
    echo "<td>{$row['ApptID']}</td>";
    echo "<td>{$row['Appt']}</td>";
    echo "<td>{$row['ApptDate']}</td>";
    echo "<td>{$row['Proc']}</td>";
    echo "<td>{$row['ProcDate']}</td>";
    echo "<td>{$row['DocName']}</td>";
    echo "<td>{$row['ProcID']}</td>";
    echo "</tr>";
}

echo "</table>";

// Close the connection
mysqli_close($con);

echo "</body>
</html>";
?>
