<?php
// Include DB config
require_once __DIR__ . '/inc/db_config.php';

// Validate input
$name     = trim($_POST['name'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$address  = trim($_POST['address'] ?? '');
$service  = trim($_POST['service'] ?? '');
$date     = trim($_POST['preferred_date'] ?? '');
$time     = trim($_POST['preferred_time'] ?? '');
$message  = trim($_POST['message'] ?? '');

if (empty($name) || empty($phone)) {
    http_response_code(400);
    echo "Name and phone are required.";
    exit;
}

// Prepare and insert data
$stmt = $conn->prepare("INSERT INTO inquiries (name, phone, address, date, time, service, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $name, $phone, $address, $date, $time, $service, $message);

if ($stmt->execute()) {
    // --- Admin Email ---
    $adminEmail = "info@optms.co.in"; // <-- your email
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
    $headers .= "Reply-To: no-reply@fastfixncr.in\r\n";

    @mail($adminEmail, $subject, $body, $headers);

    // --- Auto-reply to User ---
    // Optional: if you collect email, replace with user's email; for now, we'll send SMS-style acknowledgment via phone only if you use email field later
    // But since we don't have user's email, we skip direct mail reply unless added later.
    // If you later add an email field, uncomment the below lines:

    /*
    if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $userEmail = $_POST['email'];
        $userSubject = "FastFix - Service Inquiry Confirmation";
        $userBody = "Dear $name,\n\nThank you for contacting FastFix!\n\nHere are your booking details:\n
Service: $service\nPreferred Date: $date\nPreferred Time: $time\nAddress: $address\n\n
Our team will reach out to you shortly at $phone.\n\nRegards,\nFastFix Support\nsupport@fastfixncr.com";
        $userHeaders = "From: FastFix <no-reply@yourdomain.com>\r\n";
        @mail($userEmail, $userSubject, $userBody, $userHeaders);
    }
    */

    // For now, return success (JS will handle UI message)
    echo "success";

} else {
    http_response_code(500);
    echo "Database error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
