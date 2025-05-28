<?php
session_start();
require_once 'connection.php';

// Check login and role
if (
    !isset($_SESSION['role_id']) || 
    (!isset($_SESSION['admin_id']) && !isset($_SESSION['user_id']))
) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = trim($_POST['member_id'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $wallet = floatval($_POST['wallet'] ?? 0);
    $points = intval($_POST['points'] ?? 0);
    $status = strtolower(trim($_POST['status'] ?? 'active'));
    $registered_at = date('Y-m-d H:i:s');
    $payment_slip = '';

    // Handle payment slip upload (image/pdf)
    if (isset($_FILES['payment_slip']) && $_FILES['payment_slip']['error'] === UPLOAD_ERR_OK) {
        $target_dir = 'uploads/payment_slips/';
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $ext = strtolower(pathinfo($_FILES['payment_slip']['name'], PATHINFO_EXTENSION));
        $basename = uniqid('slip_', true) . '.' . $ext;
        $target_file = $target_dir . $basename;
        if (move_uploaded_file($_FILES['payment_slip']['tmp_name'], $target_file)) {
            $payment_slip = $target_file;
        } else {
            $error = "Payment slip upload failed.";
        }
    }

    if (!$member_id || !$first_name || !$last_name || !$email || !$phone) {
        $error = "Please fill in all required fields.";
    }

    if (!$error) {
        $stmt = mysqli_prepare($conn, "
            INSERT INTO membership (member_id, first_name, last_name, email, phone, wallet, points, status, registered_at, payment_slip)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        mysqli_stmt_bind_param($stmt, "sssssdisss",
            $member_id, $first_name, $last_name, $email, $phone, $wallet, $points, $status, $registered_at, $payment_slip);
        if (mysqli_stmt_execute($stmt)) {
            $success = "Member added successfully!";
            header("Location: admin_view_members.php");
            exit;
        } else {
            $error = "Failed to add member: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add Member | Brew & Go Admin</title>
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="styles/admin_activities.css" />
    <style>
      .admin-add-form { max-width:600px; margin:30px auto; background:#f9f9f9; padding:22px 35px 30px 35px; border-radius:13px; box-shadow:0 4px 24px #0001;}
      .admin-add-form label { font-weight:600; margin-top:12px; display:block;}
      .admin-add-form input { width:100%; margin-top:4px; margin-bottom:12px; padding:7px 8px; border-radius:6px; border:1px solid #bbb;}
      .admin-add-form select { width:100%; margin-top:4px; margin-bottom:12px; padding:7px 8px; border-radius:6px; border:1px solid #bbb;}
      .admin-add-form button { background:#2196F3; color:#fff; padding:10px 20px; border:none; border-radius:8px; font-size:16px;}
      .admin-add-form .error { color:#e53e3e; margin-bottom:12px;}
      .admin-add-form .success { color:#38a169; margin-bottom:12px;}
    </style>
</head>
<body class="admin-members-body">
<?php include 'navbar.php'; ?>
<div class="admin-wrapper">
    <?php include 'admin_sidebar.php'; ?>
    <div class="admin-activities-main">
        <header class="admin-activities-topbar">
            <div class="admin-activities-topbar-left">
                <span class="admin-activities-topbar-title">Add Member</span>
            </div>
            <div class="admin-activities-topbar-right">
                <a href="admin_view_members.php" class="admin-activities-back-btn">← Back to Members</a>
            </div>
        </header>
        <form class="admin-add-form" action="add_members.php" method="post" enctype="multipart/form-data">
            <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
            <label for="member_id">Member ID*</label>
            <input type="text" name="member_id" id="member_id" required>

            <label for="first_name">First Name*</label>
            <input type="text" name="first_name" id="first_name" required>

            <label for="last_name">Last Name*</label>
            <input type="text" name="last_name" id="last_name" required>

            <label for="email">Email*</label>
            <input type="email" name="email" id="email" required>

            <label for="phone">Phone*</label>
            <input type="text" name="phone" id="phone" required>

            <label for="wallet">Wallet (RM)</label>
            <input type="number" step="0.01" name="wallet" id="wallet">

            <label for="points">Points</label>
            <input type="number" name="points" id="points">

            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="active" selected>Active</option>
                <option value="expired">Expired</option>
            </select>

            <label for="payment_slip">Payment Slip (Image/PDF)</label>
            <input type="file" name="payment_slip" id="payment_slip" accept="image/*,.pdf">

            <button type="submit">Add Member</button>
        </form>
    </div>
</div>
</body>
</html>
