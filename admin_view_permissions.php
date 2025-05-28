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

// List all pages for management (adjust this as needed)
$page_perms = [
    'admin_dashboard.php',
    'admin_view_enquiries.php',
    'admin_view_jobs.php',
    'admin_view_members.php',
    'add_members.php',
    'edit_members.php',
    'add_role.php',
    'admin_view_activities.php',
    'add_activities.php',
    'edit_activities.php',
    'admin_newsletter.php',
    'admin_view_permissions.php',
];

// Fetch all roles
$roles = [];
$res = mysqli_query($conn, "SELECT id, name FROM roles ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($res)) {
    $roles[$row['id']] = ucfirst($row['name']);
}

// Handle permission toggle (Allow/Deny)
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['page'], $_POST['role_id'])) {
    $page = $_POST['page'];
    $role_id = intval($_POST['role_id']);
    $can_view = isset($_POST['can_view']) && $_POST['can_view'] == '1' ? 1 : 0;

    // Check if permission exists
    $check = mysqli_query($conn, "SELECT id FROM page_permissions WHERE page='$page' AND role_id=$role_id");
    if (mysqli_num_rows($check) > 0) {
        // Update
        $q = mysqli_query($conn, "UPDATE page_permissions SET can_view=$can_view WHERE page='$page' AND role_id=$role_id");
        $message = $q ? "Updated!" : "Update failed: " . mysqli_error($conn);
    } else {
        // Insert
        $q = mysqli_query($conn, "INSERT INTO page_permissions (page, role_id, can_view) VALUES ('$page', $role_id, $can_view)");
        $message = $q ? "Inserted!" : "Insert failed: " . mysqli_error($conn);
    }
    // For live update/refresh effect
    header("Location: admin_view_permissions.php");
    exit;
}

// Fetch all current permissions
$page_roles = [];
$res = mysqli_query($conn, "SELECT * FROM page_permissions");
while ($row = mysqli_fetch_assoc($res)) {
    $page_roles[$row['page']][$row['role_id']] = $row['can_view'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Page Permissions</title>
    <link rel="stylesheet" href="styles/style.css" />
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="admin-wrapper">
        <?php include 'admin_sidebar.php'; ?>
        <div class="admin-activities-main">
            <header class="admin-activities-topbar">
                <div class="admin-activities-topbar-left">
                    <span class="admin-activities-topbar-title">Page Authorization</span>
                </div>
                <div class="admin-activities-topbar-right">
                    <a href="admin_dashboard.php" class="admin-activities-back-btn">← Back to Dashboard</a>
                </div>
            </header>
            <?php if ($message): ?>
                <div class="message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <table class="perm-table">
                <thead>
                    <tr>
                        <th>Page</th>
                        <?php foreach ($roles as $id => $role): ?>
                            <th><?= htmlspecialchars($role) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($page_perms as $page): ?>
                        <tr>
                            <td class="page-name"><?= htmlspecialchars($page) ?></td>
                            <?php foreach ($roles as $role_id => $role): 
                                $state = $page_roles[$page][$role_id] ?? 0; ?>
                                <td>
                                    <form method="POST" class="perm-form" action="">
                                        <input type="hidden" name="page" value="<?= htmlspecialchars($page) ?>">
                                        <input type="hidden" name="role_id" value="<?= $role_id ?>">
                                        <!-- Checkbox to toggle allow/deny -->
                                        <input type="hidden" name="can_view" value="<?= $state ? 0 : 1 ?>">
                                        <button type="submit" title="<?= $state ? 'Click to Deny' : 'Click to Allow' ?>">
                                            <span class="<?= $state ? 'allow' : 'deny' ?> icon">
                                                <?= $state ? '✔ Allow' : '✖ Deny' ?>
                                            </span>
                                        </button>
                                    </form>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
