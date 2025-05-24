<?php
session_start();
require_once 'connection.php';

// Only allow admin
if (!isset($_SESSION['admin_id']) || ($_SESSION['role_id'] ?? 0) != 1) {
    header("Location: login.php");
    exit;
}

// Fetch all job applications
$result = mysqli_query($conn, "
    SELECT 
        id, first_name, last_name, email, phone, preferred_shift, address, postcode, city, state, 
        photo_path, cv_path, submitted_at 
    FROM job_application
    ORDER BY submitted_at DESC
");
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
                            <div class="job-details-content">
                                <table class="job-details-table">
                                    <tr>
                                        <td><strong>Address:</strong></td>
                                        <td><?= htmlspecialchars($row['address']) ?></td>
                                        <td><strong>Postcode:</strong></td>
                                        <td><?= htmlspecialchars($row['postcode']) ?></td>
                                        <td><strong>City:</strong></td>
                                        <td><?= htmlspecialchars($row['city']) ?></td>
                                        <td><strong>State:</strong></td>
                                        <td><?= htmlspecialchars($row['state']) ?></td>
                                    </tr>
                                </table>
                                <div class="job-details-flex-media">
                                    <div>
                                        <strong>Photo:</strong><br>
                                        <?php if ($row['photo_path']): ?>
                                            <a href="<?= htmlspecialchars($row['photo_path']) ?>" target="_blank" class="job-photo">
                                                <img src="<?= htmlspecialchars($row['photo_path']) ?>" alt="Photo" class="job-photo-img">
                                            </a>
                                        <?php else: ?>
                                            <span>N/A</span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <strong>CV:</strong><br>
                                        <?php if ($row['cv_path']): ?>
                                            <?php
                                            $cv_path = $row['cv_path'];
                                            $cv_ext = strtolower(pathinfo($row['cv_path'], PATHINFO_EXTENSION));
                                            ?>
                                            <?php if ($cv_ext === 'pdf'): ?>
                                                <div class="jov-cv-wrapper">
                                                    <div class="job-cv-preview">
                                                        <iframe src="<?= htmlspecialchars($cv_path) ?>" class="job-cv-iframe"></iframe>
                                                    </div>
                                                    <div class="job-cv-action-row">
                                                        <a href="<?= htmlspecialchars($cv_path) ?>" target="_blank">
                                                            <button type="button" class="btn-view-pdf">View PDF in New Tab</button>
                                                        </a>
                                                        <a href="<?= htmlspecialchars($cv_path) ?>" download>
                                                            <button type="button" class="btn-download-pdf">Download PDF</button>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <a href="<?= htmlspecialchars($cv_path) ?>" download>
                                                    <button type="button" class="btn-download-pdf">Download</button>
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span>N/A</span>
                                        <?php endif; ?>
                                    </div>
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
