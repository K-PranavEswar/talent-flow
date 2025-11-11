<?php
define('APP_URL', 'http://localhost/talentflow/public');
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "talentflow");
if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $confirm = trim($_POST['confirm_password']);

  if ($password !== $confirm) {
    $error = "Passwords do not match!";
  } else {
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
      $error = "Email already registered!";
    } else {
      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
      $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $name, $email, $hashedPassword);
      if ($stmt->execute()) {
        $success = "Signup successful! Redirecting to login page...";
        echo "<script>
                setTimeout(function(){
                  window.location.href = '" . APP_URL . "/login.php';
                }, 2000);
              </script>";
      } else {
        $error = "Registration failed. Try again.";
      }
      $stmt->close();
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>Sign Up | TalentFlow HR Orchestrator</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --primary: #2575fc;
      --gradient: linear-gradient(135deg, #6a11cb, #2575fc);
    }

    body {
      background: var(--gradient);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Inter', system-ui, sans-serif;
      padding: 1rem;
    }

    .signup-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 420px;
      padding: 2rem 1.8rem;
      transition: 0.3s ease;
    }

    .signup-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.18);
    }

    h3 {
      font-weight: 700;
      color: var(--primary);
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .form-control {
      border-radius: 10px;
      padding: 0.75rem;
      font-size: 0.95rem;
    }

    .form-label {
      font-weight: 500;
      margin-bottom: 0.4rem;
    }

    .btn-primary {
      background: var(--primary);
      border: none;
      border-radius: 12px;
      font-weight: 600;
      padding: 0.8rem;
      font-size: 1rem;
    }

    .btn-primary:hover {
      background: #1b5edc;
    }

    .alert {
      border-radius: 10px;
      font-size: 0.9rem;
    }

    .text-center small {
      font-size: 0.9rem;
    }

    @media (max-width: 576px) {
      .signup-card {
        padding: 1.5rem 1.2rem;
        border-radius: 16px;
      }

      h3 {
        font-size: 1.4rem;
        margin-bottom: 1rem;
      }

      .form-control {
        font-size: 0.9rem;
        padding: 0.65rem 0.8rem;
      }

      .btn-primary {
        font-size: 0.95rem;
        padding: 0.7rem;
      }

      small {
        font-size: 0.85rem;
      }
    }
  </style>
</head>
<body>

<div class="signup-card">
  <h3>Create Account</h3>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2 text-center"><?= $error ?></div>
  <?php elseif (!empty($success)): ?>
    <div class="alert alert-success py-2 text-center"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input type="text" class="form-control" name="name" placeholder="Enter your name" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Email address</label>
      <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" class="form-control" name="password" placeholder="Create password" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Confirm Password</label>
      <input type="password" class="form-control" name="confirm_password" placeholder="Confirm password" required>
    </div>

    <button type="submit" class="btn btn-primary w-100 mt-2">Sign Up</button>
  </form>

  <div class="text-center mt-3">
    <small>Already have an account? 
      <a href="<?= APP_URL ?>/login.php" class="text-decoration-none fw-semibold text-primary">Login here</a>
    </small>
  </div>

  <footer class="footer mt-5 py-3 text-center text-muted border-top">
    <div class="container">
      <small>© 2025 <strong>MACSEEDS</strong> | Hackathon Series — <strong>lablab.ai</strong></small>
    </div>
  </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
