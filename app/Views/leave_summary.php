<?php
start_secure_session();
if (!isset($_SESSION['user'])) {
  header('Location: ' . APP_URL . '/login.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Leave Summary | TalentFlow</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: radial-gradient(circle at top, #1a093e, #110529, #08041a);
      color: #f1e8ff;
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .navbar {
      background: linear-gradient(90deg, #3a0ca3, #7209b7, #560bad);
      box-shadow: 0 2px 10px rgba(130, 60, 255, 0.3);
    }
    .navbar-brand {
      font-weight: 700;
      color: #fff !important;
    }
    .card {
      background: linear-gradient(135deg, #2a1457 0%, #3d1b72 100%);
      border: none;
      color: #f1e8ff;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(87, 23, 255, 0.2);
    }
    .table {
      color: #eaeaff;
    }
    .table thead {
      background: linear-gradient(90deg, #7209b7, #560bad);
    }
    .badge {
      border-radius: 8px;
      padding: 0.5em 0.8em;
      font-size: 0.8rem;
    }
    .badge-approved {
      background-color: #00c896;
    }
    .badge-pending {
      background-color: #fca311;
    }
    .badge-rejected {
      background-color: #e63946;
    }
    .btn-action {
      border: none;
      border-radius: 8px;
      padding: 6px 12px;
      font-size: 0.85rem;
      transition: all 0.2s;
    }
    .btn-approve {
      background: linear-gradient(90deg, #00c896, #029e70);
      color: #fff;
    }
    .btn-approve:hover {
      box-shadow: 0 0 10px rgba(0, 200, 150, 0.4);
    }
    .btn-reject {
      background: linear-gradient(90deg, #d00000, #9d0208);
      color: #fff;
    }
    .btn-reject:hover {
      box-shadow: 0 0 10px rgba(255, 80, 80, 0.4);
    }
    footer {
      text-align: center;
      margin-top: auto;
      padding: 1rem;
      background: #10072e;
      color: #b9a8ff;
      font-size: 0.9rem;
    }
  </style>
</head>

<body>
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="<?= APP_URL ?>">TalentFlow</a>
    <div class="ms-auto">
      <a href="<?= APP_URL ?>/leave/form" class="btn btn-outline-light btn-sm"><i class="bi bi-plus-lg"></i> Apply Leave</a>
      <a href="<?= APP_URL ?>/index.php" class="btn btn-outline-light btn-sm"><i class="bi bi-house"></i> Dashboard</a>
      <a href="<?= APP_URL ?>/login.php" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <div class="card p-4">
    <h4 class="fw-bold text-center mb-4">📋 Employee Leave Summary</h4>

    <?php if (empty($leaves)): ?>
      <div class="text-center text-muted py-5">
        <i class="bi bi-calendar2-x fs-1"></i>
        <p class="mt-3">No leave requests found.</p>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table align-middle table-hover">
          <thead class="text-white">
            <tr>
              <th>#</th>
              <th>Employee</th>
              <th>Email</th>
              <th>Leave Type</th>
              <th>Dates</th>
              <th>Reason</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($leaves as $index => $leave): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($leave['employee_name']) ?></td>
                <td><?= htmlspecialchars($leave['employee_email']) ?></td>
                <td><?= htmlspecialchars($leave['leave_type']) ?></td>
                <td><?= htmlspecialchars($leave['start_date']) ?> → <?= htmlspecialchars($leave['end_date']) ?></td>
                <td><?= htmlspecialchars($leave['reason']) ?></td>
                <td>
                  <?php if ($leave['status'] == 'Approved'): ?>
                    <span class="badge badge-approved">Approved</span>
                  <?php elseif ($leave['status'] == 'Rejected'): ?>
                    <span class="badge badge-rejected">Rejected</span>
                  <?php else: ?>
                    <span class="badge badge-pending">Pending</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($leave['status'] == 'Pending'): ?>
                    <a href="<?= APP_URL ?>/leave/approve?id=<?= $leave['id'] ?>" class="btn-action btn-approve"><i class="bi bi-check-lg"></i></a>
                    <a href="<?= APP_URL ?>/leave/reject?id=<?= $leave['id'] ?>" class="btn-action btn-reject"><i class="bi bi-x-lg"></i></a>
                  <?php else: ?>
                    <span class="text-muted small">No actions</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<footer>
  © 2025 <strong>MACSEEDS</strong> | Hackathon Series — <a href="https://lablab.ai" target="_blank" class="text-light text-decoration-none">lablab.ai</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
