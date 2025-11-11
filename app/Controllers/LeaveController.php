<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../Models/Leave.php';
require_once __DIR__ . '/../Models/Task.php';

class LeaveController
{
  public function form(): void
  {
    start_secure_session();
    if (!isset($_SESSION['user'])) {
      header('Location: ' . APP_URL . '/login.php');
      exit;
    }

    view('leave_form');
  }

  public function submit(): void
  {
    start_secure_session();

    if (!isset($_SESSION['user'])) {
      header('Location: ' . APP_URL . '/login.php');
      exit;
    }

    $userEmail = $_SESSION['user'];

    $payload = [
      'employee_name' => trim($_POST['employee_name'] ?? ''),
      'employee_email' => trim($_POST['employee_email'] ?? ''),
      'leave_type' => trim($_POST['leave_type'] ?? ''),
      'start_date' => trim($_POST['start_date'] ?? ''),
      'end_date' => trim($_POST['end_date'] ?? ''),
      'reason' => trim($_POST['reason'] ?? ''),
      'manager_email' => trim($_POST['manager_email'] ?? ''),
      'status' => 'Pending'
    ];

    // Store leave record
    $leaveId = Leave::create($payload);

    // Create a task entry for tracking
    Task::create('leave_request', $payload, $userEmail);

    $_SESSION['success'] = 'Leave request submitted successfully!';
    header('Location: ' . APP_URL . '/leave/summary');
    exit;
  }

  public function summary(): void
  {
    start_secure_session();

    if (!isset($_SESSION['user'])) {
      header('Location: ' . APP_URL . '/login.php');
      exit;
    }

    $leaves = Leave::all();
    view('leave_summary', compact('leaves'));
  }

  public function approve(): void
  {
    start_secure_session();

    if (!isset($_GET['id'])) {
      http_response_code(400);
      echo 'Invalid request';
      exit;
    }

    $id = (int) $_GET['id'];
    Leave::updateStatus($id, 'Approved');
    $_SESSION['success'] = 'Leave approved successfully!';
    header('Location: ' . APP_URL . '/leave/summary');
    exit;
  }

  public function reject(): void
  {
    start_secure_session();

    if (!isset($_GET['id'])) {
      http_response_code(400);
      echo 'Invalid request';
      exit;
    }

    $id = (int) $_GET['id'];
    Leave::updateStatus($id, 'Rejected');
    $_SESSION['success'] = 'Leave rejected successfully!';
    header('Location: ' . APP_URL . '/leave/summary');
    exit;
  }
}
