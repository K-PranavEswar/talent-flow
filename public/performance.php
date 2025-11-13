<?php
session_start();
define('APP_URL', 'http://localhost/talentflow/public');

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require_once __DIR__ . '/../app/Models/DB.php';
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/Task.php'; // Note: Task model is included but not used here

$pdo = DB::pdo();
$staffs = User::all();

/**
 * Helper function to set the 'active' class on the current sidebar item.
 * @param string $pageName The name of the PHP file (e.g., 'staff.php')
 * @return string 'active' if it's the current page, otherwise empty string
 */
function isActive($pageName) {
    return basename($_SERVER['PHP_SELF']) == $pageName ? 'active' : '';
}


/* ------------------------------------------
    FETCH PERFORMANCE DATA (employee_performance)
--------------------------------------------- */
$perf_stmt = $pdo->prepare("
    SELECT ep.*, u.name 
    FROM employee_performance ep
    INNER JOIN users u ON ep.employee_id = u.id
    ORDER BY ep.updated_at DESC
");
$perf_stmt->execute();
$performance_data = $perf_stmt->fetchAll(PDO::FETCH_ASSOC);

/* ------------------------------------------
    SCORE CALCULATION (AUTO PER STAFF)
--------------------------------------------- */

/**
 * Calculates the final performance score based on weighted metrics.
 * @param float|int $tasks_done
 * @param float|int $attendance
 * @param float|int $teamwork
 * @param float|int $skill
 * @return float
 */
function calculateScore($tasks_done, $attendance, $teamwork, $skill) {
    return ($tasks_done * 0.4) + ($attendance * 0.3) + ($teamwork * 0.2) + ($skill * 0.1);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_review'])) {

    $employee_id = $_POST['employee_id'];
    $tasks = $_POST['tasks'];
    $attendance = $_POST['attendance'];
    $teamwork = $_POST['teamwork'];
    $skill = $_POST['skill'];
    $review = trim($_POST['review']);
    $manager = $_SESSION['admin']['name'];

    // calculate score using the defined function
    $score = calculateScore($tasks, $attendance, $teamwork, $skill);

    $stmt = $pdo->prepare("
        INSERT INTO employee_performance 
        (employee_id, month, performance_score, tasks_completed, attendance_score, teamwork_score, skill_score, review, manager_name)
        VALUES (?, DATE_FORMAT(NOW(), '%Y-%m'), ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $employee_id,
        $score,
        $tasks,
        $attendance,
        $teamwork,
        $skill,
        $review,
        $manager
    ]);

    header("Location: performance.php?success=1");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>TalentFlow | Performance</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* Apply same sidebar style from staff.php */
body {
    background: #090314;
    font-family: 'Inter', sans-serif;
    color: #eee;
}

/* --------------------------
    FIXED TALENTFLOW SIDEBAR
--------------------------- */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px; 
    height: 100vh;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(12px);
    border-right: 1px solid rgba(255,255,255,0.08);
    padding: 1.5rem 0;
    display: flex;
    flex-direction: column;
    z-index: 1000;
}

.sidebar .sidebar-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 20px 25px 20px;
    font-size: 1.2rem;
    font-weight: 600;
    color: white;
}

.sidebar-logo i {
    font-size: 1.5rem;
    color: #b621fe;
}

.sidebar-menu {
    list-style: none;
    padding: 0 10px;
    margin: 0;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.sidebar-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 15px;
    margin-bottom: 8px;
    color: #dcdcdc;
    text-decoration: none;
    border-radius: 10px;
    transition: 0.25s ease;
    font-size: 0.95rem;
}

.sidebar-item i {
    font-size: 1.25rem;
    width: 22px;
    text-align: center;
}

.sidebar-item:hover {
    background: rgba(255,255,255,0.08);
    color: white;
}

.sidebar-item.active {
    background: linear-gradient(135deg, #b621fe, #1fd1f9);
    box-shadow: 0 0 15px rgba(182,33,254,0.4);
    color: white !important;
}

.logout-section {
    margin-top: auto;
    padding-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.08);
}

/* MAIN CONTENT WRAPPER */
.main-content-wrapper {
    margin-left: 250px;
    padding: 2rem;
}

/* CARDS */
.card-glass {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 15px;
    padding: 20px;
    backdrop-filter: blur(10px);
}
</style>
</head>

<body>

<div class="sidebar">
    <div class="sidebar-logo">
        <i class="bi bi-shield-shaded"></i>
        TalentFlow
    </div>

    <ul class="sidebar-menu">

        <li><a href="admin_dashboard.php" class="sidebar-item <?= isActive('admin_dashboard.php') ?>">
            <i class="bi bi-grid-fill"></i> <span>Dashboard</span></a></li>

        <li><a href="staff.php" class="sidebar-item <?= isActive('staff.php') ?>">
            <i class="bi bi-people-fill"></i> <span>Staffs</span></a></li>

        <li><a href="taskboard.php" class="sidebar-item <?= isActive('taskboard.php') ?>">
            <i class="bi bi-clipboard2-check-fill"></i> <span>TaskBoard</span></a></li>

        <li><a href="leavemanagement.php" class="sidebar-item <?= isActive('leavemanagement.php') ?>">
            <i class="bi bi-calendar2-x-fill"></i> <span>Leave Manager</span></a></li>

        <li><a href="events.php" class="sidebar-item <?= isActive('events.php') ?>">
            <i class="bi bi-calendar-event-fill"></i> <span>Events</span></a></li>

        <li><a href="performance.php" class="sidebar-item <?= isActive('performance.php') ?>">
            <i class="bi bi-graph-up-arrow"></i> <span>Performance</span></a></li>

        <li class="logout-section">
            <a href="admin_logout.php" class="sidebar-item">
                <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
            </a>
        </li>

    </ul>
</div>

<div class="main-content-wrapper">

<h2 class="mb-1" style="background:linear-gradient(90deg,#b621fe,#1fd1f9);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
    Employee Performance
</h2>
<p class="text-secondary">Review performance, productivity trends, and monthly scores.</p>

<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success">Performance review saved successfully.</div>
<?php endif; ?>

<div class="card-glass mt-4">
    <h4 class="mb-3"><i class="bi bi-star-fill"></i> Latest Performance Reviews</h4>

    <table class="table table-dark table-hover align-middle">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Score</th>
                <th>Tasks</th>
                <th>Attendance</th>
                <th>Teamwork</th>
                <th>Skills</th>
                <th>Manager</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($performance_data as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><span class="badge bg-info"><?= $p['performance_score'] ?></span></td>
                <td><?= $p['tasks_completed'] ?></td>
                <td><?= $p['attendance_score'] ?></td>
                <td><?= $p['teamwork_score'] ?></td>
                <td><?= $p['skill_score'] ?></td>
                <td><?= htmlspecialchars($p['manager_name']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="card-glass mt-4">
    <h4><i class="bi bi-pencil-square"></i> Add Performance Review</h4>
    
    <form method="POST" class="row g-3 mt-2">

        <div class="col-md-4">
            <label class="form-label">Select Employee</label>
            <select name="employee_id" class="form-select" required>
                <option value="">Choose...</option>
                <?php foreach ($staffs as $s): ?>
                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Tasks</label>
            <input type="number" name="tasks" class="form-control" min="0" required id="tasks_input">
        </div>

        <div class="col-md-2">
            <label class="form-label">Attendance</label>
            <input type="number" name="attendance" class="form-control" min="0" max="100" required id="attendance_input">
        </div>

        <div class="col-md-2">
            <label class="form-label">Teamwork</label>
            <input type="number" name="teamwork" class="form-control" min="0" max="100" required id="teamwork_input">
        </div>

        <div class="col-md-2">
            <label class="form-label">Skill</label>
            <input type="number" name="skill" class="form-control" min="0" max="100" required id="skill_input">
        </div>

        <div class="col-12">
            <label class="form-label">Review Comments</label>
            <textarea class="form-control" name="review" rows="2"></textarea>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">Final Score (Auto-Calculated)</label>
            <input type="number" id="final_score" class="form-control" readonly style="background-color: #333; color: #fff;">
        </div>

        <div class="col-12 mt-3">
            <button name="add_review" class="btn btn-primary">Submit Review</button>
        </div>
    </form>
</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all the metric inputs
    const tasksInput = document.getElementById('tasks_input');
    const attendanceInput = document.getElementById('attendance_input');
    const teamworkInput = document.getElementById('teamwork_input');
    const skillInput = document.getElementById('skill_input');
    const scoreOutput = document.getElementById('final_score');

    // Define the calculation function in JS (must match PHP)
    function updateScore() {
        // Use parseFloat and default to 0 if input is empty or invalid
        const tasks = parseFloat(tasksInput.value) || 0;
        const attendance = parseFloat(attendanceInput.value) || 0;
        const teamwork = parseFloat(teamworkInput.value) || 0;
        const skill = parseFloat(skillInput.value) || 0;

        // Same calculation logic as the PHP function
        const score = (tasks * 0.4) + (attendance * 0.3) + (teamwork * 0.2) + (skill * 0.1);
        
        // Display the score, fixed to 2 decimal places
        scoreOutput.value = score.toFixed(2);
    }

    // Add event listeners to all metric inputs
    const inputs = [tasksInput, attendanceInput, teamworkInput, skillInput];
    inputs.forEach(input => {
        // 'input' event fires immediately on change
        input.addEventListener('input', updateScore);
    });
});
</script>

</body>
</html>