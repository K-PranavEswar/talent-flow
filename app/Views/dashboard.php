<?php
if (!isset($_SESSION)) session_start();

if (!defined('APP_URL')) {
    define('APP_URL', 'http://localhost/talentflow/public');
}

if (!isset($_SESSION['user'])) {
    echo "<script>window.location.href='" . APP_URL . "/login.php';</script>";
    exit;
}

require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../Models/User.php';
$userEmail = $_SESSION['user'];
$user = User::findByEmail($userEmail);
$userName = $user ? htmlspecialchars($user['name']) : 'User';
$tasks = Task::forUser($userEmail);
$activeTaskId = $_GET['task'] ?? (empty($tasks) ? null : array_reverse($tasks)[0]['id']);
$pendingTasks = count(array_filter($tasks, fn($t) => $t['status'] == 'pending'));
$completedTasks = count(array_filter($tasks, fn($t) => $t['status'] == 'done'));
$totalTasks = count($tasks);
function getTaskIcon($type) {
    switch ($type) {
        case 'onboarding': return 'bi-rocket-takeoff-fill';
        case 'interview': return 'bi-mic-fill';
        case 'offer': return 'bi-file-earmark-text-fill';
        default: return 'bi-question-circle-fill';
    }
}
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<style>
:root{--card-bg-glass:rgba(20,15,40,0.6);--card-border:rgba(255,255,255,0.1);--primary-glow:rgba(114,9,183,0.4);--accent-glow:rgba(255,110,199,0.5)}.text-gradient{background:linear-gradient(90deg,#b621fe,#1fd1f9);-webkit-background-clip:text;-webkit-text-fill-color:transparent}.text-accent{color:var(--accent,#ff6ec7)}.card-glass{background:var(--card-bg-glass);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid var(--card-border);border-radius:1rem;box-shadow:0 4px 20px rgba(0,0,0,0.2)}.stat-card{padding:1.25rem;display:flex;align-items:center;gap:1rem}.stat-card .stat-icon{font-size:2.25rem;flex-shrink:0}.stat-card .stat-number{font-size:2.25rem;font-weight:700;color:#fff;line-height:1}.stat-card .stat-label{font-size:0.9rem;color:var(--bs-secondary-text-emphasis);text-transform:uppercase;letter-spacing:0.5px;line-height:1.2}.btn-grad{background:linear-gradient(90deg,var(--primary,#7209b7),var(--secondary,#560bad));border:none;color:#fff;box-shadow:0 4px 15px var(--primary-glow);transition:all 0.3s ease}.btn-grad:hover{color:#fff;transform:translateY(-2px);box-shadow:0 6px 20px var(--primary-glow)}.search-input-glass .form-control,.search-input-glass .input-group-text{background:rgba(0,0,0,0.15)!important;border-color:var(--card-border)!important;color:var(--bs-body-color)!important}.search-input-glass .form-control::placeholder{color:var(--bs-secondary-text-emphasis);opacity:0.7}.search-input-glass .form-control:focus{background:rgba(0,0,0,0.2)!important;border-color:var(--accent,#ff6ec7)!important;box-shadow:0 0 10px var(--accent-glow)!important;color:var(--bs-body-color)!important}.nav-pills-glass{height:100%;max-height:calc(70vh - 70px);overflow-y:auto;padding:0.5rem}.nav-pills-glass .nav-link{display:flex;align-items:center;padding:1rem;border-radius:0.75rem;color:var(--bs-body-color);margin-bottom:0.5rem;transition:background 0.3s ease,box-shadow 0.3s ease;border:1px solid transparent;background:rgba(0,0,0,0.1)}.nav-pills-glass .nav-link:hover{background:rgba(255,255,255,0.08)}.nav-pills-glass .nav-link.active{background:rgba(255,255,255,0.15);border-color:var(--accent,#ff6ec7);box-shadow:0 0 15px var(--accent-glow);color:var(--bs-body-color)}.task-tab-icon{font-size:1.5rem;width:40px;height:40px;line-height:40px;text-align:center;border-radius:50%;background:rgba(255,255,255,0.1);color:var(--accent,#ff6ec7);margin-right:1rem}.task-tab-badge{margin-left:auto}.list-group-clean{list-style:none;padding-left:0}.list-group-clean li{display:flex;justify-content:space-between;padding:0.75rem 0.25rem;border-bottom:1px solid var(--card-border)}.list-group-clean li:last-child{border-bottom:none}.list-group-clean li strong{color:var(--bs-secondary-text-emphasis);padding-right:1rem}.timeline{list-style:none;padding:0;position:relative}.timeline::before{content:'';position:absolute;left:12px;top:5px;bottom:5px;width:2px;background:var(--card-border)}.timeline-item{padding-left:3rem;position:relative;margin-bottom:1rem}.timeline-item::before{content:'\F26A';font-family:'bootstrap-icons';position:absolute;left:0;top:0;font-size:1.5rem;width:26px;height:26px;text-align:center;color:var(--bs-success)}.timeline-item.pending::before{content:'\F635';color:var(--bs-warning);animation:spin 2s linear infinite}@keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}body[data-bs-theme="light"]{--card-bg-glass:rgba(255,255,255,0.7);--card-border:rgba(0,0,0,0.1)}body[data-bs-theme="light"] .stat-card .stat-number{color:#1a202c}body[data-bs-theme="light"] .search-input-glass .form-control,.search-input-glass .input-group-text{background:rgba(0,0,0,0.03)!important}body[data-bs-theme="light"] .search-input-glass .form-control:focus{background:rgba(0,0,0,0.05)!important}body[data-bs-theme="light"] .nav-pills-glass .nav-link{background:rgba(0,0,0,0.03);color:var(--bs-body-color)}body[data-bs-theme="light"] .nav-pills-glass .nav-link:hover{background:rgba(0,0,0,0.05)}body[data-bs-theme="light"] .nav-pills-glass .nav-link.active{background:rgba(0,0,0,0.08);color:var(--bs-body-color)}body[data-bs-theme="light"] .task-tab-icon{background:rgba(0,0,0,0.04)}body[data-bs-theme="light"] .timeline::before{background:var(--card-border)}.header-profile-topright{display:flex;align-items:center;gap:12px}.header-profile-topright a{display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:50%;overflow:hidden;border:2px solid rgba(255,255,255,0.12);box-shadow:0 6px 18px rgba(0,0,0,0.35);background:#ffffff10;text-decoration:none}.header-profile-topright img{width:100%;height:100%;object-fit:cover;border-radius:50%;image-rendering:crisp-edges;-webkit-image-rendering:optimize-contrast}
</style>

<div class="container-fluid mt-4">
    <div class="d-flex align-items-center mb-4">
        <h3 class="me-auto fw-bold text-gradient">Welcome, <?= $userName ?> 👋</h3>
        <?php
        $profilePhoto = $user && !empty($user['photo']) ? 'assets/images/' . htmlspecialchars($user['photo']) : 'assets/images/default.png';
        ?>
        <div class="header-profile-topright">
            <a href="<?= APP_URL ?>/profile.php" title="View profile">
                <img src="<?= APP_URL . '/' . $profilePhoto ?>" alt="Profile">
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <a href="<?= APP_URL ?>/onboarding" class="btn btn-grad shadow-sm btn-lg w-100 d-flex align-items-center justify-content-center" style="height:100%;min-height:150px">
                <i class="bi bi-plus-lg me-2" style="font-size:1.5rem"></i> New Onboarding
            </a>
        </div>
        <div class="col-lg-4">
            <div class="card-glass h-100">
                <div class="card-body">
                    <h5 class="mb-3 fw-bold text-gradient">Task Summary</h5>
                    <div style="height:250px">
                        <canvas id="taskSummaryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="vstack g-3">
                <div class="card-glass stat-card">
                    <div class="stat-icon text-warning"><i class="bi bi-hourglass-split"></i></div>
                    <div>
                        <div class="stat-number"><?= $pendingTasks ?></div>
                        <div class="stat-label">Pending Tasks</div>
                    </div>
                </div>
                <div class="card-glass stat-card">
                    <div class="stat-icon text-success"><i class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <div class="stat-number"><?= $completedTasks ?></div>
                        <div class="stat-label">Completed Tasks</div>
                    </div>
                </div>
                <div class="card-glass stat-card">
                    <div class="stat-icon text-info"><i class="bi bi-collection-fill"></i></div>
                    <div>
                        <div class="stat-number"><?= $totalTasks ?></div>
                        <div class="stat-label">Total Tasks</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card-glass p-3">
                <div class="input-group mb-3 search-input-glass">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="taskSearchInput" class="form-control" placeholder="Search tasks by ID, type, date...">
                </div>
                <div class="nav nav-pills nav-pills-glass flex-column" id="task-tab" role="tablist">
                    <?php if (empty($tasks)): ?>
                        <div class="text-muted text-center p-4">No tasks found.</div>
                    <?php else: ?>
                        <?php foreach (array_reverse($tasks) as $task): ?>
                            <?php $isActive = ($activeTaskId == $task['id']); ?>
                            <a class="nav-link <?= $isActive ? 'active' : '' ?>" id="task-tab-<?= $task['id'] ?>" data-bs-toggle="pill" href="#task-content-<?= $task['id'] ?>" role="tab">
                                <div class="task-tab-icon"><i class="<?= getTaskIcon($task['type']) ?>"></i></div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-semibold">#<?= $task['id'] ?> — <?= ucfirst($task['type']) ?></h6>
                                    <small class="text-muted">Created: <?= date('M d, Y', strtotime($task['created_at'])) ?></small>
                                </div>
                                <span class="badge task-tab-badge bg-<?= $task['status']=='done'?'success':($task['status']=='error'?'danger':'warning') ?>"><?= ucfirst($task['status']) ?></span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="text-muted text-center p-4" id="noSearchResults" style="display:none">No tasks match your search.</div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card-glass h-100">
                <div class="tab-content h-100" id="task-tab-content">
                    <?php if (empty($tasks)): ?>
                        <div class="tab-pane fade show active h-100">
                            <div class="h-100 d-flex justify-content-center align-items-center text-center">
                                <div>
                                    <i class="bi bi-stars text-gradient" style="font-size:4rem"></i>
                                    <h5 class="mt-3 mb-2 fw-bold">No Tasks to Display</h5>
                                    <p class="text-muted px-4">Create a new onboarding task to get started.</p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($tasks as $t): ?>
                            <?php $payload = $t['payload']; $isActive = ($activeTaskId == $t['id']); ?>
                            <div class="tab-pane fade <?= $isActive ? 'show active' : '' ?> h-100" id="task-content-<?= $t['id'] ?>" role="tabpanel">
                                <div class="p-4 h-100" style="overflow-y:auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0 fw-bold text-gradient">🧩 Task #<?= $t['id'] ?> — <?= strtoupper($t['type']) ?></h5>
                                        <span class="badge bg-<?= $t['status']=='done'?'success':($t['status']=='error'?'danger':'warning') ?>"><?= ucfirst($t['status']) ?></span>
                                    </div>

                                    <?php if ($t['type'] === 'interview'): ?>
                                        <h6 class="text-accent fw-bold mb-3 mt-4">👤 CANDIDATE DETAILS</h6>
                                        <ul class="list-group-clean mb-4">
                                            <li><strong>Name:</strong> <span><?= htmlspecialchars($payload['candidate'] ?? '-') ?></span></li>
                                            <li><strong>Role:</strong> <span><?= htmlspecialchars($payload['role'] ?? '-') ?></span></li>
                                            <li><strong>Date:</strong> <span><?= htmlspecialchars($payload['date'] ?? '-') ?></span></li>
                                            <li><strong>Panel:</strong> <span><?= htmlspecialchars($payload['panel'] ?? '-') ?></span></li>
                                        </ul>
                                        <h6 class="text-accent fw-bold mb-3">📋 STATUS SUMMARY</h6>
                                        <ul class="timeline">
                                            <li class="timeline-item">Interview scheduled</li>
                                            <li class="timeline-item">Calendar invite sent</li>
                                            <li class="timeline-item">Candidate notified</li>
                                            <li class="timeline-item pending">Awaiting feedback</li>
                                        </ul>

                                    <?php elseif ($t['type'] === 'onboarding'): ?>
                                        <h6 class="text-accent fw-bold mb-3 mt-4">👨‍💼 NEW HIRE DETAILS</h6>
                                        <ul class="list-group-clean mb-4">
                                            <li><strong>Name:</strong> <span><?= htmlspecialchars($payload['name'] ?? '-') ?></span></li>
                                            <li><strong>Role:</strong> <span><?= htmlspecialchars($payload['role'] ?? '-') ?></span></li>
                                            <li><strong>Start Date:</strong> <span><?= htmlspecialchars($payload['start_date'] ?? '-') ?></span></li>
                                            <li><strong>Manager:</strong> <span><?= htmlspecialchars($payload['manager_email'] ?? '-') ?></span></li>
                                        </ul>
                                        <h6 class="text-accent fw-bold mb-3">🚀 ONBOARDING PROGRESS</h6>
                                        <ul class="timeline">
                                            <li class="timeline-item">Employee record created</li>
                                            <li class="timeline-item">IT access provided</li>
                                            <li class="timeline-item">Orientation scheduled</li>
                                            <li class="timeline-item">Welcome email sent</li>
                                        </ul>

                                    <?php elseif ($t['type'] === 'offer'): ?>
                                        <?php $ctcValue = $payload['ctc'] ?? 0; $ctcValue = preg_replace('/[^\d.]/','',$ctcValue); ?>
                                        <h6 class="text-accent fw-bold mb-3 mt-4">🎉 OFFER DETAILS</h6>
                                        <ul class="list-group-clean">
                                            <li><strong>Candidate:</strong> <span><?= htmlspecialchars($payload['candidate'] ?? '-') ?></span></li>
                                            <li><strong>Role:</strong> <span><?= htmlspecialchars($payload['role'] ?? '-') ?></span></li>
                                            <li><strong>CTC:</strong> <span>₹<?= number_format((float)$ctcValue) ?></span></li>
                                            <li><strong>Start Date:</strong> <span><?= htmlspecialchars($payload['start_date'] ?? '-') ?></span></li>
                                        </ul>
                                    <?php else: ?>
                                        <p class="text-muted mt-4">No detailed information available for this task type.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded",function(){if(typeof Chart==='undefined'){console.error('Chart.js is not loaded.');return}const ctx=document.getElementById('taskSummaryChart');if(!ctx)return;const getThemeTextColor=()=>document.body.dataset.bsTheme==='light'?'#333':'#eaeaff';const chartData={labels:['Pending','Completed'],datasets:[{label:'Tasks',data:[<?= $pendingTasks ?>,<?= $completedTasks ?>],backgroundColor:['rgba(255,193,7,0.8)','rgba(25,135,84,0.8)'],borderColor:['rgba(255,193,7,1)','rgba(25,135,84,1)'],borderWidth:1,hoverOffset:8}]};const taskChart=new Chart(ctx,{type:'doughnut',data:chartData,options:{responsive:true,maintainAspectRatio:false,cutout:'70%',plugins:{legend:{position:'bottom',labels:{color:getThemeTextColor(),padding:20,font:{weight:'500'}}},tooltip:{backgroundColor:'rgba(11,5,33,0.9)',titleColor:'var(--accent,#ff6ec7)',bodyColor:'#eaeaff'}}}});const themeObserver=new MutationObserver(function(mutations){mutations.forEach(function(mutation){if(mutation.attributeName==='data-bs-theme'){const newTextColor=getThemeTextColor();taskChart.options.plugins.legend.labels.color=newTextColor;taskChart.update()}})});themeObserver.observe(document.body,{attributes:true});const urlParams=new URLSearchParams(window.location.search);const taskQueryId=urlParams.get('task');if(taskQueryId){const tabToActivate=document.querySelector(`#task-tab-${taskQueryId}`);if(tabToActivate){const tab=new bootstrap.Tab(tabToActivate);tab.show()}}const taskTabs=document.querySelectorAll('a[data-bs-toggle="pill"]');taskTabs.forEach(tab=>{tab.addEventListener('shown.bs.tab',event=>{const newId=event.target.id.split('-').pop();const newUrl=new URL(window.location);newUrl.searchParams.set('task',newId);window.history.replaceState({},'',newUrl)})});const searchInput=document.getElementById('taskSearchInput');const allTaskTabs=document.querySelectorAll('#task-tab .nav-link');const noResultsMessage=document.getElementById('noSearchResults');const originalNoTasksMessage=document.querySelector('#task-tab .text-muted:not(#noSearchResults)');if(searchInput){searchInput.addEventListener('keyup',()=>{const searchTerm=searchInput.value.toLowerCase();let firstVisibleTab=null;let activeTabIsVisible=false;let visibleCount=0;allTaskTabs.forEach(tab=>{const taskText=tab.textContent.toLowerCase();const isVisible=taskText.includes(searchTerm);tab.style.display=isVisible?'flex':'none';if(isVisible){visibleCount++;if(!firstVisibleTab){firstVisibleTab=tab}if(tab.classList.contains('active')){activeTabIsVisible=true}}});if(noResultsMessage){const hasTasks=allTaskTabs.length>0;noResultsMessage.style.display=(visibleCount===0&&hasTasks)?'block':'none'}if(originalNoTasksMessage){originalNoTasksMessage.style.display=(searchTerm.length>0)?'none':'block'}if(!activeTabIsVisible&&firstVisibleTab){const tab=new bootstrap.Tab(firstVisibleTab);tab.show()}})}
});
</script>
