<?php
session_start();
define('APP_URL', 'http://localhost/talentflow/public');

// ✅ Redirect if not logged in
if (!isset($_SESSION['admin'])) {
  header('Location: admin_login.php');
  exit;
}

require_once __DIR__ . '/../app/Models/User.php';

// ✅ Fetch all users from DB
$users = User::all();

// ✅ Helper function for active sidebar link
function isActive($pageName) {
  return basename($_SERVER['PHP_SELF']) == $pageName ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TalentFlow | Users Directory</title>
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

    /* --- Sidebar Hover Expand --- */
    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      height: 100vh;
      width: 90px;
      background: var(--card-bg);
      border-right: 1px solid rgba(255,255,255,0.08);
      backdrop-filter: blur(14px);
      z-index: 1000;
      overflow-x: hidden;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 1.5rem 0;
      transition: width 0.3s ease;
    }

    .sidebar:hover {
      width: 240px;
      box-shadow: 4px 0 25px rgba(0,0,0,0.3);
    }

    .sidebar a {
      color: var(--text-light);
      text-decoration: none;
      display: flex;
      align-items: center;
      width: 100%;
      height: 55px;
      border-radius: 12px;
      margin-bottom: 0.5rem;
      padding: 0 18px;
      transition: 0.3s ease;
    }

    .sidebar a i {
      font-size: 1.6rem;
      min-width: 55px;
      text-align: center;
    }

    .sidebar-text {
      opacity: 0;
      max-width: 0;
      overflow: hidden;
      transition: opacity 0.3s ease, max-width 0.3s ease;
    }

    .sidebar:hover .sidebar-text {
      opacity: 1;
      max-width: 200px;
      margin-left: 10px;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: #fff;
    }

    .sidebar-brand {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      margin-bottom: 2rem;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: all 0.3s ease;
    }

    .sidebar:hover .sidebar-brand {
      width: 200px;
      border-radius: 12px;
      justify-content: flex-start;
      padding-left: 18px;
    }

    .main-content-wrapper {
      margin-left: 90px;
      background: radial-gradient(circle at 20% 20%, #1a064e, #090314);
      min-height: 100vh;
      transition: margin-left 0.3s ease;
      color: var(--text-light);
    }

    .sidebar:hover ~ .main-content-wrapper {
      margin-left: 240px;
    }

    /* --- User Cards --- */
    .user-card {
      background: var(--card-bg);
      border: 1px solid rgba(255,255,255,0.1);
      backdrop-filter: blur(10px);
      border-radius: 18px;
      padding: 1.2rem;
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .user-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 0 25px rgba(100,100,255,0.3);
    }

    .user-photo {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 0.75rem;
      border: 2px solid var(--accent);
      box-shadow: 0 0 10px rgba(255, 110, 199, 0.3);
    }

    .user-name {
      font-weight: 600;
      color: #fff;
      margin-bottom: 0.25rem;
    }

    .user-email {
      font-size: 0.9rem;
      color: #bbb;
      margin-bottom: 0.25rem;
    }

    .user-joined {
      font-size: 0.85rem;
      color: var(--accent);
    }

    .dashboard-header {
      padding: 2rem 3rem 1rem;
    }

    .dashboard-header h2 {
      font-weight: 700;
      background: linear-gradient(90deg, #b621fe, #1fd1f9);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    footer {
      text-align: center;
      padding: 1rem;
      color: #aaa;
      border-top: 1px solid rgba(255,255,255,0.1);
      margin-top: 2rem;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <a href="admin_dashboard.php" class="sidebar-brand">
    <i class="bi bi-shield-lock-fill"></i>
    <span class="sidebar-text">TalentFlow</span>
  </a>

  <ul class="nav-links">
    <li><a href="admin_dashboard.php" class="<?= isActive('admin_dashboard.php') ?>"><i class="bi bi-grid-1x2-fill"></i><span class="sidebar-text">Dashboard</span></a></li>
    <li><a href="users.php" class="<?= isActive('users.php') ?>"><i class="bi bi-person-lines-fill"></i><span class="sidebar-text">Users</span></a></li>
    <li><a href="staff.php" class="<?= isActive('staff.php') ?>"><i class="bi bi-people-fill"></i><span class="sidebar-text">Staffs</span></a></li>
    <li><a href="taskboard.php" class="<?= isActive('taskboard.php') ?>"><i class="bi bi-clipboard2-check-fill"></i><span class="sidebar-text">TaskBoard</span></a></li>
    <li><a href="leavemanagement.php" class="<?= isActive('leavemanagement.php') ?>"><i class="bi bi-calendar2-x-fill"></i><span class="sidebar-text">Leaves</span></a></li>
    <li><a href="events.php" class="<?= isActive('events.php') ?>"><i class="bi bi-calendar-event-fill"></i><span class="sidebar-text">Events</span></a></li>
    <li class="logout-link"><a href="admin_logout.php"><i class="bi bi-box-arrow-left"></i><span class="sidebar-text">Logout</span></a></li>
  </ul>
</div>

<!-- Main Content -->
<div class="main-content-wrapper">
  <div class="dashboard-header">
    <h2><i class="bi bi-person-lines-fill me-2"></i> Users Directory</h2>
    <p class="text-secondary">View and manage all users registered in TalentFlow.</p>
  </div>

  <div class="container-fluid px-4">
    <div class="row g-4">
      <?php if (empty($users)): ?>
        <p class="text-center mt-5">No users found yet.</p>
      <?php else: ?>
        <?php foreach ($users as $user): ?>
          <div class="col-md-4 col-lg-3">
            <div class="user-card">
              <?php 
                $photo = !empty($user['photo']) 
                  ? APP_URL . '/assets/images/' . htmlspecialchars($user['photo']) 
                  : APP_URL . '/assets/images/default.png';
              ?>
              <img src="<?= $photo ?>" alt="Profile" class="user-photo">
              <h5 class="user-name"><?= htmlspecialchars($user['name']) ?></h5>
              <p class="user-email"><?= htmlspecialchars($user['email']) ?></p>
              <p class="user-joined">Joined on <?= date('M d, Y', strtotime($user['created_at'])) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <footer>
    © 2025 <strong>MACSEEDS</strong> | Hackathon Series — lablab.ai
  </footer>
</div>
</body>
</html>
