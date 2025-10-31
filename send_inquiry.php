<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include DB config and PHPMailer
require_once __DIR__ . '/inc/db_config.php';
require_once __DIR__ . '/inc/PHPMailer/src/Exception.php';
require_once __DIR__ . '/inc/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/inc/PHPMailer/src/SMTP.php';

// Validate input
$name     = trim($_POST['name'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$email    = trim($_POST['email'] ?? '');
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
$stmt->bind_param("ssssssss", $name, $phone, $email, $address, $date, $time, $service, $message);

if ($stmt->execute()) {

        // ✅ Configure PHPMailer
    $mail = new PHPMailer(true);
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'fastfixacandkitchenservice@gmail.com'; // <-- your Gmail address
        $mail->Password   = 'afus aers yppx jizf';   // <-- your 16-char app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // From
        $mail->setFrom('fastfixacandkitchenservice@gmail.com', 'FastFix NCR');

    // Admin notification
        $mail->addAddress('fastfixacandkitchenservice@gmail.com', 'FastFix Admin');
        $mail->Subject = "New Service Inquiry - FastFix";
        $mail->Body    = "
        New inquiry received:\n\n
        Name: $name\n
        Phone: $phone\n
        Email: $email\n
        Address: $address\n
        Service: $service\n
        Preferred Date: $date\n
        Preferred Time: $time\n
        Message: $message\n\n

        Submitted on " . date('Y-m-d H:i:s');
        $mail->send();

    // --- Auto-reply to User ---
        $mail->send();

        // --- Auto-reply to User ---
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $reply = new PHPMailer(true);
            $reply->isSMTP();
            $reply->Host       = 'smtp.gmail.com';
            $reply->SMTPAuth   = true;
            $reply->Username   = 'yourgmail@gmail.com';
            $reply->Password   = 'your_app_password';
            $reply->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $reply->Port       = 587;

            $reply->setFrom('yourgmail@gmail.com', 'FastFix Support');
            $reply->addAddress($email, $name);
            $reply->Subject = "FastFix - Your Service Request Confirmation";
            $reply->Body = "
Dear $name,

Thank you for contacting FastFix!

We’ve received your service inquiry. Here are your details:

Service: $service
Preferred Date: $date
Preferred Time: $time
Address: $address

Our team will contact you soon at $phone.

Regards,
FastFix Support
support@fastfixncr.com
";

            $reply->send();
        }

        echo "success";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Mailer Error: " . $mail->ErrorInfo;
    }

} else {
    http_response_code(500);
    echo "Database error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>