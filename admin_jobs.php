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
    <style>
        .job-details-row { display: none; background: #f8f9fa; }
        .job-details-content { padding: 18px 24px; }
        .admin-view-payment_slip, .job-photo-img { max-width:60px;max-height:60px;border-radius:6px;}
        .job-details-table { width: 100%; border-collapse: collapse; }
        .job-details-table td { padding: 6px 12px; border-bottom: 1px solid #eee; }
        .btn-view-details {
            background: #2f0ba3;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 6px 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-view-details:hover { background: #1c0870; }
    </style>
    <script>
        function toggleDetails(rowId) {
            var d = document.getElementById('details-'+rowId);
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
                                <!-- Vertically stack media -->
                                <div class="job-details-flex-media">
                                    <div>
                                        <strong>Photo:</strong><br>
                                        <?php if ($row['photo_path']): ?>
                                            <a href="<?= htmlspecialchars($row['photo_path']) ?>" target="_blank" class="job-photo">
                                                <img src="<?= htmlspecialchars($row['photo_path']) ?>" alt="Photo" >
                                            </a>
                                        <?php else: ?>
                                            <span>N/A</span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <strong>CV:</strong><br>
                                        <?php if ($row['cv_path']): ?>
                                            <?php
                                            $cv_path = htmlspecialchars($row['cv_path']);
                                            $cv_ext = strtolower(pathinfo($row['cv_path'], PATHINFO_EXTENSION));
                                            if ($cv_ext === 'pdf'): ?>
                                                <!-- View PDF (new tab) -->
                                                <a href="<?= $cv_path ?>" target="_blank">
                                                    <button type="button" class="btn-view-pdf">View PDF</button>
                                                </a>
                                                <!-- Download PDF -->
                                                <a href="<?= $cv_path ?>" download>
                                                    <button type="button" class="btn-download-pdf">Download PDF</button>
                                                </a>
                                                <!-- Inline PDF preview -->
                                                <div class="job-cv-preview">
                                                    <iframe src="<?= $cv_path ?>" width="100%" height="300" style="border:0;border-radius:8px;" allowfullscreen webkitallowfullscreen></iframe>
                                                </div>
                                            <?php else: ?>
                                                <!-- For other file types: Download only -->
                                                <a href="<?= $cv_path ?>" download>
                                                    <button type="button" class="btn-download-pdf">Download</button>
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span>N/A</span>
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
