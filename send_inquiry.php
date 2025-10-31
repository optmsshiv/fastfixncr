<?php
require_once __DIR__ . '/inc/db_config.php';

$name = "Test User";
$phone = "9876543210";
$address = "Test Address";
$service = "AC";
$date = date('Y-m-d');
$time = date('H:i');
$message = "This is a test insert.";

$stmt = $conn->prepare("INSERT INTO inquiries (name, phone, address, service, date, time, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $name, $phone, $address, $service, $date, $time, $message);

if ($stmt->execute()) {
    echo "✅ Test record inserted successfully!";
} else {
    echo "❌ Insert failed: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
