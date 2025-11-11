<?php
session_start();
define('APP_URL', 'http://localhost/talentflow/public');

if (!isset($_SESSION['admin'])) {
  header('Location: admin_login.php');
  exit;
}

require_once __DIR__ . '/../app/Models/Task.php';
require_once __DIR__ . '/../app/Models/Leave.php';

$tasks = Task::all();
$leaves = Leave::all();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TalentFlow | Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

  <style>
    :root {
      --primary: #6a11cb;
      --secondary: #2575fc;
      --bg-dark: #0b0521;
      --card-bg: rgba(255,255,255,0.05);
      --text-light: #e8e6f0;
      --accent: #ff6ec7;
    }

    /* --- 🔽 SIDEBAR STYLES 🔽 --- */
    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      height: 100vh;
      width: 80px; /* Collapsed width */
      background: var(--card-bg);
      border-right: 1px solid rgba(255,255,255,0.08);
      backdrop-filter: blur(14px);
      z-index: 1000;
      transition: width 0.3s ease;
      overflow-x: hidden; /* Hide text when collapsed */
    }
    
    .sidebar:hover {
      width: 250px; /* Expanded width */
    }
    
    .sidebar a {
      color: var(--text-light);
      text-decoration: none;
      display: flex;
      align-items: center;
      padding: 1rem 1.5rem;
      white-space: nowrap; /* Prevent text wrapping */
      transition: background 0.2s ease;
    }
    .sidebar a:hover {
      background: rgba(255,255,255,0.1);
    }
    
    .sidebar-brand {
      font-weight: 700;
      font-size: 1.3rem;
      padding: 1.25rem 1.25rem; /* Aligns with nav links */
      height: 65px; /* Give it a fixed height to align */
    }
    
    .sidebar-brand i {
      font-size: 1.8rem;
      min-width: 28px; /* Align with nav-links icons */
      margin-right: 1.5rem; /* Match nav-links icons */
    }
    
    .nav-links {
      list-style: none;
      padding: 0;
      margin: 0;
      padding-top: 1rem;
      /* ✨ CHANGED: Make flex column to push logout to bottom */
      display: flex;
      flex-direction: column;
      height: calc(100% - 65px); /* Full height minus brand */
    }
    
    .nav-links i {
      font-size: 1.5rem;
      min-width: 28px; /* Fixed width for alignment */
      margin-right: 1.5rem; /* Spacing between icon and text */
    }
    
    /* ✨ NEW: Pushes logout link to the bottom */
    .nav-links .logout-link {
        margin-top: auto;
    }
    
    .sidebar-text {
      opacity: 0; /* Hidden by default */
      transition: opacity 0.2s ease;
    }
    
    .sidebar:hover .sidebar-text {
      opacity: 1; /* Show on hover */
      transition-delay: 0.1s; /* Slight delay so it appears *after* expand */
    }
    
    /* --- WRAPPER FOR PUSH EFFECT --- */
    .main-content-wrapper {
      margin-left: 80px; /* Match collapsed sidebar width */
      transition: margin-left 0.3s ease;
      /* Fixes weird radial-gradient bug during transition */
      background: radial-gradient(circle at 20% 20%, #1a064e, #090314);
      min-height: 100vh;
    }
    
    /* When sidebar is hovered, push the content */
    .sidebar:hover ~ .main-content-wrapper {
      margin-left: 250px; /* Match expanded sidebar width */
    }
    /* --- 🔼 END OF SIDEBAR STYLES 🔼 --- */


    body {
      font-family: 'Inter', sans-serif;
      /* Background moved to the wrapper to fix transition bugs */
      background: #090314; 
      color: var(--text-light);
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* Navbar was removed, but if you add it back, these styles are here */
    .navbar {
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      padding: 1rem 2rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.3);
      border-radius: 0; 
    }

    .navbar-brand {
      font-weight: 700;
      letter-spacing: 0.5px;
      color: #fff !important;
      font-size: 1.3rem;
    }

    .logout-btn {
      background: #e63946;
      color: #fff;
      border-radius: 8px;
      font-weight: 500;
      transition: 0.3s;
    }

    .logout-btn:hover {
      background: #c1121f;
      color: #fff;
    }

    .dashboard-header {
      padding: 2rem 3rem 1rem;
    }

    .dashboard-header h2 {
      font-weight: 700;
      font-size: 1.8rem;
      background: linear-gradient(90deg, #b621fe, #1fd1f9);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .card-glass {
      background: var(--card-bg);
      border: 1px solid rgba(255,255,255,0.08);
      backdrop-filter: blur(14px);
      border-radius: 20px;
      padding: 1.5rem;
      box-shadow: 0 0 25px rgba(100,100,255,0.15);
      transition: all 0.3s ease;
    }

    .card-glass:hover {
      transform: translateY(-4px);
      box-shadow: 0 0 35px rgba(120, 80, 255, 0.3);
    }

    .table {
      color: #fff;
      font-size: 0.9rem;
    }

    .table th {
      color: var(--accent);
      font-weight: 600;
      border-bottom: 1px solid rgba(255,255,255,0.2);
    }

    .badge {
      border-radius: 12px;
      padding: 0.4rem 0.7rem;
    }

    .stats {
      display: flex;
      gap: 1.5rem;
      flex-wrap: wrap;
      margin-bottom: 1.5rem;
    }

    .stat-box {
      flex: 1;
      min-width: 200px;
      background: linear-gradient(135deg, #1a084f, #3b0ca3);
      border-radius: 16px;
      text-align: center;
      padding: 1.2rem;
      color: #fff;
      box-shadow: 0 0 25px rgba(80,0,255,0.25);
    }

    .stat-box h3 {
      font-size: 2rem;
      margin-bottom: 0.3rem;
    }

    .action-btns .btn {
      padding: 0.3rem 0.5rem;
      border-radius: 8px;
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

<div class="sidebar">
  <a href="#" class="sidebar-brand">
    <i class="bi bi-shield-lock-fill"></i>
    <span class="sidebar-text">TalentFlow</span>
  </a>

  <ul class="nav-links">
    <li>
      <a href="admin_profile.php">
        <i class="bi bi-person-circle"></i>
        <span class="sidebar-text">Profile</span>
      </a>
    </li>
    <li>
      <a href="analytics.php">
        <i class="bi bi-graph-up"></i>
        <span class="sidebar-text">Analytics</span>
      </a>
    </li>
    <li class="logout-link">
      <a href="admin_logout.php">
        <i class="bi bi-box-arrow-left"></i>
        <span class="sidebar-text">Logout</span>
      </a>
    </li>
  </ul>
</div>

<div class="main-content-wrapper">

  <section class="dashboard-header">
    <h2>Welcome, Admin 👋</h2>
  </section>

  <div class="container-fluid px-4 mb-4">
    <div class="row">
      <div class="col-12">
        <div class="card-glass">
          <h5 class="mb-3"><i class="bi bi-graph-up-arrow"></i> Live Activity Feed (Real-Time)</h5>
          <div style="height: 280px;"> 
            <canvas id="realTimeChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid px-4">
    <div class="stats">
      <div class="stat-box"><h3><?= count($tasks) ?></h3><p>Total HR Tasks</p></div>
      <div class="stat-box"><h3><?= count($leaves) ?></h3><p>Leave Requests</p></div>
      <div class="stat-box"><h3><?= count(array_filter($tasks, fn($t) => $t['status']=='done')) ?></h3><p>Completed Tasks</p></div>
      <div class="stat-box"><h3><?= count(array_filter($tasks, fn($t) => $t['status']=='pending')) ?></h3><p>Pending Tasks</p></div>
    </div>
      <div class="col-lg-4">
        <div class="card-glass">
          <h5 class="mb-3"><i class="bi bi-calendar-check"></i> Leave Requests</h5>
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr><th>ID</th><th>Name</th><th>Type</th><th>Status</th><th>Action</th></tr>
              </thead>
              <tbody>
                <?php foreach ($leaves as $leave): ?>
                <tr>
                  <td><?= $leave['id'] ?></td>
                  <td><?= htmlspecialchars($leave['employee_name']) ?></td>
                  <td><?= htmlspecialchars($leave['leave_type']) ?></td>
                  <td>
                    <?php if ($leave['status'] == 'Approved'): ?>
                      <span class="badge bg-success">Approved</span>
                    <?php elseif ($leave['status'] == 'Rejected'): ?>
                      <span class="badge bg-danger">Rejected</span>
                    <?php else: ?>
                      <span class="badge bg-warning text-dark">Pending</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($leave['status'] == 'Pending'): ?>
                      <form method="post" action="leave_action.php" class="d-inline">
                        <input type="hidden" name="id" value="<?= $leave['id'] ?>">
                        <button name="action" value="approve" class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i></button>
                      </form>
                      <form method="post" action="leave_action.php" class="d-inline">
                        <input type="hidden" name="id" value="<?= $leave['id'] ?>">
                        <button name="action" value="reject" class="btn btn-danger btn-sm"><i class="bi bi-x-lg"></i></button>
                      </form>
                    <?php else: ?>
                      <i class="text-muted">—</i>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer>
    © 2025 <strong>MACSEEDS</strong> | Hackathon Series — lablab.ai
  </footer>

</div> 
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
  <div id="toastContainer"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function showToast(message, type = 'info') {
    const icons = {
      success: 'bi bi-check-circle-fill text-success',
      error: 'bi bi-x-circle-fill text-danger',
      warning: 'bi bi-exclamation-triangle-fill text-warning',
      info: 'bi bi-info-circle-fill text-info'
    };

    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white border-0 show';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
      <div class="d-flex">
        <div class="toast-body d-flex align-items-center">
          <i class="${icons[type]} me-2"></i> ${message}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    `;
    toast.style.background = 'rgba(30, 30, 40, 0.9)';
    toast.style.backdropFilter = 'blur(8px)';
    toast.style.borderRadius = '10px';
    toast.style.minWidth = '260px';
    toast.style.marginBottom = '10px';
    document.getElementById('toastContainer').appendChild(toast);

    setTimeout(() => toast.remove(), 4000);
  }

  // ----------------------------------------------------
  // ✨ Real-time Chart JavaScript
  // ----------------------------------------------------

  const ctx = document.getElementById('realTimeChart');
  if (ctx) {
    const chartCtx = ctx.getContext('2d');

    const tasksGradient = chartCtx.createLinearGradient(0, 0, 0, 280);
    tasksGradient.addColorStop(0, 'rgba(40, 199, 111, 0.6)'); // Greenish
    tasksGradient.addColorStop(1, 'rgba(40, 199, 111, 0)');

    const leavesGradient = chartCtx.createLinearGradient(0, 0, 0, 280);
    leavesGradient.addColorStop(0, 'rgba(255, 107, 107, 0.6)'); // Reddish
    leavesGradient.addColorStop(1, 'rgba(255, 107, 107, 0)');

    const liveChart = new Chart(chartCtx, {
      type: 'line',
      data: {
        labels: [], // Timestamps
        datasets: [
          {
            label: 'Tasks Completed',
            data: [], // New completed tasks
            borderColor: '#28c76f', // Green
            backgroundColor: tasksGradient,
            fill: true,
            tension: 0.4, // Makes the line curved
            pointRadius: 0,
          },
          {
            label: 'Leave Requests',
            data: [], // New leave requests
            borderColor: '#ff6b6b', // Red
            backgroundColor: leavesGradient,
            fill: true,
            tension: 0.4,
            pointRadius: 0,
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 400, // Faster animation
          easing: 'linear'
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 10, // Max 10 new items at a time
            ticks: {
              stepSize: 1,
              color: 'rgba(255,255,255,0.5)' // Y-axis labels
            },
            grid: {
              color: 'rgba(255,255,255,0.1)' // Y-axis grid lines
            }
          },
          x: {
            ticks: {
              color: 'rgba(255,255,255,0.5)' // X-axis labels
            },
            grid: {
              display: false // Hide vertical grid lines
            }
          }
        },
        plugins: {
          legend: {
            position: 'top',
            align: 'end',
            labels: {
              color: 'rgba(255,255,255,0.8)',
              boxWidth: 12,
              padding: 20
            }
          },
          tooltip: {
            backgroundColor: 'rgba(11, 5, 33, 0.9)', // Dark tooltip
            titleColor: '#fff',
            bodyColor: '#fff',
            borderColor: 'rgba(255,255,255,0.2)',
            borderWidth: 1
          }
        },
        interaction: {
          intersect: false, // Show tooltip on hover anywhere
          mode: 'index',
        }
      }
    });

    // Function to fetch new data and update the chart
    async function updateChartData() {
      try {
        const response = await fetch('api_live_stats.php'); 
        const data = await response.json();
        liveChart.data.labels.push(data.timestamp);
        liveChart.data.datasets[0].data.push(data.completedTasks); // Tasks
        liveChart.data.datasets[1].data.push(data.leaveRequests); // Leaves

        const maxDataPoints = 15;
        if (liveChart.data.labels.length > maxDataPoints) {
          liveChart.data.labels.shift(); 
          liveChart.data.datasets.forEach(dataset => {
            dataset.data.shift(); 
          });
        }
        liveChart.update('none'); 

      } catch (error) {
        console.error('Error fetching chart data:', error);
      }
    }

    setInterval(updateChartData, 1000); 
    updateChartData(); 
  }
  
  // ✅ Check for success messages (passed via PHP session)
  <?php if (isset($_SESSION['toast'])): ?>
    showToast("<?= $_SESSION['toast']['msg'] ?>", "<?= $_SESSION['toast']['type'] ?>");
    <?php unset($_SESSION['toast']); ?>
  <?php endif; ?>
</script>
</body>
</html>