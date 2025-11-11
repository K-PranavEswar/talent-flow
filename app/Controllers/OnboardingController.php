<?php
require_once __DIR__ . '/../Services/Orchestrator.php';
require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../Services/MailService.php';
require_once __DIR__ . '/../helpers.php';

class OnboardingController {
  public function form(): void {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    if (!isset($_SESSION['user'])) {
      header('Location: ' . APP_URL . '/login.php');
      exit;
    }
    view('onboarding_form');
  }

  public function submit(): void {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    if (!isset($_SESSION['user'])) {
      header('Location: ' . APP_URL . '/login.php');
      exit;
    }

    $userEmail = $_SESSION['user'];
    $payload = [
      'name'          => trim($_POST['name'] ?? ''),
      'role'          => trim($_POST['role'] ?? ''),
      'start_date'    => trim($_POST['start_date'] ?? ''),
      'manager_email' => trim($_POST['manager_email'] ?? ''),
      'location'      => trim($_POST['location'] ?? ''),
      'email'         => trim($_POST['email'] ?? ''),
      'bundle'        => trim($_POST['bundle'] ?? 'DataAnalyst')
    ];

    $taskId = Task::create('onboarding', $payload, $userEmail);
    Orchestrator::runOnboarding($taskId, $payload);

    $to = $payload['email'];
    if (!empty($to)) {
      $subject = '🌟 Welcome to the Team – Onboarding Details';
      $html = '
      <div style="background:#0e0a2b;padding:20px;font-family:\'Inter\',Arial,sans-serif;color:#f3f1ff;border-radius:12px;max-width:600px;margin:auto;">
        <div style="background:linear-gradient(90deg,#6a11cb,#2575fc);padding:15px 25px;border-radius:10px 10px 0 0;">
          <h2 style="margin:0;color:#fff;">Welcome, ' . htmlspecialchars($payload['name']) . ' 👋</h2>
        </div>
        <div style="padding:20px;background:#1a093e;border-radius:0 0 10px 10px;">
          <p>Your onboarding process has officially begun! Here are your key details:</p>
          <table style="width:100%;margin:15px 0;color:#d6cfff;">
            <tr><td><strong>Role:</strong></td><td>' . htmlspecialchars($payload['role']) . '</td></tr>
            <tr><td><strong>Start Date:</strong></td><td>' . htmlspecialchars($payload['start_date']) . '</td></tr>
            <tr><td><strong>Manager:</strong></td><td>' . htmlspecialchars($payload['manager_email']) . '</td></tr>
            <tr><td><strong>Location:</strong></td><td>' . htmlspecialchars($payload['location']) . '</td></tr>
          </table>
          <p>Our IT and HR teams will prepare everything before your start date. You’ll receive further updates shortly.</p>
          <p style="margin-top:20px;">Warm regards,<br><strong style="color:#b28bff;">TalentFlow HR Team</strong></p>
        </div>
      </div>';

      MailService::send($to, $subject, $html);
    }

    $_SESSION['success'] = 'Onboarding started and email sent successfully!';
    header('Location: ' . APP_URL . '/index.php?task=' . $taskId);
    exit;
  }
}
