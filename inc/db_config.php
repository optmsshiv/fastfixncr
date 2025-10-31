<?php
// Database configuration
$host = "localhost";
$username = "edrppymy_fastfix";
$password = "123@Fastfix";
$database = "edrppymy_fastfix";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
