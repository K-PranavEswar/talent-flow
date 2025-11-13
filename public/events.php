<?php
session_start();
require_once __DIR__ . '/../app/Models/DB.php';

$pdo = DB::pdo();
$success = "";
$error = "";

/* ============================
   ADD EVENT
=============================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    $title = trim($_POST['title']);
    $date = $_POST['event_date'];
    $desc = trim($_POST['description']);

    if ($title !== "" && $date !== "") {
        $stmt = $pdo->prepare("INSERT INTO events (title, event_date, description) VALUES (?, ?, ?)");
        if ($stmt->execute([$title, $date, $desc])) {
            $success = "Event added successfully!";
        } else {
            $error = "Failed to add event.";
        }
    }
}

/* ============================
   DELETE EVENT
=============================== */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = "Event deleted successfully!";
    } else {
        $error = "Failed to delete event.";
    }
}

/* ============================
   FETCH EVENTS
=============================== */
$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

$calendar_events = [];
foreach ($events as $e) {
    $calendar_events[] = [
        'id' => $e['id'],
        'title' => $e['title'],
        'start' => $e['event_date'],
        'description' => $e['description']
    ];
}

function isActive($page) {
    return basename($_SERVER['PHP_SELF']) === $page ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TalentFlow | Events</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* -------------------------
   GLOBAL & COLORS
-------------------------- */
:root {
    --sidebar-width: 260px;
    --bg-dark: #101014;
    --card-bg: #1b1b1f;
    --border-color: rgba(255,255,255,0.08);
    --text-muted: #aaa;
    --primary-gradient: linear-gradient(90deg,#b621fe,#1fd1f9);
}

body {
    font-family: 'Inter', sans-serif;
    background: var(--bg-dark);
    color: #fff;
    margin: 0;
}
/* -------------------------
   NEW TALENTFLOW SIDEBAR (MATCHES IMAGE)
-------------------------- */

.sidebar {
    width: 250px;
    height: 100vh;
    background: #0d0c28; /* dark navy like screenshot */
    padding: 25px 15px;
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
    gap: 10px;
    font-size: 20px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 35px;
    padding-left: 10px;
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-menu li {
    margin-bottom: 18px;
}

.sidebar-item {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 18px;
    border-radius: 12px;
    text-decoration: none;
    color: #d0cfff;
    font-weight: 500;
    font-size: 15px;
    transition: 0.25s ease;
}

.sidebar-item i {
    font-size: 20px;
}

/* Hover effect */
.sidebar-item:hover {
    background: rgba(255,255,255,0.07);
    color: #ffffff;
}

/* Active item — EXACT gradient like screenshot */
.sidebar-item.active {
    background: linear-gradient(135deg, #6b4dff, #3e8bff);
    color: white !important;
}

/* Keep Logout at bottom */
.logout-section {
    margin-top: auto;
    padding-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.07);
    position: absolute;
    bottom: 20px;
    width: 200px;
}
/* -------------------------
   MAIN CONTENT
-------------------------- */
.main-content-wrapper {
    margin-left: var(--sidebar-width);
    padding: 30px;
}

.main-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-glass-calendar {
    background: rgba(255,255,255,0.03);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    backdrop-filter: blur(10px);
}

.btn-gradient {
    background: var(--primary-gradient);
    border: none;
    font-weight: 500;
    color: #fff;
    box-shadow: 0 4px 15px rgba(182,33,254,0.3);
}

.modal-content {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
}

.fc {
    color: #fff;
}

.fc .fc-daygrid-day-number {
    color: #ccc;
}

.fc .fc-day-today {
    background: rgba(182,33,254,0.18) !important;
}

</style>

</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="sidebar-logo">
        <i class="bi bi-shield-shaded"></i>
        <span>TalentFlow</span>
    </div>

    <ul class="sidebar-menu">

        <li><a href="admin_dashboard.php" class="sidebar-item <?= isActive('admin_dashboard.php') ?>"><i class="bi bi-grid-fill"></i>Dashboard</a></li>

        <li><a href="staff.php" class="sidebar-item <?= isActive('staff.php') ?>"><i class="bi bi-people-fill"></i>Staffs</a></li>

        <li><a href="taskboard.php" class="sidebar-item <?= isActive('taskboard.php') ?>"><i class="bi bi-clipboard2-check-fill"></i>TaskBoard</a></li>

        <li><a href="leavemanagement.php" class="sidebar-item <?= isActive('leavemanagement.php') ?>"><i class="bi bi-calendar2-x-fill"></i>Leave Manager</a></li>

        <li><a href="events.php" class="sidebar-item <?= isActive('events.php') ?>"><i class="bi bi-calendar-event-fill"></i>Events</a></li>

        <li><a href="performance.php" class="sidebar-item <?= isActive('performance.php') ?>"><i class="bi bi-graph-up"></i>Performance</a></li>

        <li class="logout-section">
            <a href="admin_logout.php" class="sidebar-item">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </li>

    </ul>

</div>


<!-- MAIN CONTENT -->
<div class="main-content-wrapper">

<header class="main-header">
    <div>
        <h2 style="background:var(--primary-gradient); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">Events Management</h2>
        <p style="color:var(--text-muted)">Manage all HR events and schedules</p>
    </div>

    <button class="btn btn-gradient btn-lg" data-bs-toggle="modal" data-bs-target="#addEventModal">
        <i class="bi bi-plus-circle"></i> Add New Event
    </button>
</header>

<?php if ($success): ?>
<div class="alert alert-success mt-3"><?= $success ?></div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger mt-3"><?= $error ?></div>
<?php endif; ?>

<div class="card-glass-calendar mt-4">
    <div id="calendar"></div>
</div>

</div>

<!-- ADD EVENT MODAL -->
<div class="modal fade" id="addEventModal">
    <div class="modal-dialog modal-lg">
        <form method="POST">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-calendar-plus"></i> Add New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Event Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Event Date</label>
                            <input type="date" name="event_date" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control">
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-gradient" name="add_event"><i class="bi bi-plus-circle"></i> Add Event</button>
                </div>

            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
const eventData = <?= json_encode($calendar_events) ?>;

document.addEventListener("DOMContentLoaded", () => {

    const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        themeSystem: "bootstrap5",
        initialView: "dayGridMonth",

        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,listWeek"
        },

        events: eventData,

        eventClick(info) {
            if (confirm(`Delete event?\n\n${info.event.title}`)) {
                window.location.href = `events.php?delete=${info.event.id}`;
            }
        },

        eventDidMount(info) {
            if (info.event.extendedProps.description) {
                new bootstrap.Tooltip(info.el, {
                    title: info.event.extendedProps.description,
                    placement: "top"
                });
            }
        }
    });

    calendar.render();
});
</script>

</body>
</html>
