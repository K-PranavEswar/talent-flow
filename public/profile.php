<?php
session_start();
if (!defined('APP_URL')) define('APP_URL', 'http://localhost/talentflow/public');
if (!isset($_SESSION['user'])) { echo "<script>window.location.href='" . APP_URL . "/login.php';</script>"; exit; }

require_once __DIR__ . '/../app/Models/User.php';

$userEmail = $_SESSION['user'];
$user = User::findByEmail($userEmail);

if (!$user) {
    echo "<script>alert('User not found.'); window.location.href='" . APP_URL . "/login.php';</script>";
    exit;
}

$profilePhoto = !empty($user['photo']) ? 'assets/images/' . htmlspecialchars($user['photo']) : 'assets/images/default.png';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $file = $_FILES['photo'];
    if ($file['error'] === 0) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['png','jpg','jpeg','webp'];

        if (in_array($ext, $allowed)) {
            $newName = 'user_' . $user['id'] . '_' . time() . '.' . $ext;
            $uploadPath = __DIR__ . '/assets/images/' . $newName;

            move_uploaded_file($file['tmp_name'], $uploadPath);

            User::updatePhoto($user['id'], $newName);

            $_SESSION['user_photo'] = $newName;

            header("Location: profile.php?updated=1");
exit;

        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Profile | TalentFlow</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body{font-family:'Inter',sans-serif;background:#090314;color:#eaeaff}
.card-glass{background:rgba(20,15,40,0.6);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.1);border-radius:1rem;padding:2rem;box-shadow:0 4px 20px rgba(0,0,0,.2)}
.profile-photo{width:140px;height:140px;border-radius:50%;object-fit:cover;border:3px solid #6a11cb;box-shadow:0 0 25px rgba(106,17,203,.5)}
.btn-grad{background:linear-gradient(90deg,#b621fe,#1fd1f9)!important;border:none;color:#fff;transition:.3s}
.btn-grad:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(106,17,203,.4)}
</style>
</head>

<body>
<div class="container py-5">
    <div class="card-glass mx-auto" style="max-width:600px;text-align:center">

        <img src="<?= APP_URL . '/' . $profilePhoto ?>" class="profile-photo mb-3">

        <h3 class="fw-bold"><?= htmlspecialchars($user['name']) ?></h3>
        <p class="text-muted mb-1"><?= htmlspecialchars($user['email']) ?></p>
        <p class="text-muted">Joined: <?= date('M d, Y', strtotime($user['created_at'])) ?></p>

        <hr class="my-4" style="border-color:rgba(255,255,255,.1)">

        <h5 class="fw-semibold mb-3">Update Profile Photo</h5>

        <form method="post" enctype="multipart/form-data">
            <input type="file" name="photo" class="form-control mb-3" required>
            <button class="btn btn-grad w-100">Upload New Photo</button>
        </form>

        <a href="<?= APP_URL ?>/dashboard.php" class="btn btn-outline-light mt-4 w-100">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>

    </div>
</div>
</body>
</html>
