﻿<?php
// Database configuration
$host = "localhost";
$username = "your_cpanel_db_user";
$password = "your_db_password";
$database = "your_database_name";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
