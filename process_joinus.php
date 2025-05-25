<?php
session_start();
require_once 'connection.php';

function clean($data) { return htmlspecialchars(trim($data)); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save all user input except files
    $form_data = [
        'first_name'      => clean($_POST['first_name'] ?? ''),
        'last_name'       => clean($_POST['last_name'] ?? ''),
        'email'           => clean($_POST['email'] ?? ''),
        'street'          => clean($_POST['street'] ?? ''),
        'city'            => clean($_POST['city'] ?? ''),
        'state'           => clean($_POST['state'] ?? ''),
        'postcode'        => clean($_POST['postcode'] ?? ''),
        'phone'           => clean($_POST['phone'] ?? ''),
        'shift'           => clean($_POST['shift'] ?? ''),
    ];

    // Validation
    foreach (['first_name','last_name','email','street','city','state','postcode','phone','shift'] as $f) {
        if (empty($form_data[$f])) {
            $_SESSION['joinus_form'] = $form_data;
            $_SESSION['joinus_form_error'] = 'All required fields must be filled in!';
            header('Location: joinus.php');
            exit;
        }
    }

    // --- Validate and save CV ---
    $cv_path = '';
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        $allowed_cv_ext = ['pdf','doc','docx'];
        $cv_ext = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
        if ($_FILES['cv']['size'] > 200 * 1024) {
            $_SESSION['joinus_form'] = $form_data;
            $_SESSION['joinus_form_error'] = 'CV too large (max 200KB).';
            header('Location: joinus.php');
            exit;
        }
        if (!in_array($cv_ext, $allowed_cv_ext)) {
            $_SESSION['joinus_form'] = $form_data;
            $_SESSION['joinus_form_error'] = 'Invalid CV file type. Only PDF, DOC, DOCX allowed.';
            header('Location: joinus.php');
            exit;
        }

        // ======== MIME type check for PDF =========
        if ($cv_ext === 'pdf') {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $type = finfo_file($finfo, $_FILES['cv']['tmp_name']);
            finfo_close($finfo);
            if ($type !== 'application/pdf') {
                $_SESSION['joinus_form'] = $form_data;
                $_SESSION['joinus_form_error'] = 'Uploaded file is not a valid PDF document.';
                header('Location: joinus.php');
                exit;
            }
        }
        // ==========================================

        $cv_dir = __DIR__ . '/uploads/cvs/';
        if (!is_dir($cv_dir)) mkdir($cv_dir, 0777, true);

        $basename = pathinfo($_FILES['cv']['name'], PATHINFO_FILENAME);
        $safe_basename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $basename);
        $cv_filename = $safe_basename . '_' . uniqid() . '.' . $cv_ext;
        $cv_server_path = $cv_dir . $cv_filename;
        $cv_path = 'uploads/cvs/' . $cv_filename;

        if (!move_uploaded_file($_FILES['cv']['tmp_name'], $cv_server_path)) {
            $_SESSION['joinus_form'] = $form_data;
            $_SESSION['joinus_form_error'] = 'Failed to upload CV.';
            header('Location: joinus.php');
            exit;
        }
    }



    // --- Validate and save photo ---
    $photo_path = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowed_photo_ext = ['jpg','jpeg','png','gif','webp'];
        $photo_ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if ($_FILES['photo']['size'] > 500 * 1024) {
            $_SESSION['joinus_form'] = $form_data;
            $_SESSION['joinus_form_error'] = 'Photo too large (max 500KB).';
            header('Location: joinus.php');
            exit;
        }
        if (!in_array($photo_ext, $allowed_photo_ext)) {
            $_SESSION['joinus_form'] = $form_data;
            $_SESSION['joinus_form_error'] = 'Invalid photo file type.';
            header('Location: joinus.php');
            exit;
        }
        $photo_dir = 'uploads/photos/';
        if (!is_dir($photo_dir)) mkdir($photo_dir, 0777, true);
        $photo_path = $photo_dir . 'photo_' . uniqid() . '.' . $photo_ext;
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path)) {
            $_SESSION['joinus_form'] = $form_data;
            $_SESSION['joinus_form_error'] = 'Failed to upload photo.';
            header('Location: joinus.php');
            exit;
        }
    }

    // All OK, insert to DB
    $sql = "INSERT INTO job_application 
            (first_name, last_name, email, phone, preferred_shift, address, postcode, city, state, photo_path, cv_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    $address = $form_data['street'];
    mysqli_stmt_bind_param($stmt, "sssssssssss", 
        $form_data['first_name'], $form_data['last_name'], $form_data['email'], $form_data['phone'], $form_data['shift'],
        $address, $form_data['postcode'], $form_data['city'], $form_data['state'], $photo_path, $cv_path
    );
    if (mysqli_stmt_execute($stmt)) {
        // Success: clear form data
        unset($_SESSION['joinus_form'], $_SESSION['joinus_form_error']);
        $response_type = 'success';
        $response_msg = 'Your application was submitted successfully!';
    } else {
        $_SESSION['joinus_form'] = $form_data;
        $_SESSION['joinus_form_error'] = 'Failed to submit application: ' . mysqli_error($conn);
        header('Location: joinus.php');
        exit;
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    header('Location: joinus.php');
    exit;
}
?>
<!-- Show response page on success -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Application Status | Brew & Go</title>
    <link rel="stylesheet" href="styles/style.css" />
    <meta http-equiv="refresh" content="2;url=main.php">
</head>
<body>
    <div class="response-container" style="margin:100px auto;text-align:center;">
        <div class="response-success" style="font-size:1.5em;margin-bottom:0.8em;"><?= htmlspecialchars($response_msg) ?></div>
        <p>You will be redirected to the home page in 2 seconds.</p>
        <a href="main.php"><button class="response-btn">Go Home Now</button></a>
    </div>
</body>
</html>
