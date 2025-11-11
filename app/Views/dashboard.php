<?php
/**
 * Dashboard View – TalentFlow AI HR Automation Suite
 * Author: K. Pranav Eswar (MACSEEDS)
 * Description: Displays user tasks, status summaries, and workflow insights.
 */

// ✅ Session is already started in index.php
// So, DO NOT call session_start() again.

// 🔒 Check user authentication (Safe JS redirect)
if (!isset($_SESSION['user'])) {
    echo "<script>window.location.href='" . APP_URL . "/login.php';</script>";
    exit;
}

// 📦 Load required models
require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../Models/Run.php';
require_once __DIR__ . '/../Models/Artifact.php';
require_once __DIR__ . '/../Models/User.php';

// 🎯 Fetch current user data
$userEmail = $_SESSION['user'];
$user = User::findByEmail($userEmail);
$userName = $user ? htmlspecialchars($user['name']) : 'User';

// 🧩 Fetch user’s tasks
$taskId = $_GET['task'] ?? null;
$tasks = Task::forUser($userEmail);
?>

<div class="container-fluid mt-4">
  <div class="d-flex align-items-center mb-3">
    <h3 class="me-auto fw-bold">Welcome, <?= $userName ?> 👋</h3>
    <a href="<?= APP_URL ?>/onboarding" class="btn btn-primary shadow-sm">+ New Onboarding</a>
  </div>

  <div class="row g-4">
    <!-- 🌌 Left: Task List -->
    <div class="col-lg-4">
      <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold text-gradient">Your Recent Tasks</h5>
          <span class="badge bg-secondary"><?= count($tasks) ?> total</span>
        </div>

        <div class="list-group list-group-flush">
          <?php if (empty($tasks)): ?>
            <div class="list-group-item text-muted text-center py-3">No tasks found.</div>
          <?php else: ?>
            <?php foreach ($tasks as $row): ?>
              <?php $isActive = ($taskId == $row['id']); ?>
              <a href="<?= APP_URL ?>/?task=<?= $row['id'] ?>" 
                 class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                <div class="d-flex w-100 justify-content-between align-items-center">
                  <h6 class="mb-1 fw-semibold">#<?= $row['id'] ?> — <?= ucfirst($row['type']) ?></h6>
                  <span class="badge bg-<?= $row['status']=='done'?'success':($row['status']=='error'?'danger':'secondary') ?>">
                    <?= ucfirst($row['status']) ?>
                  </span>
                </div>
                <small class="text-muted">Created: <?= $row['created_at'] ?></small>
              </a>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- 🌠 Right: Task Details -->
    <div class="col-lg-8">
      <?php if ($taskId && ($t = Task::find($taskId))): ?>
        <?php $payload = $t['payload']; ?>

        <div class="card shadow-sm border-0">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-gradient">
              🧩 Task #<?= $t['id'] ?> — <?= strtoupper($t['type']) ?>
            </h5>
            <span class="badge bg-<?= $t['status']=='done'?'success':($t['status']=='error'?'danger':'secondary') ?>">
              <?= ucfirst($t['status']) ?>
            </span>
          </div>

          <div class="card-body">
            <?php if ($t['type'] === 'interview'): ?>
              <h6 class="text-info fw-bold mb-3">👤 Candidate Details</h6>
              <ul class="list-unstyled ps-2 mb-4">
                <li><strong>Name:</strong> <?= htmlspecialchars($payload['candidate'] ?? '-') ?></li>
                <li><strong>Role:</strong> <?= htmlspecialchars($payload['role'] ?? '-') ?></li>
                <li><strong>Date:</strong> <?= htmlspecialchars($payload['date'] ?? '-') ?></li>
                <li><strong>Panel:</strong> <?= htmlspecialchars($payload['panel'] ?? '-') ?></li>
              </ul>

              <h6 class="text-info fw-bold mb-2">📋 Status Summary</h6>
              <ul class="list-unstyled ps-2 mb-0">
                <li>✅ Interview scheduled successfully</li>
                <li>📅 Calendar invite sent to panel</li>
                <li>📧 Candidate notified</li>
                <li>🕓 Awaiting feedback</li>
              </ul>

            <?php elseif ($t['type'] === 'onboarding'): ?>
              <h6 class="text-info fw-bold mb-3">👨‍💼 New Hire Details</h6>
              <ul class="list-unstyled ps-2 mb-4">
                <li><strong>Name:</strong> <?= htmlspecialchars($payload['name'] ?? '-') ?></li>
                <li><strong>Role:</strong> <?= htmlspecialchars($payload['role'] ?? '-') ?></li>
                <li><strong>Start Date:</strong> <?= htmlspecialchars($payload['start_date'] ?? '-') ?></li>
                <li><strong>Manager:</strong> <?= htmlspecialchars($payload['manager_email'] ?? '-') ?></li>
                <li><strong>Location:</strong> <?= htmlspecialchars($payload['location'] ?? '-') ?></li>
              </ul>

              <h6 class="text-info fw-bold mb-2">🚀 Onboarding Progress</h6>
              <ul class="list-unstyled ps-2 mb-0">
                <li>✅ Employee record created in HRIS</li>
                <li>💻 IT access & hardware provisioned</li>
                <li>📅 Orientation scheduled</li>
                <li>📨 Welcome email sent to manager</li>
              </ul>

            <?php elseif ($t['type'] === 'offer'): ?>
              <h6 class="text-info fw-bold mb-3">🎉 Offer Details</h6>
              <ul class="list-unstyled ps-2 mb-4">
                <li><strong>Candidate:</strong> <?= htmlspecialchars($payload['candidate'] ?? '-') ?></li>
                <li><strong>Role:</strong> <?= htmlspecialchars($payload['role'] ?? '-') ?></li>
               <?php
$ctcValue = $payload['ctc'] ?? 0;
$ctcValue = preg_replace('/[^\d.]/', '', $ctcValue); // remove commas or symbols
?>
<li><strong>CTC:</strong> ₹<?= number_format((float)$ctcValue) ?></li>

                <li><strong>Start Date:</strong> <?= htmlspecialchars($payload['start_date'] ?? '-') ?></li>
              </ul>

              <h6 class="text-info fw-bold mb-2">📢 Offer Summary</h6>
              <ul class="list-unstyled ps-2 mb-0">
                <li>📄 Offer letter generated</li>
                <li>📧 Sent to candidate</li>
                <li>🕓 Awaiting acceptance</li>
              </ul>

            <?php else: ?>
              <p class="text-muted">No detailed information available for this task type.</p>
            <?php endif; ?>
          </div>
        </div>

      <?php else: ?>
        <div class="card text-center shadow-sm border-0 p-5">
          <div class="card-body">
            <h5 class="text-muted mb-2">No Task Selected</h5>
            <p class="text-muted">Select a task from the left to view its details here.</p>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<style>
/* 🌌 Gradient Text Style for Headings */
.text-gradient {
  background: linear-gradient(90deg, #ff66ff, #a46eff, #6a11cb);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  letter-spacing: 0.3px;
}
</style>
