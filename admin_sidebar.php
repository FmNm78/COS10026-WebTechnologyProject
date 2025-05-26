<!-- Sidebar -->
<?php
$current_admin_page = basename($_SERVER['PHP_SELF']);
?>


    <aside class="admin-sidebar">
        <div class="admin-brand">
            <a href="admin_dashboard.php" class="admin-brand-link">Welcome, Admin</a>
        </div>
        <nav>
            <ul>
                <li>
                    <a href="admin_dashboard.php"<?= $current_admin_page == 'admin_dashboard.php' ? ' class="active"' : '' ?>>Dashboard</a>
                </li>

                <li>
                    <a href="admin_view_members.php"<?= $current_admin_page == 'admin_view_members.php' ? ' class="active"' : '' ?>>Members</a>
                </li>
                
                <li>
                    <a href="admin_view_enquiries.php"<?= $current_admin_page == 'admin_view_enquiries.php' ? ' class="active"' : '' ?>>Enquiries</a>
                </li>
                
                <li>
                    <a href="admin_jobs.php"<?= $current_admin_page == 'admin_jobs.php' ? ' class="active"' : '' ?>>Job Applications</a>
                </li>
                
                <li>
                    <a href="admin_activities.php"<?= $current_admin_page == 'admin_activities.php' ? ' class="active"' : '' ?>>Activities</a>
                </li>

                <li>
                    <a href="admin_newsletter.php"<?= $current_admin_page == 'admin_newsletter.php' ? ' class="active"' : '' ?>>Newletter</a>
                </li>
                
                <li>
                    <a href="logout.php" class="admin-logout-btn">Logout</a>
                </li>

            </ul>
        </nav>
    </aside>