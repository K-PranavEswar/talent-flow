<?php
session_start();
define('APP_URL', 'http://localhost/talentflow/public');

if (!isset($_SESSION['admin']) || !is_array($_SESSION['admin'])) {
  header('Location: admin_login.php');
  exit;
}

require_once __DIR__ . '/../app/Models/Admin.php';

// ✅ Get current admin session data
$sessionAdmin = $_SESSION['admin'];

// ✅ Fetch latest data from DB
try {
  $admin = Admin::find($sessionAdmin['id']);
} catch (Exception $e) {
  $admin = $sessionAdmin;
}

// ✅ If admin record not found, fallback
if (!$admin) {
  $admin = $sessionAdmin;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TalentFlow | Admin Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --primary: #6a11cb;
      --secondary: #2575fc;
      --bg-dark: #0b0521;
      --card-bg: rgba(255,255,255,0.05);
      --text-light: #e8e6f0;
      --accent: #ff6ec7;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--bg-dark);
      color: var(--text-light);
      min-height: 100vh;
      margin: 0;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      height: 100vh;
      width: 80px;
      background: var(--bg-dark);
      border-right: 1px solid rgba(255,255,255,0.08);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: space-between;
      padding: 1rem 0;
      z-index: 1000;
    }

    .sidebar .logo {
      text-align: center;
      font-weight: 700;
      font-size: 1.3rem;
      color: var(--text-light);
    }

    .sidebar a {
      text-decoration: none;
      color: var(--text-light);
      font-size: 1.2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0.75rem;
      width: 100%;
      transition: 0.3s ease;
    }

    .sidebar a:hover {
      background: rgba(255,255,255,0.1);
    }

    .main-content-wrapper {
      margin-left: 80px;
      padding-top: 2rem;
      min-height: 100vh;
    }

    .header-title {
      padding: 0 3rem 1rem;
    }

    .header-title h2 {
      font-weight: 700;
      background: linear-gradient(90deg, #b621fe, #1fd1f9);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .card-glass {
      background: var(--card-bg);
      border: 1px solid rgba(255,255,255,0.08);
      backdrop-filter: blur(14px);
      border-radius: 20px;
      padding: 2rem;
      box-shadow: 0 0 25px rgba(100,100,255,0.15);
    }

    .profile-avatar {
      width: 130px;
      height: 130px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid var(--accent);
      margin-bottom: 1rem;
    }

    .form-control {
      background: rgba(255,255,255,0.08);
      border: none;
      color: #fff;
    }

    .form-control:focus {
      background: rgba(255,255,255,0.12);
      color: #fff;
      box-shadow: 0 0 0 2px var(--secondary);
    }

    .btn-gradient {
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      border: none;
      color: #fff;
      border-radius: 10px;
      padding: 0.6rem 1.4rem;
      font-weight: 500;
      transition: 0.3s ease;
    }

    .btn-gradient:hover {
      transform: scale(1.03);
      opacity: 0.9;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <div class="logo">
    <i class="bi bi-shield-lock-fill fs-3"></i>
  </div>

  <div>
    <a href="admin_dashboard.php" title="Back to Dashboard">
      <i class="bi bi-arrow-left-circle fs-3"></i>
    </a>
  </div>
</div>

<div class="main-content-wrapper">
  <div class="header-title">
    <h2>Admin Profile ⚙️</h2>
    <p class="text-secondary">Manage your account and credentials securely.</p>
  </div>

  <div class="container px-4">
    <div class="card-glass text-center mx-auto" style="max-width: 600px;">
      <img 
        src="<?= !empty($admin['photo']) 
          ? APP_URL . '/assets/images/' . htmlspecialchars($admin['photo']) 
          : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' ?>" 
        class="profile-avatar" alt="Admin Photo">

      <form action="update_profile.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($admin['name'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($admin['email'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">New Password</label>
          <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
        </div>

        <div class="mb-3">
          <label class="form-label">Profile Photo</label>
          <input type="file" name="photo" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn-gradient mt-2">
          <i class="bi bi-save me-1"></i> Update Profile
        </button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
