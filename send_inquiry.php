<?php
// Include DB config
require_once __DIR__ . '/inc/db_config.php';

// Validate input
$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$service = trim($_POST['service'] ?? '');
$date = trim($_POST['preferred_date'] ?? '');
$time = trim($_POST['preferred_time'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($name) || empty($phone)) {
    die("Error: Name and phone are required.");
}

// Prepare and insert data
$stmt = $conn->prepare("INSERT INTO inquiries (name, phone, address, date, time, service, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $name, $phone, $address, $service, $date, $time, $message);

if ($stmt->execute()) {
    // Send Email
    $to = "sr21er@gmail.com"; // <-- replace with your actual email
    $subject = "New Service Inquiry - FastFix";
    $body = "
        New inquiry received:\n\n
        Name: $name\n
        Phone: $phone\n
        Address: $address\n
        Service: $service\n
        Preferred Date: $date\n
        Preferred Time: $time\n
        Message: $message\n\n
        Submitted on " . date('Y-m-d H:i:s');
    $headers = "From: FastFix Website <no-reply@yourdomain.com>\r\n";
    $headers .= "Reply-To: $name <$phone>\r\n";

    @mail($to, $subject, $body, $headers);

    echo "<!doctype html><html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>Thank You</title>
    <style>body{font-family:sans-serif;text-align:center;padding:40px;color:#333;}a{color:#0b79d0;font-weight:bold;text-decoration:none;}</style>
    </head><body>
    <h2>Thank you, $name!</h2>
    <p>Your inquiry has been submitted successfully.</p>
    <p>We'll contact you shortly at <strong>$phone</strong>.</p>
    <p><a href='index.html'>&larr; Back to Home</a></p>
    </body></html>";
} else {
    echo "Database error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
