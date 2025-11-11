<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../Services/MailService.php';
require_once __DIR__ . '/../Services/Orchestrator.php';

class InterviewController {

  public function schedule(): void {
    start_secure_session();
    if (!isset($_SESSION['user'])) { 
      header('Location: ' . APP_URL . '/login.php'); 
      exit; 
    }
    view('interview_form');
  }

  public function submit(): void {
    start_secure_session();
    if (!isset($_SESSION['user'])) { 
      header('Location: ' . APP_URL . '/login.php'); 
      exit; 
    }

    // Logged-in HR email (the sender)
    $fromEmail = $_SESSION['user'];

    // Form data
    $payload = [
      'candidate' => trim($_POST['candidate'] ?? ''),
      'role'      => trim($_POST['role'] ?? ''),
      'panel'     => trim($_POST['panel'] ?? ''),
      'date'      => trim($_POST['date'] ?? '')
    ];

    // Create and process task
    $taskId = Task::create('interview', $payload, $fromEmail);
    Orchestrator::runInterview($taskId, $payload);

    // Email configuration
    $toEmails = explode(',', $payload['panel']);
    $subject = "Interview Scheduled – " . htmlspecialchars($payload['role']);
    $html = '
      <div style="font-family:Inter,Arial,sans-serif; color:#333;">
        <h2>Interview Scheduled</h2>
        <p>Dear Interview Panel,</p>
        <p>An interview has been scheduled for the following candidate:</p>
        <ul>
          <li><strong>Candidate:</strong> ' . htmlspecialchars($payload['candidate']) . '</li>
          <li><strong>Role:</strong> ' . htmlspecialchars($payload['role']) . '</li>
          <li><strong>Date:</strong> ' . htmlspecialchars($payload['date']) . '</li>
          <li><strong>Scheduled by:</strong> ' . htmlspecialchars($fromEmail) . '</li>
        </ul>
        <p>Please ensure your availability for the meeting.</p>
        <p>Regards,<br>TalentFlow HR Automation</p>
      </div>
    ';

    // Send to each panelist
    foreach ($toEmails as $to) {
      MailService::send(trim($to), $subject, $html, $fromEmail);
    }

    $_SESSION['success'] = 'Interview scheduled successfully and emails sent to panel members.';
    header('Location: ' . APP_URL . '/index.php?task=' . $taskId);
    exit;
  }
}
