<?php
define('APP_URL', 'http://localhost/talentflow/public');
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "talentflow");
if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      $_SESSION['user'] = $user['email'];
      header('Location: index.php');
      exit;
    } else {
      $error = "Invalid password.";
    }
  } else {
    $error = "Email not registered. <a href='" . APP_URL . "/signup.php'>Sign up</a>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Staff Login | TalentFlow</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* 🌟 HR Background Image */
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Inter', sans-serif;
      overflow: hidden;

      background: url('<?= APP_URL ?>/assets/images/profileee.png') no-repeat center center / cover;
      position: relative;
    }

    /* Dark Overlay */
    body::before {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.65);
      backdrop-filter: blur(3px);
      z-index: 0;
    }

    .login-box,
    .top-btn,
    footer {
      position: relative;
      z-index: 2;
    }

    /* Login Card */
    .login-box {
      background: rgba(255, 255, 255, 0.10);
      backdrop-filter: blur(15px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 16px;
      padding: 2.5rem;
      width: 100%;
      max-width: 380px;
      color: #fff;
      text-align: center;
      box-shadow: 0 4px 25px rgba(0,0,0,0.3);
    }

    .login-box h3 {
      font-weight: 700;
      margin-bottom: 1.5rem;
      color: #fff;
    }

    .form-control {
      background: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 10px;
      color: #fff;
      padding: 0.8rem;
    }

    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.7);
    }

    .form-control:focus {
      background: rgba(255, 255, 255, 0.25);
      border-color: #fff;
      box-shadow: none;
      color: #fff;
    }

    .btn-primary {
      background: #6a11cb;
      border: none;
      border-radius: 12px;
      padding: 0.75rem;
      font-weight: 600;
      width: 100%;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: #580a9c;
      transform: translateY(-2px);
    }

    .top-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      border: none;
      border-radius: 50px;
      padding: 0.45rem 1rem;
      font-weight: 600;
      font-size: 0.9rem;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .top-btn:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: scale(1.05);
    }

    .alert {
      background: rgba(255, 50, 50, 0.2);
      color: #ffcccc;
      border: 1px solid rgba(255, 0, 0, 0.3);
    }

    footer {
      margin-top: 1.5rem;
      font-size: 0.85rem;
      color: #ddd;
    }

    footer strong {
      color: #fff;
    }

    a {
      color: #ffd166;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
      color: #ffe599;
    }
  </style>
</head>

<body>

<!-- Admin Login Button -->
<a href="admin_login.php" class="top-btn">
  <i class="bi bi-person-gear me-1"></i> Admin Login
</a>

<div class="login-box">
  <h3><i class="bi bi-people-fill me-2"></i> Login</h3>

  <?php if (!empty($error)): ?>
    <div class="alert small text-center py-2"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="mb-3 text-start">
      <label class="form-label text-light">Email</label>
      <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
    </div>

    <div class="mb-3 text-start">
      <label class="form-label text-light">Password</label>
      <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
    </div>

    <button class="btn btn-primary mt-2">Login</button>
  </form>

  <footer>
    <p class="mt-4 mb-1">Don’t have an account? <a href="<?= APP_URL ?>/signup.php">Sign up</a></p>
    <small>© 2025 <strong>MACSEEDS</strong> | Hackathon Series — <strong>lablab.ai</strong></small>
  </footer>
</div>

</body>
</html>
