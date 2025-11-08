<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/db_config.php';
require_once __DIR__ . '/inc/PHPMailer/src/Exception.php';
require_once __DIR__ . '/inc/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/inc/PHPMailer/src/SMTP.php';

// Form data
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
    exit('Name and phone are required.');
}

// Save to database
$stmt = $conn->prepare("INSERT INTO inquiries (name, phone, email, address, date, time, service, message)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $name, $phone, $email, $address, $date, $time, $service, $message);
$stmt->execute();
$stmt->close();

// === PHPMailer setup ===
$mail = new PHPMailer(true);

try {
    // Gmail SMTP config
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'fastfixacandkitchenservice@gmail.com'; // your Gmail
    $mail->Password   = 'afus aers yppx jizf'; // use Gmail App Password (not normal password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Common email settings
    $mail->isHTML(true);
    $mail->setFrom('no-reply@fastfixncr.in', 'FastFixNCR');
    $mail->addReplyTo('no-reply@fastfixncr.in', 'FastFixNCR');

    // === Admin email ===
    $mail->addAddress('fastfixacandkitchenservice@gmail.com', 'FastFix Admin');
    $mail->Subject = "New Service Inquiry for - $service";
    $mail->Body = "
        <div style='font-family:Arial, sans-serif;color:#333;background:#f9f9f9;padding:20px;'>
            <div style='max-width:600px;margin:auto;background:white;padding:20px;border-radius:10px;'>
                <div style='text-align:center;'>
                    <img src='https://fastfixncr.in/assets/images/logo.png' alt='FastFixNCR' width='150'>
                    <h2 style='color:#0b79d0;margin-top:10px;'>New Service Inquiry</h2>
                </div>
                <hr style='border:none;border-top:2px solid #0b79d0;margin:15px 0;'>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Address:</strong> $address</p>
                <p><strong>Service:</strong> $service</p>
                <p><strong>Preferred Date:</strong> $date</p>
                <p><strong>Preferred Time:</strong> $time</p>
                <p><strong>Message:</strong> $message</p>
                <hr style='border:none;border-top:1px solid #ddd;margin:15px 0;'>
                <p style='font-size:12px;color:#777;'>Submitted on " . date('Y-m-d H:i:s') . "</p>
            </div>
        </div>";

        $mail->AltBody = "New inquiry received from $name ($phone) for $service.";

    $mail->send();

    // === Send auto-reply to user ===
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $userMail = new PHPMailer(true);
        $userMail->isSMTP();
        $userMail->Host       = 'smtp.gmail.com';
        $userMail->SMTPAuth   = true;
        $userMail->Username   = 'fastfixacandkitchenservice@gmail.com';
        $userMail->Password   = 'afus aers yppx jizf';
        $userMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $userMail->Port       = 587;

        $userMail->setFrom('no-reply@fastfixncr.in', 'FastFixNCR');
        $userMail->addAddress($email, $name);
        $userMail->isHTML(true);
        $userMail->Subject = 'FastFixNCR - Service Request Received';
        $userMail->Body = "
            <div style='font-family:Arial,sans-serif;color:#333;background:#f9f9f9;padding:20px;'>
                <div style='max-width:600px;margin:auto;background:white;padding:20px;border-radius:10px;'>
                    <div style='text-align:center;'>
                        <img src='https://fastfixncr.in/assets/images/logo.png' alt='FastFixNCR' width='150'>
                        <h2 style='color:#0b79d0;margin-top:10px;'>Service Request Received</h2>
                    </div>
                    <p>Dear <strong>$name</strong>,</p>
                    <p>Thank you for choosing <strong>FastFixNCR</strong>! We've received your inquiry for <strong>$service</strong> and our technician will contact you soon.</p>
                    <p><strong>Submitted details:</strong><br>
                    <p><strong>Service:</strong> $service<br>
                    <strong>Preferred Date:</strong> $date<br>
                    <strong>Preferred Time:</strong> $time</p>
                    <p>For urgent queries, call us at <a href='tel:+919310346037'>9310346037</a>.</p>
                    <hr style='border:none;border-top:1px solid #ddd;margin:15px 0;'>
                    <p style='font-size:12px;color:#777;text-align:center;'>© " . date('Y') . " FastFixNCR. All rights reserved.</p>
                </div>
            </div>";
        $userMail->send();
    }

    echo "Inquiry saved and emails sent successfully.";

} catch (Exception $e) {
    http_response_code(500);
    echo "Mailer Error: {$mail->ErrorInfo}";
}
?>