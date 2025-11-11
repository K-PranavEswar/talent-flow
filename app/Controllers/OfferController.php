<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../Services/MailService.php';
require_once __DIR__ . '/../Services/Orchestrator.php';

class OfferController {
  public function form(): void {
    start_secure_session();
    if (!isset($_SESSION['user'])) {
      header('Location: ' . APP_URL . '/login.php');
      exit;
    }
    view('offer_form');
  }

  public function submit(): void {
    start_secure_session();
    if (!isset($_SESSION['user'])) {
      header('Location: ' . APP_URL . '/login.php');
      exit;
    }

    $userEmail = $_SESSION['user'];
    $payload = [
      'candidate'  => trim($_POST['candidate'] ?? ''),
      'email'      => trim($_POST['email'] ?? ''),
      'role'       => trim($_POST['role'] ?? ''),
      'ctc'        => trim($_POST['ctc'] ?? ''),
      'start_date' => trim($_POST['start_date'] ?? '')
    ];

    $taskId = Task::create('offer', $payload, $userEmail);
    Orchestrator::runOffer($taskId, $payload);

    $subject = "🎉 Offer Letter – " . htmlspecialchars($payload['role']);
    $html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Offer Letter – ' . htmlspecialchars($payload['role']) . '</title>
    <style>
      body { font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color:#f8f4ff; margin:0; padding:0; }
      .container { max-width:650px; margin:40px auto; background:#fff; border-radius:14px; box-shadow:0 6px 20px rgba(114,9,183,0.2); overflow:hidden; }
      .header { background:linear-gradient(90deg,#7209b7,#560bad,#3a0ca3); color:#fff; text-align:center; padding:20px; }
      .header h1 { margin:0; font-size:22px; }
      .content { padding:30px; color:#333; }
      .details { background:#f3edff; border-left:4px solid #7209b7; padding:15px 20px; border-radius:8px; }
      .details li { margin-bottom:8px; font-size:15px; }
      .footer { background:#f3f0ff; text-align:center; color:#666; font-size:13px; padding:12px; border-top:1px solid #ddd; }
      .footer strong { color:#6a11cb; }
    </style>
    </head>
    <body>
      <div class="container">
        <div class="header">
          <h1>TalentFlow HR – Offer Letter</h1>
        </div>
        <div class="content">
          <p>Dear <strong>' . htmlspecialchars($payload['candidate']) . '</strong>,</p>
          <p>Congratulations! We are pleased to offer you the position of <strong>' . htmlspecialchars($payload['role']) . '</strong> at our organization.</p>
          <div class="details">
            <ul>
              <li><strong>Offered Role:</strong> ' . htmlspecialchars($payload['role']) . '</li>
              <li><strong>CTC:</strong> ₹' . htmlspecialchars($payload['ctc']) . '</li>
              <li><strong>Joining Date:</strong> ' . htmlspecialchars($payload['start_date']) . '</li>
              <li><strong>Issued by:</strong> ' . htmlspecialchars($userEmail) . '</li>
            </ul>
          </div>
          <p>We’re excited to have you onboard! Please confirm your acceptance by replying to this email.</p>
          <p>Warm regards,<br><strong>TalentFlow HR Automation</strong></p>
        </div>
        <div class="footer">
          <p>© 2025 <strong>MACSEEDS</strong> | Hackathon Series — <strong>lablab.ai</strong></p>
        </div>
      </div>
    </body>
    </html>
    ';

    MailService::send($payload['email'], $subject, $html, $userEmail);

    $_SESSION['success'] = 'Offer generated and sent to candidate.';
    header('Location: ' . APP_URL . '/index.php?task=' . $taskId);
    exit;
  }
}
