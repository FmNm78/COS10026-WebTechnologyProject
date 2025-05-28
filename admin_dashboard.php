<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define INBOUND for connection.php security
define('INBOUND', true);

include 'connection.php';
include 'auth.php';

$currentPage = basename($_SERVER['PHP_SELF']);

// Set timezone for correct revenue date calculation
date_default_timezone_set('Asia/Kuala_Lumpur');

// Check login and role
if (
    !isset($_SESSION['role_id']) ||
    (!isset($_SESSION['admin_id']) && !isset($_SESSION['user_id']))
) {
    error_log("admin_dashboard.php: Session check failed. role_id: " . ($_SESSION['role_id'] ?? 'not set'));
    header("Location: login.php");
    exit;
}

// Check page permission by role
if (!checkPagePermission($conn, $currentPage, $_SESSION['role_id'])) {
    error_log("admin_dashboard.php: Permission denied for role_id: " . ($_SESSION['role_id'] ?? 'not set') . ", page: $currentPage");
    header("Location: no_access.php");
    exit;
}

// Fetch basic stats using prepared statements
$stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM user");
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $user_count);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM enquiry");
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $enquiry_count);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Latest 5 enquiries using prepared statement
$stmt = mysqli_prepare($conn, "SELECT ticket_id, first_name, last_name, email, enquiry_type, submitted_at FROM enquiry ORDER BY submitted_at DESC LIMIT 5");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$latest_enquiries = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Today's revenue
$today = date('Y-m-d');
$stmt = mysqli_prepare($conn, "SELECT SUM(amount) FROM topup_history WHERE DATE(created_at) = ?");
mysqli_stmt_bind_param($stmt, "s", $today);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $today_revenue);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
$today_revenue = $today_revenue ?? 0.00;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | Brew & Go</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body class="admin-dashboard-body">
    <?php include 'navbar.php'; ?>
    <div class="admin-wrapper">
        <?php include 'admin_sidebar.php'; ?>
        <div class="admin-activities-main">
            <header class="admin-activities-topbar">
                <div class="admin-activities-topbar-left">
                    <span class="admin-activities-topbar-title">Dashboard</span>
                </div>
            </header>
            <!-- Stat Cards -->
            <section class="admin-cards-row">
                <div class="admin-card">
                    <h4>Total Users</h4>
                    <div class="admin-card-stat"><?= htmlspecialchars($user_count) ?></div>
                </div>
                <div class="admin-card">
                    <h4>Today's Revenue</h4>
                    <div class="admin-card-stat">RM <?= number_format($today_revenue, 2) ?></div>
                </div>
                <div class="admin-card">
                    <h4>Enquiries</h4>
                    <div class="admin-card-stat"><?= htmlspecialchars($enquiry_count) ?></div>
                </div>
            </section>

            <!-- Latest Enquiries Table -->
            <section class="admin-table-section">
                <h3>Latest Enquiries</h3>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($latest_enquiries as $row): ?>
                            <tr onclick="window.location='admin_view_enquiries.php?highlight=<?= urlencode($row['ticket_id']) ?>#<?= htmlspecialchars($row['ticket_id']) ?>';" style="cursor:pointer">
                                <td><?= htmlspecialchars($row['ticket_id']) ?></td>
                                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['enquiry_type']) ?></td>
                                <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</body>
</html>