<?php
session_start();
define('APP_URL', 'http://localhost/talentflow/public');

if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}

require_once __DIR__ . '/../app/Models/User.php';

$staffs = User::all();

if (isset($_GET['kick'])) {
    $id = $_GET['kick'];
    User::delete($id);
    header("Location: staff.php?removed=success");
    exit;
}

function isActive($pageName) {
    return basename($_SERVER['PHP_SELF']) == $pageName ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TalentFlow | Staffs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
    --primary: #6a11cb;
    --secondary: #2575fc;
    --card-bg: rgba(255,255,255,0.05);
    --text-light: #e8e6f0;
    --text-muted: #a09cb8;
}

body {
    background: #090314;
    font-family: 'Inter', sans-serif;
    color: var(--text-light);
}

/* -----------------------------
   STATIC SIDEBAR (NO TRANSITION)
------------------------------ */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 250px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(14px);
    border-right: 1px solid rgba(255,255,255,0.08);
    padding: 1.2rem 0;
    z-index: 1000;
}

.sidebar .nav-links {
    list-style: none;
    padding: 0 1rem;
    margin: 0;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.sidebar a {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 15px;
    text-decoration: none;
    color: var(--text-light);
    font-size: 0.95rem;
    border-radius: 12px;
    transition: 0.25s;
}

.sidebar a i {
    font-size: 1.3rem;
    width: 28px;
    text-align: center;
}

.sidebar a:hover {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
}

.sidebar a.active {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff !important;
}

.nav-spacer {
    flex-grow: 1;
}

.logout-section {
    padding-top: 10px;
    border-top: 1px solid rgba(255,255,255,0.08);
}

/* -----------------------------
   MAIN CONTENT
------------------------------ */
.main-content-wrapper {
    margin-left: 250px;
    background: radial-gradient(circle at 20% 20%, #1a064e, #090314);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* -----------------------------
   HEADER
------------------------------ */
.main-header {
    display: flex;
    justify-content: space-between;
    padding: 1.5rem 2.5rem;
    background: rgba(11,5,33,0.5);
    border-bottom: 1px solid rgba(255,255,255,0.08);
    backdrop-filter: blur(10px);
}

.header-greeting h2 {
    font-size: 1.8rem;
    font-weight: 700;
    background: linear-gradient(90deg, #b621fe, #1fd1f9);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin: 0;
}

.admin-profile-btn {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid var(--primary);
    background: #ffffff10;
    box-shadow: 0 0 15px rgba(106,17,203,0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.3s ease;
}

.admin-profile-btn:hover {
    transform: scale(1.08);
    box-shadow: 0 0 25px rgba(106,17,203,0.7);
}

.admin-profile-btn img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

/* -----------------------------
   MAIN CONTENT CONTAINER
------------------------------ */
.main-container {
    padding: 2.5rem;
    flex-grow: 1;
}

.section-title {
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: 0;
}

/* -----------------------------
   STAFF CARDS
------------------------------ */
.staff-card {
    background: rgba(255,255,255,0.06);
    border-radius: 18px;
    padding: 1.5rem 1rem;
    text-align: center;
    border: 1px solid rgba(255,255,255,0.08);
    backdrop-filter: blur(10px);
    transition: 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.staff-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 0 25px rgba(106,17,203,0.4);
}

.staff-photo {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--secondary);
    box-shadow: 0 0 12px rgba(37,117,252,0.5);
    margin-bottom: 1rem;
}

.staff-name {
    font-size: 1rem;
    font-weight: 600;
}

.staff-email {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 8px;
}

.staff-date {
    font-size: 0.8rem;
    color: #bbb;
}

/* -----------------------------
   FOOTER
------------------------------ */
.main-footer {
    padding: 1.5rem 2.5rem;
    text-align: center;
    color: var(--text-muted);
    border-top: 1px solid rgba(255,255,255,0.08);
    margin-top: 2rem;
}
</style>


</head>
<body>

<div class="sidebar">
    <ul class="nav-links">
        <li>
            <a href="admin_dashboard.php" class="sidebar-brand">
                <i class="bi bi-shield-lock-fill"></i>
                <span class="sidebar-text">TalentFlow</span>
            </a>
        </li>
        <li><a href="admin_dashboard.php" class="<?= isActive('admin_dashboard.php') ?>"><i class="bi bi-grid-1x2-fill"></i><span class="sidebar-text">Dashboard</span></a></li><br>
        <li><a href="staff.php" class="<?= isActive('staff.php') ?>"><i class="bi bi-people-fill"></i><span class="sidebar-text">Staffs</span></a></li><br>
        <li><a href="taskboard.php" class="<?= isActive('taskboard.php') ?>"><i class="bi bi-clipboard2-check-fill"></i><span class="sidebar-text">TaskBoard</span></a></li><br>
        <li><a href="leavemanagement.php" class="<?= isActive('leavemanagement.php') ?>"><i class="bi bi-calendar2-x-fill"></i><span class="sidebar-text">Leave Manager</span></a></li><br>
        <li><a href="events.php" class="<?= isActive('events.php') ?>"><i class="bi bi-calendar-event-fill"></i><span class="sidebar-text">Events</span></a></li><br>
        <li><a href="performance.php" class="<?= isActive('performance.php') ?>"><i class="bi bi-graph-up-arrow"></i><span class="sidebar-text">Performance</span></a></li><br>
        <li class="nav-spacer"></li>
        <li>
            <a href="admin_logout.php">
                <i class="bi bi-box-arrow-left"></i>
                <span class="sidebar-text">Logout</span>
            </a>
        </li>
    </ul>
</div>

<div class="main-content-wrapper">

    <header class="main-header">
        <div class="header-greeting">
            <h2>Staff Management</h2>
            <p style="color: var(--text-muted);">View and manage staff members.</p>
        </div>

        <div class="header-profile">
            <?php
            $adminPhoto = !empty($_SESSION['admin']['photo'])
                ? 'assets/images/' . htmlspecialchars($_SESSION['admin']['photo'])
                : 'assets/images/default.png';
            ?>
            <a href="admin_profile.php" class="admin-profile-btn">
                <img src="<?= APP_URL . '/' . $adminPhoto ?>" alt="Admin">
            </a>
        </div>
    </header>

    <div class="main-container">

        <?php if (isset($_GET['removed'])): ?>
            <div class="alert alert-success">Staff removed successfully.</div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="section-title"><i class="bi bi-people-fill"></i> Staff Members</h5>
            <a href="staff_add.php" class="btn btn-primary" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none;">
                <i class="bi bi-plus-lg"></i> Add Staff
            </a>
        </div>

        <div class="row g-4">
            <?php foreach ($staffs as $staff): ?>

                <?php
                $photo = !empty($staff['photo'])
                    ? 'assets/images/' . htmlspecialchars($staff['photo'])
                    : 'assets/images/default.png';
                ?>

                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <div class="staff-card">

                        <img src="<?= APP_URL . '/' . $photo ?>" class="staff-photo">

                        <div class="staff-name"><?= htmlspecialchars($staff['name']) ?></div>
                        <div class="staff-email"><?= htmlspecialchars($staff['email']) ?></div>
                        <div class="staff-date">Joined: <?= date('d M Y', strtotime($staff['created_at'])) ?></div>

                        <a href="staff.php?kick=<?= $staff['id'] ?>"
                           onclick="return confirm('Remove this staff member permanently?')"
                           class="btn btn-outline-danger btn-sm mt-3">
                            <i class="bi bi-trash"></i> Kick
                        </a>

                    </div>
                </div>

            <?php endforeach; ?>
        </div>

    </div>

    <footer class="main-footer">
        © <?= date('Y') ?> MACSEEDS | TalentFlow
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
