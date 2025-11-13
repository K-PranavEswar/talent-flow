<?php
session_start();
define('APP_URL', 'http://localhost/talentflow/public');

if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}

require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/DB.php';

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $position = trim($_POST['position']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm_password']);

    // Required fields check
    if ($name === "" || $email === "" || $position === "" || $password === "" || $confirm === "") {
        $error = "All fields are required!";
    }

    // Password match check
    elseif ($password !== $confirm) {
        $error = "Passwords do not match!";
    }

    else {

        // PHOTO UPLOAD
        $photoName = "default.png";
        $uploadDir = __DIR__ . "/assets/images/";

        if (!empty($_FILES['photo']['name'])) {

            $tempFile = $_FILES['photo']['tmp_name'];
            $fileName = basename($_FILES['photo']['name']);
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($ext, $allowed)) {
                $error = "Only JPG, JPEG or PNG allowed!";
            } else {

                // Unique file name
                $photoName = "staff_" . time() . "." . $ext;
                $target = $uploadDir . $photoName;

                if (!move_uploaded_file($tempFile, $target)) {
                    $error = "Photo upload failed!";
                }
            }
        }

        if ($error === "") {
            // HASH PASSWORD
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insert staff into DB
            $pdo = DB::pdo();
            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, position, password, photo, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())
            ");

            $stmt->execute([$name, $email, $position, $hashedPassword, $photoName]);

            header("Location: staff.php?added=success");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Staff | TalentFlow</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background: #0a031c;
    font-family: 'Inter', sans-serif;
    color: #fff;
}

.form-container {
    max-width: 550px;
    margin: auto;
    margin-top: 60px;
    background: rgba(255,255,255,0.07);
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 0 20px rgba(140, 82, 255, 0.3);
    backdrop-filter: blur(12px);
}

.form-control, .form-select {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: #fff;
}

.form-control::placeholder { color: #bbb; }

.form-control:focus {
    background: rgba(255,255,255,0.15);
    color: #fff;
}

.btn-primary {
    background: linear-gradient(90deg, #6a11cb, #2575fc);
    border: none;
}

.btn-primary:hover {
    opacity: .85;
}
</style>
</head>

<body>

<div class="container">
    <div class="form-container">
        <h3 class="text-center mb-4">➕ Add New Staff</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email ID</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Position / Role</label>
                <input type="text" name="position" class="form-control" placeholder="HR, Developer, Manager" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required minlength="6">
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter password" required minlength="6">
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Photo</label>
                <input type="file" name="photo" class="form-control" accept=".jpg,.jpeg,.png">
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">Add Staff</button>

            <a href="staff.php" class="btn btn-secondary w-100 mt-3">Back to Staff List</a>
        </form>
    </div>
</div>

</body>
</html>
