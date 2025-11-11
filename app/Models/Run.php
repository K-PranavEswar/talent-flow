<?php
require_once __DIR__ . '/DB.php';

class RunLog {
  public static function add(int $taskId, string $step, string $status, string $message = '', ?array $data = null): void {
    $stmt = DB::pdo()->prepare('INSERT INTO runs(task_id, step, status, message, data) VALUES(?,?,?,?,?)');
    $stmt->execute([$taskId, $step, $status, $message, $data ? json_encode($data) : null]);
  }

  public static function forTask(int $taskId): array {
    $stmt = DB::pdo()->prepare('SELECT * FROM runs WHERE task_id=? ORDER BY id ASC');
    $stmt->execute([$taskId]);
    return $stmt->fetchAll();
  }
}
