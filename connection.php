<?php
$serverName = "localhost";
$userName = "root";
$password = "";
$database = "cricket_club_database";

// Create connection
$conn = mysqli_connect($serverName, $userName, $password, $database);

// Check connection
if (!$conn)
{
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";
