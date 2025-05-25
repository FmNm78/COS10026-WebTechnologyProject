<?php
session_start();
require_once 'connection.php';

// Only allow admin
if (!isset($_SESSION['admin_id']) || ($_SESSION['role_id'] ?? 0) != 1) {
    header("Location: login.php");
    exit;
}

// Handle status update
if (isset($_POST['update_status'], $_POST['job_id'], $_POST['status'])) {
    $job_id = intval($_POST['job_id']);
    $allowed = ['Pending', 'In Progress', 'Resolved'];
    $status = in_array($_POST['status'], $allowed) ? $_POST['status'] : 'Pending';
    $stmt = mysqli_prepare($conn, "UPDATE job_application SET status = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $status, $job_id);
    mysqli_stmt_execute($stmt);
}

// Fetch all job applications
$result = mysqli_query($conn, "
    SELECT 
        id, first_name, last_name, email, phone, preferred_shift, address, postcode, city, state, 
        photo_path, cv_path, submitted_at
    FROM job_application
    ORDER BY submitted_at DESC");



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>All Job Applications | Brew & Go Admin</title>
    <link rel="stylesheet" href="styles/style.css" />
    <script>
        function toggleDetails(rowId) {
            var d = document.getElementById('details-' + rowId);
            d.style.display = d.style.display === 'table-row' ? 'none' : 'table-row';
        }
    </script>
</head>
<body class="admin-members-body">
    <?php include 'navbar.php'; ?>
    <div class="admin-wrapper">
    <?php include 'admin_sidebar.php'; ?>
    <div class="admin-main">
        <header class="admin-topbar">
            <div class="admin-topbar-left">
                <span class="admin-topbar-title">All Job Applications</span>
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Preferred Shift</th>
                        <th>Submitted</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['preferred_shift']) ?></td>
                        <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                        <td>
                            <button class="btn-view-details" onclick="toggleDetails(<?= $row['id'] ?>)">View</button>
                        </td>
                    </tr>
                    <tr id="details-<?= $row['id'] ?>" class="job-details-row">
                        <td colspan="7">
                            <div class="job-details-card">
                                <div class="job-details-cv">
                                    <h4>Applicant Photo & Details</h4>
                                </div>
                                <div class="job-details-photo-column">
                                <div class="job-details-row-main">
                                    <!-- LEFT: PHOTO -->
                                    <div class="job-details-left">
                                        <img src="<?= $row['photo_path'] ? htmlspecialchars($row['photo_path']) : 'images/default-profile.png' ?>" alt="Applicant Photo" class="job-details-photo">
                                    </div>
                                    <!-- RIGHT: ALL DETAILS AS TABLE -->
                                    <div class="job-details-right">
                                        <table class="job-details-table">
                                            <tr>
                                                <td><b>Name</b></td>
                                                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Email</b></td>
                                                <td><?= htmlspecialchars($row['email']) ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Phone</b></td>
                                                <td><?= htmlspecialchars($row['phone']) ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Shift</b></td>
                                                <td><?= htmlspecialchars($row['preferred_shift']) ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Address</b></td>
                                                <td><?= htmlspecialchars($row['address']) ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Postcode</b></td>
                                                <td><?= htmlspecialchars($row['postcode']) ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>City</b></td>
                                                <td><?= htmlspecialchars($row['city']) ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>State</b></td>
                                                <td><?= htmlspecialchars($row['state']) ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Submitted</b></td>
                                                <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                                            </tr>
                                            <tr>
                                        </tr>
                                        </table>
                                    </div>
                                </div>
                                </div>
                                <!-- BOTTOM: CV SECTION -->
                                <div class="job-details-cv">
                                    <h4>CV / Resume</h4>
                                    <?php if ($row['cv_path'] && strtolower(pathinfo($row['cv_path'], PATHINFO_EXTENSION)) === 'pdf'): ?>
                                        <div class="job-cv-action-row">
                                            <a href="<?= htmlspecialchars($row['cv_path']) ?>" target="_blank">
                                                <button type="button" class="btn-view-pdf">View PDF in New Tab</button>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <a href="<?= htmlspecialchars($row['cv_path']) ?>" download>
                                            <button type="button" class="btn-download-pdf">Download</button>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
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
