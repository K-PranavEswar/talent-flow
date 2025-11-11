<?php
require_once __DIR__ . '/../../config/db.php';

class Leave {

  public static function create($data) {
    $pdo = DB::pdo();
    $stmt = $pdo->prepare("
      INSERT INTO leave_requests 
      (employee_name, employee_email, leave_type, start_date, end_date, reason, manager_email, status)
      VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')
    ");
    $stmt->execute([
      $data['employee_name'], $data['employee_email'], $data['leave_type'],
      $data['start_date'], $data['end_date'], $data['reason'], $data['manager_email']
    ]);
  }

  public static function all() {
    $pdo = DB::pdo();
    return $pdo->query("SELECT * FROM leave_requests ORDER BY id DESC")->fetchAll();
  }

  public static function approve($id) {
    $pdo = DB::pdo();
    $stmt = $pdo->prepare("UPDATE leave_requests SET status = 'Approved' WHERE id = ?");
    $stmt->execute([$id]);
  }

  public static function reject($id) {
    $pdo = DB::pdo();
    $stmt = $pdo->prepare("UPDATE leave_requests SET status = 'Rejected' WHERE id = ?");
    $stmt->execute([$id]);
  }

  public static function find($id) {
    $pdo = DB::pdo();
    $stmt = $pdo->prepare("SELECT * FROM leave_requests WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
  }
}
