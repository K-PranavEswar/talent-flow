<?php
session_start();
define('APP_URL', 'http://localhost/talentflow/public');
require_once __DIR__ . '/../config/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['username']);
  $password = trim($_POST['password']);

  try {
    $admin = DB::fetch("SELECT * FROM admins WHERE email = ?", [$email]);

    if ($admin && password_verify($password, $admin['password'])) {
      $_SESSION['admin'] = [
        'id' => $admin['id'],
        'name' => $admin['name'],
        'email' => $admin['email'],
        'photo' => $admin['photo'] ?? 'default.png'
      ];

      header('Location: admin_dashboard.php');
      exit;
    } else {
      $error = "Invalid email or password!";
    }
  } catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login | TalentFlow</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --primary: #6a11cb;
      --secondary: #2575fc;
    }

    /* ---------------------- */
    /* 🔥 MODERN ADMIN BACKGROUND */
    /* ---------------------- */
    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      font-family: 'Inter', sans-serif;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      position: relative;

      /* HR Admin Tech Gradient */
      background: radial-gradient(circle at top left, #0b0f2f, #050414 60%);
    }

    /* Purple Glow */
    body::before {
      content: "";
      position: absolute;
      width: 600px;
      height: 600px;
      top: -150px;
      left: -120px;
      background: rgba(130, 45, 255, 0.35);
      filter: blur(160px);
    }

    /* Blue Glow */
    body::after {
      content: "";
      position: absolute;
      width: 650px;
      height: 650px;
      bottom: -180px;
      right: -180px;
      background: rgba(0, 132, 255, 0.35);
      filter: blur(170px);
    }

    /* ---------------------- */
    /* STAFF BUTTON */
    /* ---------------------- */
    .staff-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      background: rgba(255,255,255,0.2);
      color: #fff;
      font-weight: 600;
      padding: 0.5rem 1.2rem;
      border-radius: 50px;
      text-decoration: none;
      backdrop-filter: blur(8px);
      transition: 0.3s ease;
      z-index: 10;
    }

    .staff-btn:hover {
      background: rgba(255,255,255,0.35);
      transform: scale(1.05);
    }

    /* ---------------------- */
    /* LOGIN BOX */
    /* ---------------------- */
    .login-box {
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      backdrop-filter: blur(18px);
      padding: 2.5rem 2rem;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
      z-index: 5;
      animation: fadeIn 0.8s ease;
    }

    .login-box h3 {
      text-align: center;
      font-weight: 700;
      margin-bottom: 1.5rem;
      text-shadow: 0 0 12px rgba(255,255,255,0.2);
    }

    .form-control {
      border-radius: 10px;
      border: none;
      padding: 0.8rem;
    }

    .btn-primary {
      background: var(--primary);
      border: none;
      border-radius: 12px;
      padding: 0.8rem;
      font-weight: 600;
    }

    .btn-primary:hover {
      background: var(--secondary);
      transform: translateY(-2px);
    }

    .footer {
      text-align: center;
      font-size: 0.85rem;
      margin-top: 1.4rem;
      opacity: 0.85;
    }

    /* Animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>

</head>
<body>

  <a href="login.php" class="staff-btn">
    <i class="bi bi-people-fill me-1"></i> Staff Login
  </a>

  <div class="login-box">
    <h3><i class="bi bi-shield-lock-fill me-2"></i> Admin Login</h3>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-center py-2">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label text-white-50">Email</label>
        <input type="email" name="username" class="form-control" placeholder="Enter email" required>
      </div>

      <div class="mb-3">
        <label class="form-label text-white-50">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
      </div>

      <button class="btn btn-primary w-100 mt-2">Login</button>
    </form>

    <div class="footer mt-4">
      © 2025 <strong>MACSEEDS</strong> | Hackathon Series — <strong>lablab.ai</strong>
    </div>
  </div>

</body>
</html>
