<?php
session_start();
require_once 'connection.php';

// Only allow admin access
if (!isset($_SESSION['admin_id']) || ($_SESSION['role_id'] ?? 0) != 1) {
    header("Location: login.php");
    exit;
}

// Fetch all enquiries
$result = mysqli_query($conn, "SELECT id, ticket_id, first_name, last_name, email, phone, enquiry_type, message, submitted_at FROM enquiry ORDER BY submitted_at DESC");
$highlight = $_GET['highlight'] ?? '';

// Get the ID of the enquiry to show the message for
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
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $is_highlight = ($highlight && $row['ticket_id'] === $highlight);
                    ?>
                        <tr id="<?= htmlspecialchars($row['ticket_id']) ?>" class="<?= $is_highlight ? 'highlight-row' : '' ?>">
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
