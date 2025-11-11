<?php
start_secure_session();
if (!isset($_SESSION['user'])) {
  header('Location: ' . APP_URL . '/login.php');
  exit;
}
$userEmail = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Leave Request | TalentFlow</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: radial-gradient(circle at top, #1a093e, #110529, #08041a);
      color: #eee;
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .navbar {
      background: linear-gradient(90deg, #3a0ca3, #7209b7, #560bad);
      box-shadow: 0 2px 10px rgba(100, 50, 255, 0.3);
    }
    .navbar .navbar-brand {
      font-weight: 700;
      color: #fff !important;
    }
    .card {
      background: linear-gradient(135deg, #2a1457 0%, #3d1b72 100%);
      border: none;
      color: #f8f8ff;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(90, 40, 255, 0.2);
    }
    .btn-primary {
      background: linear-gradient(90deg, #7209b7, #560bad);
      border: none;
      border-radius: 10px;
    }
    .btn-primary:hover {
      background: linear-gradient(90deg, #8338ec, #3a0ca3);
      box-shadow: 0 0 10px rgba(150, 70, 255, 0.5);
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

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card p-4">
        <h4 class="fw-bold text-center mb-4 text-gradient">🗓 Apply for Leave</h4>
        <form action="<?= APP_URL ?>/leave/submit" method="POST">
          <div class="mb-3">
            <label class="form-label">Employee Name</label>
            <input type="text" class="form-control" name="employee_name" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Employee Email</label>
            <input type="email" class="form-control" name="employee_email" value="<?= htmlspecialchars($userEmail) ?>" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label">Leave Type</label>
            <select class="form-select" name="leave_type" required>
              <option value="Casual Leave">Casual Leave</option>
              <option value="Sick Leave">Sick Leave</option>
              <option value="Earned Leave">Earned Leave</option>
              <option value="Emergency Leave">Emergency Leave</option>
            </select>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Start Date</label>
              <input type="date" class="form-control" name="start_date" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">End Date</label>
              <input type="date" class="form-control" name="end_date" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Reason</label>
            <textarea class="form-control" name="reason" rows="3" placeholder="Briefly describe your reason..." required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Manager Email</label>
            <input type="email" class="form-control" name="manager_email" required>
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2">Submit Leave Request</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
