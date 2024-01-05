<?php
//Makes DB connection
$servername = "sql1.njit.edu";
$username = "db594";
$password = "Csgo.odin50kills";
$dbname = "db594";
$con = mysqli_connect($servername,$username,$password,$dbname);
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
} 
?>