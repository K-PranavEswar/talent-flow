<?php
session_start();
require_once __DIR__ . '/../app/Models/DB.php';

if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}

$pdo = DB::pdo();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id = intval($_POST['id']);
    $action = $_POST['action'] === 'approve' ? 'Approved' : ($_POST['action'] === 'reject' ? 'Rejected' : null);

    if ($action !== null) {
        $stmt = $pdo->prepare("UPDATE leaves SET status = ? WHERE id = ?");
        if ($stmt->execute([$action, $id])) {
            $success = "Leave #{$id} marked as {$action}.";
        } else {
            $error = "Failed to update leave status.";
        }
    }
}

$statusFilter = $_GET['status'] ?? 'All';
$search = trim($_GET['search'] ?? '');

$where = [];
$params = [];

if ($statusFilter !== 'All' && in_array($statusFilter, ['Pending', 'Approved', 'Rejected'])) {
    $where[] = "status = ?";
    $params[] = $statusFilter;
}

if ($search !== '') {
    $where[] = "(employee_name LIKE ? OR employee_email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

$sql = "SELECT * FROM leaves {$where_sql} ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$leaves = $stmt->fetchAll(PDO::FETCH_ASSOC);

$countStmt = $pdo->query("SELECT
    SUM(status = 'Pending') AS pending,
    SUM(status = 'Approved') AS approved,
    SUM(status = 'Rejected') AS rejected,
    COUNT(*) as total
FROM leaves");
$counts = $countStmt->fetch(PDO::FETCH_ASSOC);

function isActive($p) {
    return basename($_SERVER['PHP_SELF']) === $p ? 'active' : '';
}
function h($s){ return htmlspecialchars($s, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>TalentFlow | Leave Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
:root{
  --bg:#0d0c28;
  --panel:#161534;
  --muted:#c2c1d1;
  --gradient: linear-gradient(135deg,#6b4dff,#3e8bff);
  --border: rgba(255,255,255,0.05);
}
*{box-sizing:border-box}
body{
  margin:0;
  font-family:Inter,system-ui,-apple-system,'Segoe UI',Roboto,'Helvetica Neue',Arial;
  background: #090314;
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

/* Main content */
.main-content-wrapper{margin-left:250px;padding:28px 32px;min-height:100vh}
.header-row{display:flex;justify-content:space-between;align-items:center;gap:16px}
.header-row h2{margin:0;background:var(--gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.header-row p{margin:0;color:var(--muted)}

/* Cards */
.summary-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-top:18px}
.card-box{background:var(--panel);border:1px solid var(--border);padding:18px;border-radius:12px;text-align:center}
.card-box h3{margin:0;font-size:26px}
.card-box p{margin:0;color:var(--muted)}

/* Filters */
.filters{display:flex;gap:12px;align-items:center;margin-top:18px;flex-wrap:wrap}
.form-control, .form-select{background:rgba(255,255,255,0.02);border:1px solid var(--border);color:#fff}

/* Leave list */
.leave-list{margin-top:22px;display:grid;gap:12px}
.leave-item{background:var(--panel);border:1px solid var(--border);border-radius:12px;padding:14px;display:flex;justify-content:space-between;align-items:center;gap:12px}
.leave-left{display:flex;gap:14px;align-items:center}
.avatar{width:52px;height:52px;border-radius:10px;background:linear-gradient(180deg,#2b2456,#111020);display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff}
.leave-meta h5{margin:0 0 4px 0}
.leave-meta p{margin:0;color:var(--muted);font-size:13px}

/* status badges */
.badge-status{padding:6px 10px;border-radius:999px;font-weight:600;font-size:13px}
.pend{background:rgba(255,193,7,0.12);color:#ffd54f;border:1px solid rgba(255,193,7,0.12)}
.appr{background:rgba(56,203,118,0.08);color:#7ef3b0;border:1px solid rgba(56,203,118,0.08)}
.rej{background:rgba(255,107,107,0.08);color:#ff8b8b;border:1px solid rgba(255,107,107,0.08)}

/* buttons */
.btn-approve{background:#7ef3b0;border:none;color:#05120a}
.btn-reject{background:#ff8b8b;border:none;color:#2b0a0a}

.small-muted{color:var(--muted);font-size:13px}

/* responsive tweaks */
@media (max-width:900px){
  .main-content-wrapper{margin-left:0;padding:16px}
  .header-row{flex-direction:column;align-items:flex-start;gap:8px}
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

    <div class="header-row">
        <div>
            <h2>Leave Management</h2>
            <p class="small-muted">Approve or reject employee leave requests</p>
        </div>

        <div>
            <a href="staff.php" class="btn btn-sm" style="background:var(--gradient);color:#fff;">Add Staff</a>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success mt-3"><?= h($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger mt-3"><?= h($error) ?></div>
    <?php endif; ?>

    <div class="summary-row">
        <div class="card-box">
            <h3><?= intval($counts['total'] ?? 0) ?></h3>
            <p class="small-muted">Total Requests</p>
        </div>
        <div class="card-box">
            <h3><?= intval($counts['pending'] ?? 0) ?></h3>
            <p class="small-muted">Pending</p>
        </div>
        <div class="card-box">
            <h3><?= intval($counts['approved'] ?? 0) ?></h3>
            <p class="small-muted">Approved</p>
        </div>
        <div class="card-box">
            <h3><?= intval($counts['rejected'] ?? 0) ?></h3>
            <p class="small-muted">Rejected</p>
        </div>
    </div>

    <form class="filters" method="get" action="leavemanagement.php" style="margin-top:18px;">
        <div class="input-group" style="max-width:320px;">
            <select name="status" class="form-select">
                <option value="All" <?= $statusFilter === 'All' ? 'selected' : '' ?>>All Status</option>
                <option value="Pending" <?= $statusFilter === 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Approved" <?= $statusFilter === 'Approved' ? 'selected' : '' ?>>Approved</option>
                <option value="Rejected" <?= $statusFilter === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
            </select>
            <button class="btn btn-outline-light" type="submit">Apply</button>
        </div>

        <div style="flex:1;display:flex;gap:8px;">
            <input type="search" name="search" value="<?= h($search) ?>" class="form-control" placeholder="Search by name or email">
            <button class="btn btn-outline-light" type="submit">Search</button>
            <a href="leavemanagement.php" class="btn btn-light">Reset</a>
        </div>
    </form>

    <div class="leave-list">
        <?php if (empty($leaves)): ?>
            <div class="card-box">No leave requests found.</div>
        <?php endif; ?>

        <?php foreach ($leaves as $lv): 
            $status = $lv['status'] ?? 'Pending';
            $status_cls = strtolower($status) === 'pending' ? 'pend' : (strtolower($status) === 'approved' ? 'appr' : 'rej');
            $display_range = h($lv['start_date']) . ' → ' . h($lv['end_date']);
            $created = !empty($lv['created_at']) ? date('d M Y', strtotime($lv['created_at'])) : '';
        ?>
            <div class="leave-item">
                <div class="leave-left">
                    <div class="avatar"><?= strtoupper(substr($lv['employee_name'],0,1)) ?></div>
                    <div class="leave-meta">
                        <h5><?= h($lv['employee_name']) ?></h5>
                        <p class="small-muted"><?= h($lv['employee_email']) ?> • <?= h($lv['leave_type']) ?></p>
                        <div class="small-muted"><?= $display_range ?> • Requested: <?= $created ?></div>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:12px;">
                    <div>
                        <span class="badge-status <?= $status_cls ?>"><?= h($status) ?></span>
                    </div>

                    <div style="display:flex;gap:8px;align-items:center;">
                        <button class="btn btn-sm btn-outline-light" 
                            data-bs-toggle="modal" 
                            data-bs-target="#viewModal"
                            data-id="<?= (int)$lv['id'] ?>"
                            data-name="<?= h($lv['employee_name']) ?>"
                            data-email="<?= h($lv['employee_email']) ?>"
                            data-type="<?= h($lv['leave_type']) ?>"
                            data-range="<?= h($display_range) ?>"
                            data-reason="<?= h($lv['reason']) ?>"
                            data-manager="<?= h($lv['manager_email']) ?>"
                            data-status="<?= h($status) ?>"
                        ><i class="bi bi-eye"></i> View</button>

                        <?php if ($status === 'Pending'): ?>
                            <form method="post" style="display:inline-block;margin-bottom:0;">
                                <input type="hidden" name="id" value="<?= (int)$lv['id'] ?>">
                                <input type="hidden" name="action" value="approve">
                                <button class="btn btn-sm btn-approve" onclick="return confirm('Approve this leave?')">Approve</button>
                            </form>

                            <form method="post" style="display:inline-block;margin-bottom:0;">
                                <input type="hidden" name="id" value="<?= (int)$lv['id'] ?>">
                                <input type="hidden" name="action" value="reject">
                                <button class="btn btn-sm btn-reject" onclick="return confirm('Reject this leave?')">Reject</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content" style="background:#14132a;border:1px solid var(--border);color:#fff">
      <div class="modal-header">
        <h5 class="modal-title">Leave Request</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <dl class="row">
          <dt class="col-sm-4">Employee</dt><dd class="col-sm-8" id="m-name"></dd>
          <dt class="col-sm-4">Email</dt><dd class="col-sm-8" id="m-email"></dd>
          <dt class="col-sm-4">Type</dt><dd class="col-sm-8" id="m-type"></dd>
          <dt class="col-sm-4">Range</dt><dd class="col-sm-8" id="m-range"></dd>
          <dt class="col-sm-4">Manager</dt><dd class="col-sm-8" id="m-manager"></dd>
          <dt class="col-sm-4">Status</dt><dd class="col-sm-8"id="m-status"></dd>
          <dt class="col-sm-4">Reason</dt><dd class="col-sm-8" id="m-reason"></dd>
        </dl>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const viewModal = document.getElementById('viewModal');
viewModal.addEventListener('show.bs.modal', function (event) {
  const btn = event.relatedTarget;
  const data = {
    id: btn.getAttribute('data-id'),
    name: btn.getAttribute('data-name'),
    email: btn.getAttribute('data-email'),
    type: btn.getAttribute('data-type'),
    range: btn.getAttribute('data-range'),
    reason: btn.getAttribute('data-reason'),
    manager: btn.getAttribute('data-manager'),
    status: btn.getAttribute('data-status')
  };
  document.getElementById('m-name').textContent = data.name;
  document.getElementById('m-email').textContent = data.email;
  document.getElementById('m-type').textContent = data.type;
  document.getElementById('m-range').textContent = data.range;
  document.getElementById('m-reason').textContent = data.reason || '—';
  document.getElementById('m-manager').textContent = data.manager || '—';
  document.getElementById('m-status').textContent = data.status || 'Pending';
});
</script>

</body>
</html>