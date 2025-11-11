<?php
require_once __DIR__ . '/../app/Models/Task.php';
session_start();

if (!isset($_SESSION['admin'])) {
  header('Location: admin_login.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id']);
  $action = $_POST['action'] ?? '';

  try {
    switch ($action) {
      case 'delete':
        Task::delete($id);
        $_SESSION['toast'] = [
          'msg' => "Task #$id deleted successfully!",
          'type' => 'success'
        ];
        break;

      case 'update_status':
        $newStatus = trim($_POST['status'] ?? 'pending');
        Task::setStatus($id, $newStatus);
        $_SESSION['toast'] = [
          'msg' => "Task #$id updated to '$newStatus'.",
          'type' => 'info'
        ];
        break;

      default:
        $_SESSION['toast'] = [
          'msg' => 'Invalid action requested!',
          'type' => 'error'
        ];
        break;
    }

  } catch (Exception $e) {
    $_SESSION['toast'] = [
      'msg' => 'Error: ' . $e->getMessage(),
      'type' => 'error'
    ];
  }

  header('Location: admin_dashboard.php');
  exit;
}
?>
