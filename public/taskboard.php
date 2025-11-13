<?php
session_start();
define('APP_URL', 'http://localhost/talentflow/public');

if (!isset($_SESSION['admin'])) {
  header('Location: admin_login.php');
  exit;
}

require_once __DIR__ . '/../app/Models/Task.php';
$tasks = Task::all();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TalentFlow | Task Board</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --primary: #6a11cb;
      --secondary: #2575fc;
      --bg-dark: #0b0521;
      --card-bg: rgba(255,255,255,0.05);
      --text-light: #f2f1ff;
      --accent: #ff6ec7;
    }
    body {
      font-family: 'Inter', sans-serif;
      background: radial-gradient(circle at 20% 20%, #1a064e, #090314);
      color: var(--text-light);
      min-height: 100vh;
    }
    .card-glass {
      background: var(--card-bg);
      border: 1px solid rgba(255,255,255,0.08);
      backdrop-filter: blur(14px);
      border-radius: 20px;
      padding: 1.5rem;
      box-shadow: 0 0 25px rgba(100,100,255,0.15);
    }
    .card-glass:hover {
      transform: translateY(-4px);
      transition: 0.3s ease;
      box-shadow: 0 0 35px rgba(120, 80, 255, 0.3);
    }
    .table {
      color: #fff;
      font-size: 0.95rem;
    }
    .table th {
      color: var(--accent);
      font-weight: 600;
      border-bottom: 1px solid rgba(255,255,255,0.2);
    }
    .badge {
      border-radius: 12px;
      padding: 0.4rem 0.7rem;
      font-weight: 500;
    }
    .action-btns .btn {
      padding: 0.3rem 0.5rem;
      border-radius: 8px;
    }
    .page-header {
      padding: 2rem 3rem 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .page-header h2 {
      font-weight: 700;
      background: linear-gradient(90deg, #b621fe, #1fd1f9);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .btn-gradient {
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      border: none;
      color: #fff;
      border-radius: 10px;
      padding: 0.6rem 1.2rem;
      font-weight: 500;
      transition: 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .btn-gradient:hover {
      transform: scale(1.05);
      opacity: 0.9;
      color: #fff;
    }
  </style>
</head>
<body>

<div class="page-header">
  <div>
    <h2><i class="bi bi-bar-chart-fill"></i> HR TaskBoard Overview</h2>
    <p class="text-secondary">Insights into tasks, users, and workflow performance.</p>
  </div>
  <!-- ✅ Back to Dashboard Button -->
  <a href="admin_dashboard.php" class="btn-gradient">
    <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
  </a>
</div>

<div class="container-fluid px-4">
  <div class="card-glass">
    <h5 class="mb-3"><i class="bi bi-kanban"></i> All HR Tasks</h5>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Status</th>
            <th>User</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($tasks as $task): ?>
          <tr>
            <td><?= $task['id'] ?></td>
            <td><?= ucfirst($task['type']) ?></td>
            <td>
              <?php if ($task['status'] == 'done'): ?>
                <span class="badge bg-success">Done</span>
              <?php elseif ($task['status'] == 'pending'): ?>
                <span class="badge bg-warning text-dark">Pending</span>
              <?php else: ?>
                <span class="badge bg-secondary"><?= ucfirst($task['status']) ?></span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($task['user_email']) ?></td>
            <td class="action-btns">
              <a href="task_edit.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-warning text-dark" title="Edit">
                <i class="bi bi-pencil-square"></i>
              </a>
              <form method="post" action="task_action.php" class="d-inline" onsubmit="return confirm('Delete this task?');">
                <input type="hidden" name="id" value="<?= $task['id'] ?>">
                <button name="action" value="delete" class="btn btn-sm btn-danger" title="Delete">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
