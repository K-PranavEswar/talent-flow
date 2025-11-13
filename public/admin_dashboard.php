<?php
session_start();
define('APP_URL', 'http://localhost/talentflow/public');

if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}

require_once __DIR__ . '/../app/Models/Task.php';
require_once __DIR__ . '/../app/Models/Leave.php';
require_once __DIR__ . '/../app/Models/User.php';

$tasks = Task::all();
$leaves = Leave::all();
$staffs = User::all();

$pending_tasks = array_filter($tasks, fn($t) => $t['status'] == 'pending');
$completed_tasks = array_filter($tasks, fn($t) => $t['status'] == 'done');

function isActive($page) {
    return basename($_SERVER['PHP_SELF']) === $page ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>TalentFlow | Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
    --primary: #6a11cb;
    --secondary: #2575fc;
    --card-bg: rgba(255,255,255,0.05);
    --text-light: #e8e6f0;
    --text-muted: #a09cb8;
    --accent: #2575fc;
}

body {
    font-family: 'Inter', sans-serif;
    background: #0a031c;
    color: var(--text-light);
}

::-webkit-scrollbar { width: 8px; }
::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 10px;
}

/* ------------------------------ */
/* PERFECT MATCH TALENTFLOW SIDEBAR */
/* ------------------------------ */

.sidebar {
    width: 240px;
    height: 100vh;
    background: #120b24;
    padding: 1.5rem 0;
    position: fixed;
    top: 0;
    left: 0;
    border-right: 1px solid rgba(255,255,255,0.05);
    display: flex;
    flex-direction: column;
}

.sidebar-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 1.1rem;
    font-weight: 600;
    color: white;
    padding: 0 20px 25px 20px;
}

.sidebar-logo i {
    font-size: 1.3rem;
}

.sidebar-menu {
    list-style: none;
    margin: 0;
    padding: 0;
}

.sidebar-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 20px;
    color: #dcdcdc;
    text-decoration: none;
    font-size: 0.95rem;
    border-radius: 10px;
    transition: 0.25s;
}

.sidebar-item i {
    font-size: 1.2rem;
}

/* ACTIVE ITEM (Exact Purple Gradient) */
.sidebar-item.active {
    background: linear-gradient(90deg, #7a20ff, #3f7cff);
    color: white;
}

.sidebar-item:hover {
    background: rgba(255,255,255,0.08);
    color: white;
}

/* Logout fixed at bottom */
.logout-section {
    position: absolute;
    bottom: 20px;
    width: 100%;
}

/* ------------------------------ */
/* MAIN CONTENT AREA */
/* ------------------------------ */

.main-content-wrapper {
    margin-left: 240px;
    transition: 0.3s;
}

.main-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.admin-profile-btn {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid var(--primary);
}

.admin-profile-btn img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ------------------------------ */
/* MAIN CONTAINER */
/* ------------------------------ */

.main-container {
    padding: 2rem;
}

.card-glass {
    background: var(--card-bg);
    border-radius: 20px;
    padding: 1.5rem;
    border: 1px solid rgba(255,255,255,0.1);
}

/* ------------------------------ */
/* STATS */
/* ------------------------------ */

.stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.stat-box {
    text-align: center;
    padding: 1.5rem;
}

.stat-box h3 {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.section-title {
    font-size: 1.4rem;
    margin-bottom: 1rem;
}

/* ------------------------------ */
/* STAFF CARDS */
/* ------------------------------ */

.staff-card {
    background: rgba(255,255,255,0.05);
    border-radius: 20px;
    padding: 1.5rem;
    text-align: center;
    border: 1px solid rgba(255,255,255,0.1);
    transition: 0.25s;
}

.staff-card:hover {
    transform: translateY(-4px);
}

.staff-photo {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    border: 3px solid var(--secondary);
    object-fit: cover;
    margin-bottom: 1rem;
}

/* ------------------------------ */
/* FOOTER */
/* ------------------------------ */

.main-footer {
    text-align: center;
    padding: 1.5rem;
    color: var(--text-muted);
}
</style>
</head>

<body>

<div class="sidebar">

    <div class="sidebar-logo">
        <i class="bi bi-shield-shaded"></i>
        <span>TalentFlow</span>
    </div>
    <br>
    <ul class="sidebar-menu">

        <li>
            <a href="admin_dashboard.php" class="sidebar-item <?= isActive('admin_dashboard.php') ?>">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <br>
        <li>
            <a href="staff.php" class="sidebar-item <?= isActive('staff.php') ?>">
                <i class="bi bi-people-fill"></i>
                <span>Staffs</span>
            </a>
        </li>
        <br>
        <li>
            <a href="taskboard.php" class="sidebar-item <?= isActive('taskboard.php') ?>">
                <i class="bi bi-clipboard2-check-fill"></i>
                <span>TaskBoard</span>
            </a>
        </li>
        <br>
        <li>
            <a href="leavemanagement.php" class="sidebar-item <?= isActive('leavemanagement.php') ?>">
                <i class="bi bi-calendar2-x-fill"></i>
                <span>Leave Manager</span>
            </a>
        </li>
        <br>
        <li>
            <a href="events.php" class="sidebar-item <?= isActive('events.php') ?>">
                <i class="bi bi-calendar-event-fill"></i>
                <span>Events</span>
            </a>
        </li>
        <br>
        <li>
            <a href="performance.php" class="sidebar-item <?= isActive('performance.php') ?>">
                <i class="bi bi-graph-up"></i>
                <span>Performance</span>
            </a>
        </li>
        <br>
        <li class="logout-section">
            <a href="admin_logout.php" class="sidebar-item">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </li>

    </ul>

</div>

<div class="main-content-wrapper">

<header class="main-header">
    <div>
        <h2 style="background: linear-gradient(90deg,#b621fe,#1fd1f9); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">Welcome, Admin 👋</h2>
        <p style="color:var(--text-muted)">Here's your performance overview for today.</p>
    </div>

    <a href="admin_profile.php" class="admin-profile-btn">
        <?php
        $adminPhoto = $_SESSION['admin']['photo'] ?? '';
        $path = __DIR__ . '/assets/images/' . $adminPhoto;
        $photo = (!empty($adminPhoto) && file_exists($path))
            ? 'assets/images/' . htmlspecialchars($adminPhoto)
            : 'assets/images/default.png';
        ?>
        <img src="<?= APP_URL . '/' . $photo ?>">
    </a>
</header>

<div class="main-container">

    <div class="card-glass mb-4">
        <h5><i class="bi bi-graph-up-arrow"></i> Live Activity Feed (Simulated)</h5>
        <div style="height:280px;"><canvas id="realTimeChart"></canvas></div>
    </div>

    <div class="stats mb-4">
        <div class="stat-box card-glass"><h3><?= count($tasks) ?></h3><p>Total HR Tasks</p></div>
        <div class="stat-box card-glass"><h3><?= count($leaves) ?></h3><p>Total Leave Requests</p></div>
        <div class="stat-box card-glass"><h3><?= count($completed_tasks) ?></h3><p>Completed Tasks</p></div>
        <div class="stat-box card-glass"><h3><?= count($pending_tasks) ?></h3><p>Pending Tasks</p></div>
    </div>

    <h5 class="section-title"><i class="bi bi-people"></i> Staff Members</h5>

    <div class="row g-4">
        <?php foreach ($staffs as $staff): ?>
            <?php
            $sf = $staff['photo'] ?? '';
            $path = __DIR__ . '/assets/images/' . $sf;
            $photo = (!empty($sf) && file_exists($path))
                ? 'assets/images/' . htmlspecialchars($sf)
                : 'assets/images/default.png';
            ?>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="staff-card">
                    <img src="<?= APP_URL . '/' . $photo ?>" class="staff-photo">
                    <div class="staff-name"><?= htmlspecialchars($staff['name']) ?></div>
                    <div class="staff-position"><?= htmlspecialchars($staff['position'] ?? 'Staff') ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<footer class="main-footer">
    © <?= date('Y') ?> <strong>MACSEEDS</strong> | TalentFlow
</footer>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById('realTimeChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['-30s','-25s','-20s','-15s','-10s','-5s','Now'],
            datasets: [
                {
                    label: 'User Logins',
                    data: [5,8,12,10,15,11,18],
                    borderColor: '#b621fe',
                    backgroundColor: 'rgba(182,33,254,0.15)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Tasks Completed',
                    data: [2,3,2,5,4,6,5],
                    borderColor: '#1fd1f9',
                    backgroundColor: 'rgba(31,209,249,0.15)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { color: '#bbb' } },
                x: { ticks: { color: '#bbb' } }
            },
            plugins: { legend: { labels: { color: '#fff' } } }
        }
    });
});
</script>

</body>
</html>
