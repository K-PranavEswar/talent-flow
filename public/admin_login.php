<?php
session_start();
define('APP_URL', 'http://localhost/talentflow/public');

// ✅ Hardcoded admin credentials (for now)
// You can switch to DB-based login later via Admin::verify()
$adminUser = "admin";
$adminPass = "admin";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  if ($username === $adminUser && $password === $adminPass) {
    // ✅ Store admin as an array — prevents offset errors
    $_SESSION['admin'] = [
      'id' => 1,
      'name' => 'Administrator',
      'email' => 'admin@talentflow.com'
    ];

    header('Location: admin_dashboard.php');
    exit;
  } else {
    $error = "Invalid admin credentials!";
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
      --purple: #7209b7;
      --purple-dark: #560bad;
      --bg-gradient: linear-gradient(135deg, #3a0ca3, #7209b7, #560bad);
    }

    body {
      background: var(--bg-gradient);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Inter', sans-serif;
      color: #fff;
      position: relative;
      overflow: hidden;
    }

    .staff-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      background: #fff;
      color: var(--purple);
      font-weight: 600;
      padding: 0.5rem 1.2rem;
      border-radius: 50px;
      text-decoration: none;
      box-shadow: 0 3px 12px rgba(0, 0, 0, 0.2);
      transition: all 0.3s ease;
    }

    .staff-btn:hover { background: #f1f1f1; transform: scale(1.05); }

    .login-box {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      padding: 2.5rem 2rem;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
      animation: fadeIn 0.8s ease-in-out;
    }

    .login-box h3 {
      color: #fff;
      font-weight: 700;
      margin-bottom: 1.5rem;
      text-align: center;
      text-shadow: 0 0 8px rgba(255,255,255,0.3);
    }

    .form-control {
      border-radius: 10px;
      border: none;
      padding: 0.8rem;
    }

    .btn-primary {
      background: var(--purple);
      border: none;
      border-radius: 12px;
      padding: 0.8rem;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: var(--purple-dark);
      transform: translateY(-2px);
    }

    .alert { border-radius: 10px; }

    .footer {
      text-align: center;
      font-size: 0.85rem;
      color: #ddd;
      margin-top: 1.5rem;
    }

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
      <div class="alert alert-danger py-2 text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label class="form-label text-white-50">Admin Username</label>
        <input type="text" name="username" class="form-control" placeholder="Enter admin ID" required>
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
