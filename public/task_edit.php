<?php
session_start();
define('APP_URL', 'http://localhost/talentflow/public');

if (!isset($_SESSION['admin'])) {
  header('Location: admin_login.php');
  exit;
}

require_once __DIR__ . '/../app/Models/Task.php';

$id = intval($_GET['id'] ?? 0);
$task = Task::find($id);

if (!$task) {
  $_SESSION['toast'] = ['msg' => 'Task not found!', 'type' => 'error'];
  header('Location: admin_dashboard.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $type = trim($_POST['type'] ?? '');
  $status = trim($_POST['status'] ?? 'pending');
  $userEmail = trim($_POST['user_email'] ?? '');

  $pdo = DB::pdo();
  $stmt = $pdo->prepare("UPDATE tasks SET type = ?, status = ?, user_email = ? WHERE id = ?");
  $stmt->execute([$type, $status, $userEmail, $id]);

  $_SESSION['toast'] = ['msg' => "Task #$id updated successfully!", 'type' => 'success'];
  header('Location: admin_dashboard.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Task #<?= $task['id'] ?> | Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: radial-gradient(circle at 20% 20%, #1a064e, #090314);
      font-family: 'Inter', sans-serif;
      color: #fff;
      min-height: 100vh;
    }

    .card {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      box-shadow: 0 0 20px rgba(120,80,255,0.2);
    }

    .btn-primary {
      background: linear-gradient(90deg, #6a11cb, #2575fc);
      border: none;
      border-radius: 10px;
      transition: 0.3s;
    }

    .btn-primary:hover {
      background: linear-gradient(90deg, #5b0bb5, #1a5ef1);
      transform: translateY(-2px);
    }

    .back-btn {
      color: #ddd;
      text-decoration: none;
      transition: 0.3s;
    }

    .back-btn:hover {
      color: #fff;
      text-decoration: underline;
    }

    footer {
      text-align: center;
      color: #aaa;
      margin-top: 2rem;
      padding: 1rem;
    }
  </style>
</head>
<body class="d-flex flex-column align-items-center justify-content-center">

<div class="container mt-5">
  <div class="card p-4 shadow-lg text-white">
    <h4 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Task #<?= $task['id'] ?></h4>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label text-light">Task Type</label>
        <input type="text" name="type" class="form-control bg-transparent text-white border-light" 
               value="<?= htmlspecialchars($task['type']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label text-light">Status</label>
        <select name="status" class="form-select bg-transparent text-white border-light">
          <option value="pending" <?= $task['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
          <option value="done" <?= $task['status'] == 'done' ? 'selected' : '' ?>>Done</option>
          <option value="error" <?= $task['status'] == 'error' ? 'selected' : '' ?>>Error</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label text-light">User Email</label>
        <input type="email" name="user_email" class="form-control bg-transparent text-white border-light" 
               value="<?= htmlspecialchars($task['user_email']) ?>" required>
      </div>

      <div class="mt-4 d-flex justify-content-between">
        <a href="admin_dashboard.php" class="back-btn"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save"></i> Save Changes</button>
      </div>
    </form>
  </div>
</div>

<footer>© 2025 <strong>MACSEEDS</strong> | Hackathon Series — lablab.ai</footer>

</body>
</html>
