<!-- header.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> <!-- Include your common stylesheet -->
    <title>House of Health</title>
    <style>
        body {
            margin: 0; /* Remove default margin to ensure the navbar spans the full width */
        }

        .navbar {
            overflow: hidden;
            background-color: #333;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="search.php">Search Account</a>
    <a href="update.php">Update Information</a>
    <a href="create.php">Create Account</a>
    <a href="makeapp.php">Book Appointment</a>
    <a href="cancela.php">Cancel Appointment</a>
    <a href="procedure.php">Request Procedures</a>
    <a href="cancelp.php">Cancel Procedures</a>
    <!-- Add more links as needed -->
</div>

<!-- The rest of the content will be included in each specific page -->
