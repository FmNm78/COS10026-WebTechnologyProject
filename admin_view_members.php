<?php
session_start();
require_once 'connection.php';

// Only allow admin
if (!isset($_SESSION['admin_id']) || ($_SESSION['role_id'] ?? 0) != 1) {
    header("Location: login.php");
    exit;
}

// Fetch all members
$result = mysqli_query($conn, "SELECT id, member_id, first_name, last_name, email, phone, wallet, points, status, registered_at, payment_slip FROM membership ORDER BY registered_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>All Members | Brew & Go Admin</title>
    <link rel="stylesheet" href="styles/style.css" />
</head>
<body class="admin-members-body">
    <?php include 'navbar.php' ?>
<div class="admin-wrapper">
    <?php include 'admin_sidebar.php'; ?>
    <div class="admin-main">
        <header class="admin-topbar">
            <div class="admin-topbar-left">
                <span class="admin-topbar-title">All Members</span>
            </div>
            <div class="admin-topbar-right">
                <a href="admin_dashboard.php" class="admin-back-btn">← Back to Dashboard</a>
            </div>
        </header>

        <section class="admin-table-section">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Wallet</th>
                        <th>Points</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Payment Slip</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['member_id']) ?></td>
                        <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td>RM <?= htmlspecialchars(number_format($row['wallet'], 2)) ?></td>
                        <td><?= htmlspecialchars($row['points']) ?></td>
                        <td>
                            <span class="status-badge <?= $row['status'] === 'active' ? 'active' : 'expired' ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($row['registered_at']) ?></td>
                        <td>
                            <?php if ($row['payment_slip']): ?>
                                <?php
                                $ext = strtolower(pathinfo($row['payment_slip'], PATHINFO_EXTENSION));
                                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                    <a href="<?= htmlspecialchars($row['payment_slip']) ?>" target="_blank">
                                        <img src="<?= htmlspecialchars($row['payment_slip']) ?>" alt="Payment Slip" class="admin-view-payment_slip">
                                    </a>
                                <?php elseif ($ext === 'pdf'): ?>
                                    <a href="<?= htmlspecialchars($row['payment_slip']) ?>" target="_blank">
                                        <button type="button" class="btn-view-pdf">View</button>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= htmlspecialchars($row['payment_slip']) ?>" target="_blank" class="admin-view-payment_slip-pdf">View</a>
                                <?php endif; ?>
                            <?php else: ?>
                                <span>N/A</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>
</div>
</body>
</html>
