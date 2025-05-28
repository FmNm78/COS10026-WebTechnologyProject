<?php
session_start();
require_once 'connection.php';
require_once 'auth.php';

$currentPage = basename($_SERVER['PHP_SELF']);

// Set timezone for correct revenue date calculation!
date_default_timezone_set('Asia/Kuala_Lumpur');

// Check login and role
if (
    !isset($_SESSION['role_id']) || 
    (!isset($_SESSION['admin_id']) && !isset($_SESSION['user_id']))
) {
    header("Location: login.php");
    exit;
}

// **Check page permission by role!**
if (!checkPagePermission($conn, $currentPage, $_SESSION['role_id'])) {
    header("Location: no_access.php");
    exit;
}

$feedback = '';

// === PHPMailer include & settings ===
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;

// SMTP Settings (edit these for your sender!)
$mail_host = 'smtp.gmail.com';             // e.g. smtp.gmail.com
$mail_username = 'zapydevtest@gmail.com';  // your sending address
$mail_password = 'zebw zcxr vesx fvsc';      // app password or SMTP password
$mail_from = 'zapydevtest@gmail.com';   // sender shown to recipients
$mail_from_name = 'Brew & Go Newsletter';  // display name

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $attachment_path = '';

    // Handle file upload (if any)
    if (!empty($_FILES['attachment']['name']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
        $ext = strtolower(pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed_ext)) {
            $target_dir = 'uploads/newsletter/';
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $basename = uniqid('newsletter_', true) . '.' . $ext;
            $attachment_path = $target_dir . $basename;
            move_uploaded_file($_FILES['attachment']['tmp_name'], $attachment_path);
        } else {
            $feedback = "<span style='color:#c0392b;'>Attachment type not allowed. Only image/PDF accepted.</span>";
        }
    }

    if ($subject && $body && !$feedback) {
        // Get all subscriber emails
        $emails = [];
        $res = mysqli_query($conn, "SELECT email FROM newsletter_subscribers");
        while ($row = mysqli_fetch_assoc($res)) {
            $emails[] = $row['email'];
        }

        $email_count = 0;
        $send_errors = [];
        foreach ($emails as $to) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = $mail_host;
                $mail->SMTPAuth = true;
                $mail->Username = $mail_username;
                $mail->Password = $mail_password;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom($mail_from, $mail_from_name);
                $mail->addAddress($to);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $body;

                // Add attachment if any
                if ($attachment_path && file_exists($attachment_path)) {
                    $mail->addAttachment($attachment_path);
                }

                $mail->send();
                $email_count++;
            } catch (Exception $e) {
                $send_errors[] = "Email to $to failed: " . $mail->ErrorInfo;
            }
        }

        if (empty($send_errors)) {
            $feedback = "<span style='color:#27ae60;'>Newsletter sent to $email_count subscribers.</span>";
        } else {
            $feedback = "<span style='color:#c0392b;'>Some emails failed:<br>" . implode('<br>', $send_errors) . "</span>";
        }
    } else if (!$subject || !$body) {
        $feedback = "<span style='color:#c0392b;'>Please enter subject and message.</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Newsletter Panel | Brew & Go Admin</title>
    <link rel="stylesheet" href="styles/style.css" />
</head>
<body class="admin-members-body">
<?php include 'navbar.php'; ?>
<div class="admin-wrapper">
    <?php include 'admin_sidebar.php'; ?>
    <div class="admin-activities-main">
        <header class="admin-activities-topbar">
            <div class="admin-activities-topbar-left">
                <span class="admin-activities-topbar-title">Newsletter Panel</span>
            </div>
            <div class="admin-activities-topbar-right">
                <a href="admin_dashboard.php" class="admin-activities-back-btn">← Back to Dashboard</a>
            </div>
        </header>
        <form class="admin-newsletter-panel" action="admin_newsletter.php" method="post" enctype="multipart/form-data">
            <?php if ($feedback): ?>
                <div class="feedback"><?= $feedback ?></div>
            <?php endif; ?>
            <label for="subject">Subject*</label>
            <input type="text" name="subject" id="subject" required>

            <label for="body">Message</label>
            <textarea name="body" id="body" rows="8" required placeholder="Write your newsletter here. HTML allowed for formatting, links, etc."></textarea>

            <label for="attachment">Attachment (image or PDF, optional)</label>
            <input type="file" name="attachment" id="attachment" accept="image/*,.pdf">

            <button type="submit">Send Newsletter</button>
        </form>
    </div>
</div>
</body>
</html>
