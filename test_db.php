<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/inc/db_config.php';

if (!isset($conn)) {
    die("❌ Database connection variable not found.");
}

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
} else {
    echo "✅ Database connected successfully to: " . $conn->host_info;
}
?>
