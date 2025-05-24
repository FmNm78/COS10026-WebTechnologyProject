<?php
require_once 'connection.php';

function clean($data) {
    return htmlspecialchars(trim($data));
}

// Default response
$response_type = '';
$response_msg = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name      = clean($_POST['first_name'] ?? '');
    $last_name       = clean($_POST['last_name'] ?? '');
    $email           = clean($_POST['email'] ?? '');
    $street          = clean($_POST['street'] ?? '');
    $city            = clean($_POST['city'] ?? '');
    $state           = clean($_POST['state'] ?? '');
    $postcode        = clean($_POST['postcode'] ?? '');
    $phone           = clean($_POST['phone'] ?? '');
    $preferred_shift = clean($_POST['shift'] ?? '');

    // Validate required fields
    if (!$first_name || !$last_name || !$email || !$street || !$city || !$state || !$postcode || !$phone || !$preferred_shift) {
        $response_type = 'error';
        $response_msg = 'All required fields must be filled in!';
    } else {
        // Handle uploads
        $cv_path = '';
        $photo_path = '';

        // CV
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] === 0) {
            $allowed_cv = ['pdf', 'doc', 'docx'];
            $cv_ext = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
            if (!in_array($cv_ext, $allowed_cv)) {
                $response_type = 'error';
                $response_msg = 'Invalid CV file type.';
            } else {
                $cv_path = "uploads/cv_" . uniqid() . "." . $cv_ext;
                move_uploaded_file($_FILES['cv']['tmp_name'], $cv_path);
            }
        }

        // Photo
        if ($response_type !== 'error' && isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            if ($_FILES['photo']['size'] > 200 * 1024) {
                $response_type = 'error';
                $response_msg = 'Photo too large (max 200KB).';
            } else {
                $photo_ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                $allowed_photo = ['jpg','jpeg','png','gif','webp'];
                if (!in_array($photo_ext, $allowed_photo)) {
                    $response_type = 'error';
                    $response_msg = 'Invalid photo file type.';
                } else {
                    $photo_path = "uploads/photo_" . uniqid() . "." . $photo_ext;
                    move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
                }
            }
        }

        // Only insert if no upload errors
        if ($response_type !== 'error') {
            $sql = "INSERT INTO job_application 
                (first_name, last_name, email, phone, preferred_shift, address, postcode, city, state, photo_path, cv_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            $address = $street;

            mysqli_stmt_bind_param($stmt, "sssssssssss", 
                $first_name, $last_name, $email, $phone, $preferred_shift, 
                $address, $postcode, $city, $state, $photo_path, $cv_path
            );

            if (mysqli_stmt_execute($stmt)) {
                $response_type = 'success';
                $response_msg = 'Your application was submitted successfully!';
            } else {
                $response_type = 'error';
                $response_msg = 'Failed to submit application: ' . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
} else {
    $response_type = 'error';
    $response_msg = 'Invalid request.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Application Status | Brew & Go</title>
    <link rel="stylesheet" href="styles/style.css" />
    <?php if ($response_type === 'success'): ?>

      <!-- Auto redirect after 2 seconds -->
      <meta http-equiv="refresh" content="2;url=main.php">
    <?php endif; ?>
</head>
<body>
    <div class="response-container">
        <?php if ($response_type === 'success'): ?>
            <div class="response-success"><?= $response_msg ?></div>
        <?php else: ?>
            <div class="response-error"><?= $response_msg ?></div>
        <?php endif; ?>
        <a href="joinus.php"><button class="response-btn">Back to Join Us</button></a>
        <?php if ($response_type === 'success'): ?>
            <p class="redirect-btn">You will be redirected to the home page in 5 seconds.</p>
        <?php endif; ?>
    </div>
</body>
</html>
