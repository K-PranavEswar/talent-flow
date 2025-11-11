<?php
session_start();
require_once __DIR__ . '/../app/Models/Leave.php';
require_once __DIR__ . '/../app/Services/MailService.php';

if (!isset($_SESSION['admin'])) {
  header('Location: admin_login.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = (int)$_POST['id'];
  $action = $_POST['action'];

  $leave = Leave::find($id);

  if ($action === 'approve') {
    Leave::approve($id);
    $subject = "Leave Request Approved ✅";
    $message = "
      <h3>Hi {$leave['employee_name']},</h3>
      <p>Your leave request from <strong>{$leave['start_date']}</strong> to <strong>{$leave['end_date']}</strong> has been <b>approved</b>.</p>
      <p>Enjoy your time off!<br><br>Regards,<br>TalentFlow Admin</p>";
  } elseif ($action === 'reject') {
    Leave::reject($id);
    $subject = "Leave Request Rejected ❌";
    $message = "
      <h3>Hi {$leave['employee_name']},</h3>
      <p>We regret to inform you that your leave request from <strong>{$leave['start_date']}</strong> to <strong>{$leave['end_date']}</strong> has been <b>rejected</b>.</p>
      <p>Please contact your manager for clarification.<br><br>Regards,<br>TalentFlow Admin</p>";
  }

  // Send email notification to employee
  MailService::send($leave['employee_email'], $subject, $message);

  header('Location: admin_dashboard.php');
  exit;
}
?>
