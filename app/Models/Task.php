<?php
require_once __DIR__ . '/DB.php';

class Task {

  // ✅ Create a new task for a specific user
  public static function create(string $type, array $payload, string $userEmail): int {
    $pdo = DB::pdo();
    $stmt = $pdo->prepare('
      INSERT INTO tasks (type, payload, status, user_email, created_at)
      VALUES (?, ?, "pending", ?, NOW())
    ');
    $stmt->execute([$type, json_encode($payload), $userEmail]);
    return (int)$pdo->lastInsertId();
  }

  // ✅ Find a task by ID
  public static function find(int $id): ?array {
    $pdo = DB::pdo();
    $stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = ?');
    $stmt->execute([$id]);
    $task = $stmt->fetch();
    if (!$task) return null;

    $task['payload'] = json_decode($task['payload'], true);
    return $task;
  }

  // ✅ Update the status of a task
  public static function setStatus(int $id, string $status): void {
    $pdo = DB::pdo();
    $stmt = $pdo->prepare('UPDATE tasks SET status = ?, updated_at = NOW() WHERE id = ?');
    $stmt->execute([$status, $id]);
  }

  // ✅ Mark a specific task as Accepted (used for Offer confirmations)
  public static function markAccepted(int $id): void {
    $pdo = DB::pdo();
    $stmt = $pdo->prepare('
      UPDATE tasks
      SET status = "done",
          notes = "Offer accepted by candidate",
          updated_at = NOW()
      WHERE id = ?
    ');
    $stmt->execute([$id]);
  }

  // ✅ Update task details (used in Edit)
  public static function update(int $id, string $type, string $status, string $userEmail): void {
    $pdo = DB::pdo();
    $stmt = $pdo->prepare('
      UPDATE tasks SET type = ?, status = ?, user_email = ?, updated_at = NOW()
      WHERE id = ?
    ');
    $stmt->execute([$type, $status, $userEmail, $id]);
  }

  // ✅ Delete a task
  public static function delete(int $id): void {
    $pdo = DB::pdo();
    $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = ?');
    $stmt->execute([$id]);
  }

  // ✅ Get all tasks for a specific user
  public static function forUser(string $userEmail): array {
    $pdo = DB::pdo();
    $stmt = $pdo->prepare('SELECT * FROM tasks WHERE user_email = ? ORDER BY id DESC');
    $stmt->execute([$userEmail]);
    $tasks = $stmt->fetchAll();

    foreach ($tasks as &$task) {
      $task['payload'] = json_decode($task['payload'], true);
    }
    return $tasks;
  }

  // ✅ Get all tasks (for admin/global dashboard)
  public static function all(): array {
    $pdo = DB::pdo();
    $stmt = $pdo->query('SELECT * FROM tasks ORDER BY id DESC');
    $tasks = $stmt->fetchAll();

    foreach ($tasks as &$task) {
      $task['payload'] = json_decode($task['payload'], true);
    }
    return $tasks;
  }
}
