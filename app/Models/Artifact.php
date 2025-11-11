<?php
require_once __DIR__ . '/DB.php';

class Artifact {
  public static function add(int $taskId, string $kind, string $value): void {
    $stmt = DB::pdo()->prepare('INSERT INTO artifacts(task_id, kind, value) VALUES(?,?,?)');
    $stmt->execute([$taskId, $kind, $value]);
  }

  public static function forTask(int $taskId): array {
    $stmt = DB::pdo()->prepare('SELECT * FROM artifacts WHERE task_id=?');
    $stmt->execute([$taskId]);
    return $stmt->fetchAll();
  }
}
