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
// Fetch all members
// Fetch all members + their roles
$sql = "SELECT m.id, m.member_id, m.first_name, m.last_name, m.email, m.phone, m.wallet, m.points, m.status, m.registered_at, m.payment_slip,
               u.role_id, r.name AS role_name, u.id AS user_id
        FROM membership m
        LEFT JOIN user u ON m.id = u.membership_id
        LEFT JOIN roles r ON u.role_id = r.id
        ORDER BY m.registered_at DESC";
$result = mysqli_query($conn, $sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['new_role_id'])) {
    $user_id = intval($_POST['user_id']);
    $new_role_id = intval($_POST['new_role_id']);
    if ($user_id > 0 && $new_role_id > 0) {
        $update_stmt = mysqli_prepare($conn, "UPDATE user SET role_id = ? WHERE id = ?");
        mysqli_stmt_bind_param($update_stmt, "ii", $new_role_id, $user_id);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);
        // Optional: show a message or redirect to avoid resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch all possible roles for dropdown
$role_options = [];
$res = mysqli_query($conn, "SELECT id, name FROM roles ORDER BY id ASC");
while ($r = mysqli_fetch_assoc($res)) $role_options[$r['id']] = $r['name'];
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
        <div class="admin-activities-main">
        <header class="admin-activities-topbar">
            <div class="admin-activities-topbar-left">
                <span class="admin-activities-topbar-title">All Members</span>
            </div>
            <div class="admin-activities-topbar-right">
                <a href="add_members.php" class="admin-activities-add-btn" style="margin-left:15px;">＋ Add New Member</a>
                <a href="admin_dashboard.php" class="admin-activities-back-btn">← Back to Dashboard</a>
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
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Payment Slip</th>
                        <th>Actions</th>
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
                            <span class="status-badge <?= $row['status'] === 'active' ? 'active' : 'inactive' ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($row['user_id']): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                    <select name="new_role_id" onchange="this.form.submit()" style="min-width:90px;">
                                        <?php foreach ($role_options as $rid => $rname): ?>
                                            <option value="<?= $rid ?>" <?= $row['role_id'] == $rid ? 'selected' : '' ?>>
                                                <?= htmlspecialchars(ucfirst($rname)) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            <?php else: ?>
                                <span style="color:#aaa;">No user</span>
                            <?php endif; ?>
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
                        <td>
                            <a href="edit_members.php?id=<?= $row['id'] ?>" class="admin-activities-btn-edit">Edit</a>
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
