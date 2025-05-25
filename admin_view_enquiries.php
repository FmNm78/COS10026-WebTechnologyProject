<?php
session_start();
require_once 'connection.php';

// Only allow admin access
if (!isset($_SESSION['admin_id']) || ($_SESSION['role_id'] ?? 0) != 1) {
    header("Location: login.php");
    exit;
}

// --- Handle status update ---
if (isset($_POST['update_status'], $_POST['enquiry_id'], $_POST['status'])) {
    $enquiry_id = intval($_POST['enquiry_id']);
    $allowed = ['Pending', 'In Progress', 'Resolved'];
    $status = in_array($_POST['status'], $allowed) ? $_POST['status'] : 'Pending';
    $stmt = mysqli_prepare($conn, "UPDATE enquiry SET status = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $status, $enquiry_id);
    mysqli_stmt_execute($stmt);
}

// Fetch all enquiries (with status)
$result = mysqli_query($conn, "SELECT id, ticket_id, first_name, last_name, email, phone, enquiry_type, message, submitted_at, status FROM enquiry ORDER BY submitted_at DESC");
$highlight = $_GET['highlight'] ?? '';
$show_id = isset($_GET['show']) ? intval($_GET['show']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Enquiries | Brew & Go Admin</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body class="admin-view-enquiries-body">
    <?php include 'navbar.php' ?>
<div class="admin-wrapper">
    <?php include 'admin_sidebar.php';?>
    <div class="admin-main">

        <header class="admin-topbar">
            <div class="admin-topbar-left">
                <span class="admin-topbar-title">All Enquiries</span>
            </div>
            <div class="admin-topbar-right">
                <a href="admin_dashboard.php" class="admin-back-btn">← Back to Dashboard</a>
            </div>
        </header>
        <div class="aside-right">
            <aside class="status-legend">
                <strong>Status Legend:</strong>
                <span class="legend-box row-status-pending">Pending</span>
                <span class="legend-box row-status-inprogress">In Progress</span>
                <span class="legend-box row-status-resolved">Resolved</span>
            </aside>
        </div>
        <section class="admin-table-section">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ticket ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Type</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)):
                        $is_highlight = ($highlight && $row['ticket_id'] === $highlight);
                    ?>
                        <tr id="<?= htmlspecialchars($row['ticket_id']) ?>"
                            class="<?= $is_highlight ? 'highlight-row' : '' ?> row-status-<?= strtolower(str_replace(' ', '', $row['status'])) ?>">
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['ticket_id']) ?></td>
                            <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td><?= htmlspecialchars($row['enquiry_type']) ?></td>
                            <td style="max-width:320px;overflow-wrap:anywhere">
                                <?php if ($show_id == $row['id']): ?>
                                    <div class="admin-msg-content"><?= nl2br(htmlspecialchars($row['message'])) ?></div>
                                    <a href="admin_view_enquiries.php" class="view-msg-btn" style="margin-top:7px;">Hide</a>
                                <?php else: ?>
                                    <a href="admin_view_enquiries.php?show=<?= $row['id'] ?>" class="view-msg-btn">View</a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST" class="enquiry-status-form">
                                    <input type="hidden" name="enquiry_id" value="<?= $row['id'] ?>">
                                    <select name="status">
                                    <option value="Pending"     <?= $row['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="In Progress" <?= $row['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="Resolved"    <?= $row['status'] === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn-update-status">Update</button>
                                </form>
                                </td>
                            <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>
</div>
</body>
</html>
