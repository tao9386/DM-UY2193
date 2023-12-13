<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel";

// create a database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// check if the connection was successful
if (!$conn) {
    die("ERROR--Connection failed: " . mysqli_connect_error());
}
?>