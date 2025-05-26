<?php
// setup.php
// Run ONCE to initialize your database and tables, then DELETE or SECURE this file!

/**
 * Centralized error output
 */
function log_status($success, $successMsg, $failMsg, $conn = null) {
    if ($success) {
        echo "✅ $successMsg<br>";
    } else {
        echo "❌ $failMsg";
        if ($conn) echo ": " . mysqli_error($conn);
        echo "<br>";
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "assignment_2";

// Connect to MySQL
$conn = mysqli_connect($servername, $username, $password);
if (!$conn) {
    die("❌ Connection failed: " . mysqli_connect_error());
}
echo "✅ Connected to MySQL server.<br>";

// Create database with utf8mb4 charset
$sql = "CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
log_status(mysqli_query($conn, $sql), "Database '$dbname' is ready.", "Error creating database", $conn);

// Select database
mysqli_select_db($conn, $dbname);
echo "✅ Selected database: $dbname<br>";

/* --- 1. Roles Table --- */
echo "<b>// 1. roles: User/Staff/Admin Roles</b><br>";
$sql = "CREATE TABLE IF NOT EXISTS roles (
  id TINYINT PRIMARY KEY,
  name VARCHAR(50) UNIQUE NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
log_status(mysqli_query($conn, $sql), "Table 'roles' ready.", "Table 'roles' failed", $conn);

// Insert roles if not present
$roles = [1 => 'admin', 2 => 'operator', 3 => 'staff', 4 => 'user'];
foreach ($roles as $id => $name) {
    $check = mysqli_query($conn, "SELECT id FROM roles WHERE id = $id");
    if (mysqli_num_rows($check) === 0) {
        mysqli_query($conn, "INSERT INTO roles (id, name) VALUES ($id, '$name')");
    }
}

/* --- 2. Membership Table --- */
echo "<b>// 2. membership: Member Profile Info</b><br>";
$sql = "CREATE TABLE IF NOT EXISTS membership (
  id INT AUTO_INCREMENT PRIMARY KEY,
  member_id VARCHAR(10) UNIQUE,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  phone VARCHAR(20) UNIQUE,
  address TEXT DEFAULT NULL,
  sex VARCHAR(10) DEFAULT NULL,
  nationality VARCHAR(50) DEFAULT NULL,
  wallet DECIMAL(10,2) DEFAULT 0.00,
  points INT DEFAULT 0,
  profile_picture VARCHAR(255) DEFAULT NULL,
  payment_slip VARCHAR(255) DEFAULT NULL,
  status ENUM('active', 'Inactive') DEFAULT 'Inactive',
  registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
log_status(mysqli_query($conn, $sql), "Table 'membership' ready.", "Table 'membership' failed", $conn);

/* --- 3. User Table (Login credentials) --- */
echo "<b>// 3. user: Login Credentials (linked to membership, role)</b><br>";
$sql = "CREATE TABLE IF NOT EXISTS user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  membership_id INT,
  role_id TINYINT DEFAULT 4,
  FOREIGN KEY (membership_id) REFERENCES membership(id) ON DELETE SET NULL,
  FOREIGN KEY (role_id) REFERENCES roles(id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
log_status(mysqli_query($conn, $sql), "Table 'user' ready.", "Table 'user' failed", $conn);

/* --- 4. Admin Table --- */
echo "<b>// 4. admin: Standalone admin login table</b><br>";
$sql = "CREATE TABLE IF NOT EXISTS admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
log_status(mysqli_query($conn, $sql), "Table 'admin' ready.", "Table 'admin' failed", $conn);

// Insert default admin (plain text 'admin')
$check_admin_sql = "SELECT id FROM admin WHERE LOWER(username) = 'admin'";
$check_admin_result = mysqli_query($conn, $check_admin_sql);
if (mysqli_num_rows($check_admin_result) === 0) {
    $insert_admin_sql = "INSERT INTO admin (username, password) VALUES ('admin', 'admin')";
    if (mysqli_query($conn, $insert_admin_sql)) {
        echo "✅ Default admin account created.<br>";
    } else {
        echo "❌ Failed to create default admin: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "ℹ️ Default admin already exists.<br>";
}

/* --- 5. job_application: Job/Join Us Table --- */
echo "<b>// 5. job_application: Staff/Job Applications</b><br>";
$sql = "CREATE TABLE IF NOT EXISTS job_application (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(20),
  preferred_shift VARCHAR(50),
  address TEXT,
  postcode VARCHAR(10),
  city VARCHAR(100),
  state VARCHAR(100),
  photo_path VARCHAR(255),
  cv_path VARCHAR(255),
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
log_status(mysqli_query($conn, $sql), "Table 'job_application' ready.", "Table 'job_application' failed", $conn);

/* --- 6. enquiry: Contact/Enquiry Submissions --- */
echo "<b>// 6. enquiry: User Contact/Enquiry Messages</b><br>";
$sql = "CREATE TABLE IF NOT EXISTS enquiry (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ticket_id VARCHAR(20) UNIQUE,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(20),
  address TEXT,
  postcode VARCHAR(10),
  city VARCHAR(100),
  state VARCHAR(100),
  enquiry_type VARCHAR(100),
  message TEXT,
  status ENUM('Pending', 'In Progress', 'Resolved') DEFAULT 'Pending',
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
log_status(mysqli_query($conn, $sql), "Table 'enquiry' ready.", "Table 'enquiry' failed", $conn);

/* --- 7. activities: Events/Blog Posts --- */
echo "<b>// 7. activities: Events & Activities</b><br>";
$sql = "CREATE TABLE IF NOT EXISTS activities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  image_path VARCHAR(255),
  event_date DATE,
  start_time TIME,
  end_time TIME,
  location VARCHAR(255),
  external_link VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
log_status(mysqli_query($conn, $sql), "Table 'activities' ready.", "Table 'activities' failed", $conn);

/* --- 8. topup_history: Track Each Wallet Top-Up Event --- */
echo "<b>// 10. topup_history: Member Wallet Top-Up Transactions</b><br>";
$sql = "CREATE TABLE IF NOT EXISTS topup_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  membership_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (membership_id) REFERENCES membership(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
log_status(mysqli_query($conn, $sql), "Table 'topup_history' ready.", "Table 'topup_history' failed", $conn);


$sql = "CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (mysqli_query($conn, $sql)) {
    echo "<p style='color:green;'>✅ Table <b>newsletter_subscribers</b> created or already exists.</p>";
} else {
    echo "<p style='color:red;'>❌ Failed to create table: " . mysqli_error($conn) . "</p>";
}


echo "<br>🎉 Setup complete. Please delete or secure this file after running for security.<br>";

mysqli_close($conn);
?>
