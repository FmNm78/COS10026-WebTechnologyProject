<?php
session_start();
require_once 'connection.php';
require_once 'auth.php';

$currentPage = basename($_SERVER['PHP_SELF']);
date_default_timezone_set('Asia/Kuala_Lumpur');

// Check login and role
if (
    !isset($_SESSION['role_id']) ||
    (!isset($_SESSION['admin_id']) && !isset($_SESSION['user_id']))
) {
    header("Location: login.php");
    exit;
}
if (!checkPagePermission($conn, $currentPage, $_SESSION['role_id'])) {
    header("Location: no_access.php");
    exit;
}

// Handle status update
if (isset($_POST['update_status'], $_POST['job_id'], $_POST['status'])) {
    $job_id = intval($_POST['job_id']);
    $allowed = ['Pending', 'Accepted', 'Rejected'];
    $status = in_array($_POST['status'], $allowed) ? $_POST['status'] : 'Pending';
    $stmt = mysqli_prepare($conn, "UPDATE job_application SET status = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $status, $job_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: $currentPage");
    exit;
}

// Fetch by status
function fetchJobsByStatus($conn, $status) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM job_application WHERE status = ? ORDER BY submitted_at DESC");
    mysqli_stmt_bind_param($stmt, "s", $status);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $jobs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $jobs[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $jobs;
}

$pendingJobs = fetchJobsByStatus($conn, "Pending");
$acceptedJobs = fetchJobsByStatus($conn, "Accepted");
$rejectedJobs = fetchJobsByStatus($conn, "Rejected");

// Which row to show?
$show_id = isset($_GET['show']) ? intval($_GET['show']) : 0;

// Helper for rendering the job table
function renderJobTable($jobs, $show_id)
{
    if (!$jobs || count($jobs) == 0) {
        echo "<tr class='admin-jobs-empty-row'><td colspan='8'>No applications in this section.</td></tr>";
        return;
    }
    foreach ($jobs as $row):
        $expanded = ($show_id === intval($row['id']));
        ?>
        <tr class="admin-jobs-table-row admin-jobs-status-<?= strtolower($row['status']) ?>">
            <td class="admin-jobs-td"><?= htmlspecialchars($row['id']) ?></td>
            <td class="admin-jobs-td"><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
            <td class="admin-jobs-td"><?= htmlspecialchars($row['email']) ?></td>
            <td class="admin-jobs-td"><?= htmlspecialchars($row['phone']) ?></td>
            <td class="admin-jobs-td"><?= htmlspecialchars($row['preferred_shift']) ?></td>
            <td class="admin-jobs-td"><?= htmlspecialchars($row['submitted_at']) ?></td>
            <td class="admin-jobs-td">
                <span class="admin-jobs-status-pill admin-jobs-status-<?= strtolower($row['status']) ?>">
                    <?= htmlspecialchars($row['status']) ?>
                </span>
            </td>
            <td class="admin-jobs-td">
                <form method="post" class="admin-jobs-status-form" style="display:inline">
                    <input type="hidden" name="job_id" value="<?= $row['id'] ?>">
                    <select name="status" class="admin-jobs-status-dropdown">
                        <option value="Pending"   <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Accepted"  <?= $row['status'] == 'Accepted' ? 'selected' : '' ?>>Accepted</option>
                        <option value="Rejected"  <?= $row['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                    <button type="submit" name="update_status" class="admin-jobs-btn admin-jobs-btn-update">Update</button>
                </form>
                <?php if ($expanded): ?>
                    <a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="admin-jobs-btn admin-jobs-btn-view">Hide</a>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?show=' . $row['id'] ?>" class="admin-jobs-btn admin-jobs-btn-view">View</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php if ($expanded): ?>
        <tr class="admin-jobs-details-row">
            <td colspan="8" class="admin-jobs-details-td">
                <div class="admin-jobs-details-card">
                    <div class="admin-jobs-details-photo-col">
                        <img src="<?= $row['photo_path'] ? htmlspecialchars($row['photo_path']) : 'images/default-profile.png' ?>" alt="Applicant Photo" class="admin-jobs-details-photo">
                    </div>
                    <div class="admin-jobs-details-info-col">
                        <table class="admin-jobs-details-table">
                            <tr><td><b>Name</b></td><td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td></tr>
                            <tr><td><b>Email</b></td><td><?= htmlspecialchars($row['email']) ?></td></tr>
                            <tr><td><b>Phone</b></td><td><?= htmlspecialchars($row['phone']) ?></td></tr>
                            <tr><td><b>Shift</b></td><td><?= htmlspecialchars($row['preferred_shift']) ?></td></tr>
                            <tr><td><b>Address</b></td><td><?= htmlspecialchars($row['address']) ?></td></tr>
                            <tr><td><b>Postcode</b></td><td><?= htmlspecialchars($row['postcode']) ?></td></tr>
                            <tr><td><b>City</b></td><td><?= htmlspecialchars($row['city']) ?></td></tr>
                            <tr><td><b>State</b></td><td><?= htmlspecialchars($row['state']) ?></td></tr>
                            <tr><td><b>Submitted</b></td><td><?= htmlspecialchars($row['submitted_at']) ?></td></tr>
                        </table>
                        <div class="admin-jobs-cv-section">
                            <h4>CV / Resume</h4>
                            <?php if ($row['cv_path'] && strtolower(pathinfo($row['cv_path'], PATHINFO_EXTENSION)) === 'pdf'): ?>
                                <div class="admin-jobs-cv-action-row">
                                    <embed src="<?= htmlspecialchars($row['cv_path']) ?>" type="application/pdf" width="100%" height="400px" class="admin-jobs-cv-preview"/>
                                    <div style="margin-top:8px;">
                                        <a href="<?= htmlspecialchars($row['cv_path']) ?>" target="_blank" class="admin-jobs-btn admin-jobs-btn-pdf">Open PDF in New Tab</a>
                                        <a href="<?= htmlspecialchars($row['cv_path']) ?>" download class="admin-jobs-btn admin-jobs-btn-pdf">Download</a>
                                    </div>
                                </div>
                            <?php elseif ($row['cv_path']): ?>
                                <a href="<?= htmlspecialchars($row['cv_path']) ?>" download class="admin-jobs-btn admin-jobs-btn-pdf">Download</a>
                            <?php else: ?>
                                <span class="admin-jobs-no-cv">No CV uploaded.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <?php endif;
    endforeach;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>All Job Applications | Brew & Go Admin</title>
    <link rel="stylesheet" href="styles/style.css" />
</head>
<body class="admin-jobs-body">
    <?php include 'navbar.php'; ?>
    <div class="admin-jobs-wrapper">
        <?php include 'admin_sidebar.php'; ?>
        <div class="admin-jobs-main">
            <header class="admin-jobs-topbar">
                <div class="admin-jobs-topbar-left">
                    <span class="admin-jobs-topbar-title">All Job Applications</span>
                </div>
                <div class="admin-jobs-topbar-right">
                    <a href="admin_dashboard.php" class="admin-activities-back-btn">← Back to Dashboard</a>
                </div>
            </header>
            <section class="admin-jobs-table-section">
                <h2 class="admin-jobs-section-title admin-jobs-section-pending">Pending Applications</h2>
                <table class="admin-jobs-table">
                    <thead>
                        <tr>
                            <th class="admin-jobs-th">ID</th>
                            <th class="admin-jobs-th">Name</th>
                            <th class="admin-jobs-th">Email</th>
                            <th class="admin-jobs-th">Phone</th>
                            <th class="admin-jobs-th">Preferred Shift</th>
                            <th class="admin-jobs-th">Submitted</th>
                            <th class="admin-jobs-th">Status</th>
                            <th class="admin-jobs-th">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php renderJobTable($pendingJobs, $show_id); ?>
                    </tbody>
                </table>

                <h2 class="admin-jobs-section-title admin-jobs-section-accepted">Accepted Applications</h2>
                <table class="admin-jobs-table">
                    <thead>
                        <tr>
                            <th class="admin-jobs-th">ID</th>
                            <th class="admin-jobs-th">Name</th>
                            <th class="admin-jobs-th">Email</th>
                            <th class="admin-jobs-th">Phone</th>
                            <th class="admin-jobs-th">Preferred Shift</th>
                            <th class="admin-jobs-th">Submitted</th>
                            <th class="admin-jobs-th">Status</th>
                            <th class="admin-jobs-th">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php renderJobTable($acceptedJobs, $show_id); ?>
                    </tbody>
                </table>

                <h2 class="admin-jobs-section-title admin-jobs-section-rejected">Rejected Applications</h2>
                <table class="admin-jobs-table">
                    <thead>
                        <tr>
                            <th class="admin-jobs-th">ID</th>
                            <th class="admin-jobs-th">Name</th>
                            <th class="admin-jobs-th">Email</th>
                            <th class="admin-jobs-th">Phone</th>
                            <th class="admin-jobs-th">Preferred Shift</th>
                            <th class="admin-jobs-th">Submitted</th>
                            <th class="admin-jobs-th">Status</th>
                            <th class="admin-jobs-th">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php renderJobTable($rejectedJobs, $show_id); ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</body>
</html>
